<?php

/**
 * Plugin Name:       PostTube
 * Plugin URI:		  https://posttube.co
 * Description:       A simple and easy way to convert your WordPress post to audio and video.
 * Version:           1.3
 * Author:            Senol Sahin
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or exit;

include plugin_dir_path( __FILE__ ) . 'includes/functions.php';
include plugin_dir_path( __FILE__ ) .  'vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\ListVoicesResponse;

define( 'TUBE_TIME', time() );
define( 'TUBE_ROOT', dirname(__FILE__) );
$upload_dir = wp_upload_dir();

define( 'TUBE_POST_UPLOADS_PATH', $upload_dir['basedir'] );

add_action('admin_menu', 'tubepost_plugin_setup_menu');

function tubepost_plugin_setup_menu()
{
	add_menu_page(
        'PostTube Plugin Page', 
        'PostTube', 
        'manage_options',
		'tube_post',
        'tube_post_page',
        plugins_url( 'includes/assets/img/admin_icon.png',__FILE__ ),
        '6');
}

function tubepost_plugin_activate() { 
	$upload_dir = wp_upload_dir(); 
	$tube_audio_dirname = $upload_dir['basedir'] . '/tube_post/audio';
	$tube_video_dirname = $upload_dir['basedir'] . '/tube_post/video';
	if(!file_exists($tube_audio_dirname)) wp_mkdir_p($tube_audio_dirname);
	if(!file_exists($tube_video_dirname)) wp_mkdir_p($tube_video_dirname);
	tubepost_init_database();
}
register_activation_hook( __FILE__, 'tubepost_plugin_activate' );


function tube_post_page() {
	include_once(TUBE_ROOT.'/includes/index.php');
}

function tubepost_call_text_to_speech($post)
{
	if (tubepost_isEnablePlugin() && !tubepost_isApiKeyExit()) return;
	
	if(!tubepost_isPluginEnable()) return;
	$post_id = $post->ID;
	
	$post_title = str_replace(" ", "_", substr($post->post_name,0,15));
	$post_title = str_replace("&#8217;", "", $post_title);
	$video_text = str_replace("&#8217;", "", $post->post_name);
	$video_text = str_replace("-", " ", $video_text);
	
	define( 'TUBE_VIDEO_TEXT', $video_text);
	define( 'TUBE_POST_TITLE', $post_title);
	define( 'TUBE_POST_ID', $post_id );
	
    $post = get_post( $post_id ) ;
    if ($post->post_content == null) return;
    $post_content = wp_strip_all_tags($post->post_content);
    $post_content = str_replace("&nbsp;", " ", $post_content);
    $post_content = str_replace(array(
        "\n",
        "\r"
    ) , " ", $post_content);
    $post_content = str_replace("%C2%A0", " ", $post_content);
   

    $post_content = (strlen($post_content) > 5000) ? substr($post_content,0,4999) : $post_content;
	
	tubepost_generate_audio($post_content);
	
    tuebpost_generate_video();

}

function tubepost_schedule_text_to_speech($post_id)
{
	if (tubepost_isEnablePlugin() && !tubepost_isApiKeyExit()) return;
	
	if(!tubepost_isPluginEnable()) return;
	$post_name = get_the_title( $post_id );
	
    $post = get_post( $post_id ) ;
	
	$post_title = str_replace("’", "", $post->post_title);
	$post_title = str_replace(" ", "_", substr($post_title,0,15));
	$post_title = str_replace("&#8217;", "", $post_title);
	$post_title = str_replace(",", "", $post_title);
	$video_text = str_replace("&#8217;", "", $post->post_title);
	$video_text = str_replace("’", "", $video_text);
	$video_text = str_replace("-", " ", $video_text);
	$video_text = str_replace(",", "", $video_text);
	
	define( 'TUBE_VIDEO_TEXT', $video_text);
	define( 'TUBE_POST_TITLE', $post_title);
	define( 'TUBE_POST_ID', $post_id );
	
    if ($post->post_content == null) return;
    $post_content = wp_strip_all_tags($post->post_content);
    $post_content = str_replace("&nbsp;", " ", $post_content);
    $post_content = str_replace(array(
        "\n",
        "\r"
    ) , " ", $post_content);
    $post_content = str_replace("%C2%A0", " ", $post_content);
   

    $post_content = (strlen($post_content) > 5000) ? substr($post_content,0,4999) : $post_content;
	
	tubepost_generate_audio($post_content);
	
    tuebpost_generate_video();

}
// add_action('save_post', 'tubepost_schedule_text_to_speech', 10 , 2);
add_action('auto-draft_to_publish', 'tubepost_call_text_to_speech');
add_action('publish_future_post', 'tubepost_schedule_text_to_speech');

function tubepost_generate_audio($post_content) {
	global $wpdb;
	$json_path = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'key_path'");
	
	if($json_path == "") return;
	
	putenv('GOOGLE_APPLICATION_CREDENTIALS='.$json_path);
	
	$client = new TextToSpeechClient();
	$lang = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'select_language'");
	$selected_voice = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'select_voice'");
	
	$is_random_voice = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'random_voice'");
	
	$profile = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'audio_profile'");
	$speed = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'speaking_voice'");
	$pitch = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'pitch'");
	
	$synthesisInputText = (new SynthesisInput())
	    ->setText($post_content);
	
	if($is_random_voice) {
		$select = json_decode($selected_voice);
		$ran = rand(0,count($select));
		$selected_voice = $select[$ran];
	}
	
	$voice = (new VoiceSelectionParams())
	->setLanguageCode($lang)
	->setName( $selected_voice );

	$effectsProfileId = $profile;

	$audioConfig = (new AudioConfig())
	    ->setAudioEncoding(AudioEncoding::MP3)
	    ->setEffectsProfileId(array($effectsProfileId))
	    ->setSpeakingRate($speed)
        ->setPitch( $pitch )
        ->setSampleRateHertz( 24000 );
	
	$response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
	$audioContent = $response->getAudioContent();
	
	file_put_contents(TUBE_POST_UPLOADS_PATH . '/tube_post/audio/'.TUBE_POST_TITLE.'_'.TUBE_TIME.'.mp3', $audioContent);
	
}

function tuebpost_generate_video()
{
	
    $input_audio = TUBE_POST_UPLOADS_PATH . '/tube_post/audio/'.TUBE_POST_TITLE.'_'.TUBE_TIME.'.mp3';
//     $input_image = TUBE_ROOT . '/includes/assets/img/sample.png';
	
	global $wpdb;
	$image_path = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='image_path'");

	$font_color = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='font_color'");
	$font_size = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='font_size'");
	$font_style = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='font_style'");
	$font_position = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='position'");
	$istitle = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='istitle'");
	
	if($image_path == "") return;
	
	$input_image = $image_path;
	
	if($font_position == "Center")
		$text_postion = "x=(w-text_w)/2: y=(h-text_h)/2";
    else if($font_position == "Left")
		$text_postion = "x=10: y=(h-text_h)/2";
    else
		$text_postion = "x=(w-text_w): y=(h-text_h)/2";
    
	$output = TUBE_POST_UPLOADS_PATH . '/tube_post/video/'.TUBE_POST_TITLE.'_'.TUBE_TIME.'.mp4';
	
	
	if($istitle) {
		
		$command = 'ffmpeg -y -loop 1 -threads 1 -i ' . $input_image . ' -i ' . $input_audio . ' -vf drawtext="fontfile=/path/to'.TUBE_ROOT.'/includes/assets/font/'.$font_style.'.ttf:  text='.TUBE_VIDEO_TEXT.': fontcolor='. $font_color .': fontsize='.$font_size.': box=1: boxcolor=black@0.5: boxborderw=5: '.$text_postion.'" -c:v libx264 -crf 30 -tune stillimage -c:a aac -b:a 192k -pix_fmt yuv420p -shortest ' . $output;

	}
	else {
		$command = 'ffmpeg -y -loop 1 -threads 1 -i ' . $input_image . ' -i ' . $input_audio . ' -c:v libx264 -crf 30 -tune stillimage -c:a aac -b:a 192k -pix_fmt yuv420p -shortest ' . $output;
		
	}
// 	die(print_r($command));
    $log = TUBE_ROOT . '/log.txt';
	
	$audio_name = TUBE_POST_TITLE.'_'.TUBE_TIME.'.mp3';
	
	global $wpdb;
	$wpdb->query("INSERT INTO {$wpdb->prefix}tube_log (post_id,post_date,post_title,audio_name,video_name,video_location,post_status,failed_reason) VALUES ('".TUBE_POST_ID."','".date("Y-m-d H:i:s",TUBE_TIME)."', '".TUBE_POST_TITLE."','".$audio_name."', '','".$output."','pendding','') ");

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        pclose(popen("start /B " . $command . " 1> $log 2>&1", "r")); // windows
    }
    else
    {
		$result = '';
		shell_exec($command . " 1> $log 2>&1 >/dev/null &"); //linux
    }
	
		$video_name = TUBE_POST_TITLE.'_'.TUBE_TIME.'.mp4';
		$result = 'success';
		$reason = '';

		$wpdb->query("UPDATE {$wpdb->prefix}tube_log SET post_status = '".$result."',video_name = '".$video_name."',failed_reason = '".$reason."', video_location = '".$output."' WHERE audio_name = '".$audio_name."'");
	
}


function tubepost_settings_save_action () {
	global $wpdb;
	$json=array();
	
	$lang = sanitize_text_field( $_POST['lang'] );
	$voice = sanitize_text_field( $_POST['voice'] );
	$profile = sanitize_text_field($_POST['profile']);
	$speed = sanitize_text_field($_POST['speed']);
	$pitch = sanitize_text_field($_POST['pitch']);
	$random = sanitize_text_field($_POST['random']);
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$lang."' WHERE option_name='select_language'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$voice."' WHERE option_name='select_voice'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$profile."' WHERE option_name='audio_profile'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$speed."' WHERE option_name='speaking_voice'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$pitch."' WHERE option_name='pitch'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$random."' WHERE option_name='random_voice'");

}

add_action('wp_ajax_settings_save_action','tubepost_settings_save_action');
add_action('wp_ajax_nopriv_settings_save_action','tubepost_settings_save_action');


function tubepost_activition_action () {
	global $wpdb;
	
	$val = sanitize_text_field( $_POST['val'] );
	$query = "UPDATE {$wpdb->prefix}tube_config SET option_value = '".$val."' WHERE option_name='activate_status'";
	$wpdb->query($query);
}

add_action('wp_ajax_activition_action','tubepost_activition_action');
add_action('wp_ajax_nopriv_activition_action','tubepost_activition_action');



function tubepost_remove_alldata_action () {
	global $wpdb;

	$query = "DELETE FROM {$wpdb->prefix}tube_log;";
	$wpdb->query($query);
	$audio_files = glob(TUBE_POST_UPLOADS_PATH.'/tube_post/audio/*'); // get all file names
	foreach($audio_files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}
	$video_files = glob(TUBE_POST_UPLOADS_PATH.'/tube_post/video/*'); // get all file names
	foreach($video_files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}
}

add_action('wp_ajax_remove_alldata_action','tubepost_remove_alldata_action');
add_action('wp_ajax_nopriv_remove_alldata_action','tubepost_remove_alldata_action');
	



function tubepost_remove_log_item_action () {
	global $wpdb;

	$val = sanitize_text_field( $_POST['val'] );
	
	$video_name = $wpdb->get_var("SELECT video_name FROM {$wpdb->prefix}tube_log WHERE id='".$val."'");
	$audio_name = $wpdb->get_var("SELECT audio_name FROM {$wpdb->prefix}tube_log WHERE id='".$val."'");
	unlink(TUBE_POST_UPLOADS_PATH . '/tube_post/audio/'.$audio_name);
	unlink(TUBE_POST_UPLOADS_PATH . '/tube_post/video/'.$video_name);
	$wpdb->query("DELETE FROM {$wpdb->prefix}tube_log WHERE id='".$val."'");
}

add_action('wp_ajax_remove_log_item_action','tubepost_remove_log_item_action');
add_action('wp_ajax_nopriv_remove_log_item_action','tubepost_remove_log_item_action');


function tubepost_save_video_config () {
	global $wpdb;
	

	$istitle = sanitize_text_field( $_POST['istitle'] );
	$position = sanitize_text_field( $_POST['position'] );
	$font_style = sanitize_text_field( $_POST['font_style'] );
	$font_size = sanitize_text_field( $_POST['font_size'] );
	$font_color = sanitize_text_field( $_POST['font_color'] );
	$title_codec = sanitize_text_field( $_POST['title_codec'] );
	$title_format = sanitize_text_field( $_POST['title_format'] );
	$title_resolution = sanitize_text_field( $_POST['title_resolution'] );
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$istitle."' WHERE option_name='istitle'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$position."' WHERE option_name='position'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$font_style."' WHERE option_name='font_style'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$font_size."' WHERE option_name='font_size'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$font_color."' WHERE option_name='font_color'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$title_codec."' WHERE option_name='title_codec'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$title_format."' WHERE option_name='title_format'");
	$wpdb->query("UPDATE {$wpdb->prefix}tube_config SET option_value = '".$title_resolution."' WHERE option_name='title_resolution'");

}

add_action('wp_ajax_save_video_config','tubepost_save_video_config');
add_action('wp_ajax_nopriv_save_video_config','tubepost_save_video_config');


function tubepost_mime_types( $mimes ) {
 
// New allowed mime types.
$mimes['json']='application/json';
// Optional. Remove a mime type.
unset( $mimes['exe'] );
 
return $mimes;
}
add_filter( 'upload_mimes', 'tubepost_mime_types' );

function tubepost_init_database() {
global $wpdb;
$wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}tube_log`  (
	  `id` int NOT NULL AUTO_INCREMENT,
	  `post_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	  `post_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	  `post_date` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	  `audio_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	  `video_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	  `video_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	  `post_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	  `failed_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
	  PRIMARY KEY (`id`) USING BTREE)");
$wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}tube_config` (
		`id` int NOT NULL AUTO_INCREMENT,
		`option_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`option_value` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		PRIMARY KEY (`id`) USING BTREE)");

}
?>

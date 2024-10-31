<?php

function tubepost_isEnablePlugin() {
	if(tubepost_get_mbstring_installed() == 'NO' || tubepost_get_curl_installed() == 'NO' || tubepost_get_zip_installed() == 'NO' || tubepost_get_bcmath_installed() == 'NO' || tubepost_get_xml_installed() == 'NO' || tubepost_get_dom_installed() == 'NO' || tubepost_get_shell_enabled() == 'NO' || tubepost_get_ffmpeg_installed() == 'NO') 
		return true;
	else return false;
}

function tubepost_isApiKeyExit() {
	try {
		global $wpdb;
		$json_path=$wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='key_path'");
		
		$file_exists = file_exists ( $json_path );

		return $file_exists ? true : false;
	} catch (Exception $e) {
	    	return false;
	}
}

function tubepost_isPluginEnable() {
	try {
		global $wpdb;
		$val = $wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name = 'activate_status'");

		return $val;
	} catch (Exception $e) {
	    	return false;
	}
}

function tubepost_isImageExit() {
	try {
		global $wpdb;
		$image_path=$wpdb->get_var("SELECT option_value FROM wp_tube_config WHERE option_name='image_path'");
		$file_exists = file_exists ( $image_path );

		return $file_exists ? true : false;
	} catch (Exception $e) {
	    	return false;
	}
}

function tubepost_checkKeyFile($path) {
	
	$str = file_get_contents($path);
	$json = json_decode($str, true);
	if (strlen($json['private_key']) > 1500)
		return true;
	else 
		return false;
}

// function random_voice() {
	
// 	$json_path = $wpdb->get_var("SELECT option_value FROM {$wpdb->prefix}tube_config option_name = 'key_path'");
// 	putenv('GOOGLE_APPLICATION_CREDENTIALS='.$json_path);

// 	$client = new TextToSpeechClient();

// 	$response = $client->listVoices();
	
// 	$voices   = $response->getVoices();
// }

function tubepost_get_shell_enabled () {
	try {
		$shell_enable = is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec');

		return $shell_enable ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_zip_installed() {
	try {
		$zip_installed = extension_loaded( 'zip' );

		return $zip_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_dom_installed() {
	try {
		$dom_installed = extension_loaded( 'dom' );

		return $dom_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_xml_installed() {
	try {
		$xml_installed = extension_loaded( 'xml' );

		return $xml_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_curl_installed() {
	try {
		$curl_installed = extension_loaded( 'curl' );

		return $curl_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}


function tubepost_get_bcmath_installed () {
	try {
		$bcmath_installed = extension_loaded( 'bcmath' );

		return $bcmath_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_mbstring_installed() {
	try {
		$mbstring_installed = extension_loaded( 'mbstring' );

		return $mbstring_installed ? 'YES' : 'NO';
	} catch (Exception $e) {
	    return 'NO';
	}
}

function tubepost_get_ffmpeg_installed () {
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        $ffmpeg = pclose(popen("ffmpeg -version", "r")); // windows
    }
    else {
		$ffmpeg = trim(shell_exec('ffmpeg -version'));
    }
	if (empty($ffmpeg))
	{
	    return 'NO';
	}
	else
		return 'YES';
}

function tubepost_is_ffmpeg_version4xx () {
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        $ffmpeg = pclose(popen("ffmpeg -version", "r")); // windows
    }
    else {
		$ffmpeg = trim(shell_exec('ffmpeg -version'));
    }
	if (strtoupper(substr($ffmpeg, 15, 1)) == "4")
	{
	    return 'YES';
	}
	else
		return 'NO';
}

?>
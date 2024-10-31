=== PostTube Audio/Video Generator - Convert Your Post into Video ===
Contributors: senols
Donate link: https://profiles.wordpress.org/senols/
Author URI: https://twitter.com/senolsahin
Plugin URL: https://posttube.co/
Tags: video, audio, video converter, audio converter, converter, text-to-speech, ffmpeg, youtube, speech
Requires at least: 3.3
Tested up to: 5.8
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
A simple and easy way to convert your WordPress post to audio and video.
 
== Description ==

<h4>Official Website</h4>

You can find more information on our web site <a href="https://posttube.co/">posttube.co</a>.

https://www.youtube.com/watch?v=y6_4c3wEoSY

PostTube is a plugin that convert your WordPress post to audio and video.

Once you publish a post, PostTube convert that post content into audio then convert it again into a video.

PostTube uses Google Text-to-Speech API. So you need to get API key from Google in order to use the plugin. Total characters per request is 5000.

<h4>Features</h4>

1. Audio generation triggered by publishing a new post automatically.
2. Video generation triggered by publishing a new post automatically.
3. Integration with Google Text-to-Speech API.
4. Convert text into natural-sounding speech using an API powered by Google’s AI technologies.
5. Choose from a set of 220+ voices across 40+ languages and variants. Pick the voice that works best for your posts.
6. Take advantage of 90+ WaveNet voices built based on DeepMind’s groundbreaking research to generate speech that significantly closes the gap with human performance.
7. Personalize the pitch of your selected voice, up to 20 semitones more or less from the default. Adjust your speaking rate to be 4x faster or slower than the normal rate.
8. Adjust your speaking rate to be 4x faster or slower than the normal rate.
9. Optimize for the type of speaker from which your speech is intended to play, such as headphones or phone lines.
10. Add stunning images into your videos.
11. FFMPEG integration.
12. Add text into your video.
13. Select random voice for each video.
14. Scheduled post will also trigger video generation.

== Installation ==

1. Upload `PostTube.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Check system requirements and make sure all required extensions are installed.
4. Setup Google Text-to-Speech API.
5. Select language and voice.
6. Configure video settings and import a sample image for your videos.

== Screenshots ==
1. Main Screen
2. API Settings
3. Voice Settings
4. Video Settings
5. Requirements
6. Library
7. Video

== Frequently Asked Questions ==
 
= What is PostTube? =

PostTube is a plugin that convert your WordPress post to video.

= How does it work? =
 
Once you publish a post, PostTube convert that post content into audio speech then convert it again into a video.

= How does it generate audio from a post? =
 
PostTube uses Google Text-to-Speech API. So you need to get API key from Google in order to use the plugin. Total characters per request is 5000.

= How does it generate video from a post? =
 
PostTube use the speech audio and add one single image into the video. You need to provide an image for the video.

= What triggers video generation? =
 
Video generation is triggered by publishing a new post.

= Does scheduled posts also generate video? =
 
Yes.

= What are the system requirements? =
 
You need to have at least 2 GB memory + 2 CPUs to have a good performance. However in our tests even DigitalOcean’s 5$ droplets worked fine.

Below extensions and features must be installed on your server;

mbstring, cURL, zip, xml, DOM, BCMath, shell_exec, ffmpeg 4.x.x

Plugin wont work until all these features are enabled.

= Is it free? =

Yes.

== Changelog ==

= 1.3 =
* Now you can add post title in your video.
* You can adjust font type, size, position and color.
* Random voice: if you enable this feature it will select random voice each time a video is generated.
* API validation: now there is a validation against invalid key file.
* You can delete video files one by one or you can remove them all at once.


= 1.0.1 =
* Small fixes.
 
= 1.0.0 =
* First release.
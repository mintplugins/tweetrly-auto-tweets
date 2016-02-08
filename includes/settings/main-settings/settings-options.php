<?php			
/**
 * This is the code that will create a new page of settings for your page.
 * To set up this page:
 * Step 1. Include this page in your plugin/theme
 * Step 2. Do a find-and-replace for the term 'tweetrly_auto_tweets_settings' and replace it with the slug you desire for this page
 * Step 3. Go to line 17 and set the title, slug, and type for this page.
 * Step 4. Include options tabs.
 * Go here for full setup instructions: 
 * http://mintplugins.com/doc/settings-class/
 */

function tweetrly_auto_tweets_settings(){
	
	/**
	 * Set args for new administration menu.
	 *
	 * For complete instructions, visit:
	 * http://mintplugins.com/doc/settings-class-args/
	 *
	 */
	$args = array(
		'title' => __('Auto Tweets', 'mp_core'), 
		'slug' => 'tweetrly_auto_tweets_settings', 
		'type' => 'object',
		'icon' => 'dashicons-twitter'
	);
	
	//Initialize settings class
	global $tweetrly_auto_tweets_settings;
	$tweetrly_auto_tweets_settings = new MP_CORE_Settings($args);
	
	//Include other option tabs
	include_once( 'settings-tab-general.php' );
	
}
add_action('plugins_loaded', 'tweetrly_auto_tweets_settings', 99);
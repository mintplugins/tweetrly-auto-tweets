<?php
/**
 * This file contains the function keeps the Tweetrly Auto Tweets plugin up to date.
 *
 * @since 1.0.0
 *
 * @package    Tweetrly Auto Tweets
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2016, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Check for updates for the Tweetrly Auto Tweets Plugin by creating a new instance of the MP_CORE_Plugin_Updater class.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
 if (!function_exists('tweetrly_auto_tweets_update')){
	function tweetrly_auto_tweets_update() {
		$args = array(
			'software_name' => 'Tweetrly Auto Tweets', //<- The exact name of this Plugin. Make sure it matches the title in your tweetrly_auto_tweets, edd, and the WP.org stacks
			'software_api_url' => 'http://mintplugins.com',//The URL where EDD and tweetrly_auto_tweets are installed and checked
			'software_filename' => 'tweetrly-auto-tweets.php',
			'software_licensed' => true, //<-Boolean
		);
		
		//Since this is a plugin, call the Plugin Updater class
		$tweetrly_auto_tweets_plugin_updater = new MP_CORE_Plugin_Updater($args);
	}
 }
add_action( 'admin_init', 'tweetrly_auto_tweets_update' );

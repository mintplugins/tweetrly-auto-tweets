<?php
/*
Plugin Name: Tweetrly Auto Tweets
Plugin URI: http://mintplugins.com
Description: Post your tweets.
Version: 1.0.0.0
Author: Mint Plugins
Author URI: http://mintplugins.com
Text Domain: tweetrly_auto_tweets
Domain Path: languages
License: GPL2
*/

/*  Copyright 2016  Phil Johnston  (email : phil@mintplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Mint Plugins Core.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
// Plugin version
if( !defined( 'TWEETRLY_AUTO_TWEETS_VERSION' ) )
	define( 'TWEETRLY_AUTO_TWEETS_VERSION', '1.0.0.0' );

// Plugin Folder URL
if( !defined( 'TWEETRLY_AUTO_TWEETS_PLUGIN_URL' ) )
	define( 'TWEETRLY_AUTO_TWEETS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Folder Path
if( !defined( 'TWEETRLY_AUTO_TWEETS_PLUGIN_DIR' ) )
	define( 'TWEETRLY_AUTO_TWEETS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Root File
if( !defined( 'TWEETRLY_AUTO_TWEETS_PLUGIN_FILE' ) )
	define( 'TWEETRLY_AUTO_TWEETS_PLUGIN_FILE', __FILE__ );

/*
|--------------------------------------------------------------------------
| GLOBALS
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| INTERNATIONALIZATION
|--------------------------------------------------------------------------
*/

function tweetrly_auto_tweets_textdomain() {

	// Set filter for plugin's languages directory
	$tweetrly_auto_tweets_lang_dir = dirname( plugin_basename( TWEETRLY_AUTO_TWEETS_PLUGIN_FILE ) ) . '/languages/';
	$tweetrly_auto_tweets_lang_dir = apply_filters( 'tweetrly_auto_tweets_languages_directory', $tweetrly_auto_tweets_lang_dir );


	// Traditional WordPress plugin locale filter
	$locale        = apply_filters( 'plugin_locale',  get_locale(), 'tweetrly-auto-tweets' );
	$mofile        = sprintf( '%1$s-%2$s.mo', 'tweetrly-auto-tweets', $locale );

	// Setup paths to current locale file
	$mofile_local  = $tweetrly_auto_tweets_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/tweetrly-auto-tweets/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/tweetrly-auto-tweets folder
		load_textdomain( 'tweetrly_auto_tweets', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/tweetrly-auto-tweets/languages/ folder
		load_textdomain( 'tweetrly_auto_tweets', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'tweetrly_auto_tweets', false, $tweetrly_auto_tweets_lang_dir );
	}

}
add_action( 'init', 'tweetrly_auto_tweets_textdomain', 1 );


/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/

/**
 * Activation Hook Function - Sets up Rewrite Rules, Sample Stack Page, User Roles and more
 */
//require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/misc-functions/install.php' );

/**
 * Load files dependant on MP Core - if no MP Core, output a button to install it first
 */
function tweetrly_auto_tweets_include_files(){
	/**
	 * If mp_core isn't active, stop and install it now
	 */
	if (!function_exists('mp_core_textdomain')){
		
		/**
		 * Include Plugin Checker
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-checker.php' );
		
		/**
		 * Include Plugin Installer
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-installer.php' );
		
		/**
		 * Check if wp_core in installed
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-core-check.php' );
		
	}
	/**
	 * Otherwise, if mp_core is active, carry out the plugin's functions
	 */
	else{
		
		/**
		 * Update script - keeps this plugin up to date
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/updater/tweetrly-auto-tweets-update.php' );
		
		/**
		 * Settings
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/settings/main-settings/settings-options.php' );
		
		/**
		 * Enqueue Scripts
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/misc-functions/enqueue-scripts.php' );
		
		/**
		 * Ajax Callbacks
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/misc-functions/ajax-callbacks.php' );
		
		/**
		 * Tweet Filters
		 */
		require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/misc-functions/tweet-filters.php' );
	
	}
}
add_action('plugins_loaded', 'tweetrly_auto_tweets_include_files', 9);
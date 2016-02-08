<?php			
/**
 * This is the code that will create a new tab of settings for your page.
 * To create a new tab and set up this page:
 * Step 1. Duplicate this page and include it in the "class initialization function".
 * Step 1. Do a find-and-replace for the term 'tweetrly_auto_tweets_settings' and replace it with the slug you set when initializing this class
 * Step 2. Do a find and replace for 'general' and replace it with your desired tab slug
 * Step 3. Go to line 17 and set the title for this tab.
 * Step 4. Begin creating your custom options on line 30
 * Go here for full setup instructions: 
 * http://mintplugins.com/settings-class/
 */

/**
* Create new tab
*/
function tweetrly_auto_tweets_settings_general_new_tab( $active_tab ){
	
	//Create array containing the title and slug for this new tab
	$tab_info = array( 'title' => __('Tweetrly Auto Tweets Settings' , 'mp_stacks_socialgrid'), 'slug' => 'general' );
	
	global $tweetrly_auto_tweets_settings; $tweetrly_auto_tweets_settings->new_tab( $active_tab, $tab_info );
		
}
//Hook into the new tab hook filter contained in the settings class in the Mint Plugins Core
add_action('tweetrly_auto_tweets_settings_new_tab_hook', 'tweetrly_auto_tweets_settings_general_new_tab');


/**
* Create settings
*/
function tweetrly_auto_tweets_settings_general_create(){
	
	
	register_setting(
		'tweetrly_auto_tweets_settings_general',
		'tweetrly_auto_tweets_settings_general',
		'mp_core_settings_validate'
	);
	
	add_settings_section(
		'general_settings',
		__( 'General Settings', 'tweetrly_auto_tweets' ),
		'tweetrly_auto_tweets_top_description',
		'tweetrly_auto_tweets_settings_general'
	);
	
	//If we don't have a password enetered by the user, make one now.
	$tweetrly_auto_tweets_custom_url_password = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_custom_url_password' );
	
	if ( empty( $tweetrly_auto_tweets_custom_url_password ) ){
		$tweetrly_auto_tweets_custom_url_password = wp_generate_password( 20, false );
	}
	
	function tweetrly_auto_tweets_top_description(){
		
		//Get the URL password required by the user
		$tweetrly_auto_tweets_custom_url_password = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_custom_url_password' );
			
		echo '<p>' . __( 'Disclaimer: Before you start, make sure to review Twitter\'s Automation best practices to avoid getting your Twitter account deleted. This is a powerful plugin which could post a lot of tweets very quickly. We don\'t recommend using it to do that as it could get your Twitter account deleted. Don\'t post too many of the same exact thing too often. Space out your tweets and make them as unique as possible.', 'mp_post tweets' ) . '</p>
			<p>' . __( 'Twitter Best Practices', 'tweetrly_auto_tweets' ) . ': <a href="https://support.twitter.com/articles/76915" target="_blank">https://support.twitter.com/articles/76915</a></p>
			<a class="button" href="' . add_query_arg( array( 
				'tweetrly_auto_tweets_post_tweets' => true,
				'tweetrly_auto_tweets_key' => $tweetrly_auto_tweets_custom_url_password,
				//'page' => 'tweetrly_auto_tweets_settings'
			), get_bloginfo( 'wpurl' ) ) . '">' . __( 'Start Posting the Tweets for Today', 'tweetrly_auto_tweets' ) . '</a>';
			
	}
	
	add_settings_field(
		'tweetrly_auto_tweets_twitter_consumer_key',
		__( 'Consumer Key', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_twitter_consumer_key',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_consumer_key' ),
			'preset_value'       => "",
			'description' => __( 'Enter your Twitter App\'s Consumer Key.', 'tweetrly_auto_tweets' ) . __( 'To get a "Consumer Key" and "Consumer Secret", follow these steps carefully: <ul>
				<li> 1: <a href="https://apps.twitter.com/app/new" target="_blank">Click here</a> to create a Twitter "Application".</li>
				<li> 2. Make the "Name" something like "' . get_bloginfo( 'name' ) . '"</li>
				<li> 4. Enter the description as something like "Interaction between Twitter and ' . get_bloginfo( 'name' ) . '"</li>
				<li> 5. Enter your website "' . get_bloginfo( 'wpurl' ) . '" under both "Website" and "Callback URL".</li>
				<li> 6. Click "Create your Twitter application" at the bottom.</li>
				<li> 7. Now, <a href="https://apps.twitter.com/" target="_blank">Click Here</a>, and select the Application you just made.</li>
				<li> 8. Find the tab called "Keys and Access Tokens" and click on it.</li>
				<li> 8. Copy the "Consumer Key (API Key)" and paste it above.</li>
				<li> 9. Copy the "Access Token" and paste it below in the corresponding field below.</li>
				<li> 9. Copy the "Access Token Secret" and paste it below in the corresponding field below.</li>
				</ul>
				<br />
				Lost your Twitter credentials? No Problem! <a href="https://apps.twitter.com/" target="_blank">Click here to find them.</a>
				', 'mp_stacks_socialgrid' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_twitter_consumer_secret',
		__( 'Consumer Secret', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_twitter_consumer_secret',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_consumer_secret' ),
			'preset_value'       => "",
			'description' => __( 'Enter your Twitter App\'s Consumer Secret', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_twitter_access_token',
		__( 'Access Token', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_twitter_access_token',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_access_token' ),
			'preset_value'       => "",
			'description' => __( 'Enter your Twitter App\'s Access Token', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_twitter_access_token_secret',
		__( 'Access Token Secret', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_twitter_access_token_secret',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_access_token_secret' ),
			'preset_value'       => "",
			'description' => __( 'Enter your Twitter App\'s Access Token Secret', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_time_delay',
		__( 'Tweet Delay in Seconds', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_time_delay',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_time_delay' ),
			'preset_value'       => "",
			'description' => '<p>' . __( 'How many seconds should pass between tweets being posted? EG: 30 minutes is 1800.', 'tweetrly_auto_tweets' ) . '</p><p>' . __( 'It is not recommended to go any lower than 120 (120 seconds) or Twitter may not be happy with you and could delete your account for spamming. Use at your own risk.', 'tweetrly_auto_tweets' ) . '</p>',
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_sunday',
		__( 'Tweets for Sunday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_sunday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_sunday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Sunday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_monday',
		__( 'Tweets for Monday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_monday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_monday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Monday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_tuesday',
		__( 'Tweets for Tuesday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_tuesday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_tuesday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Tuesday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_wednesday',
		__( 'Tweets for Wednesday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_wednesday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_wednesday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Wednesday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_thursday',
		__( 'Tweets for Thursday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_thursday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_thursday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Thursday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_friday',
		__( 'Tweets for Friday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_friday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_friday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Friday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_tweets_for_saturday',
		__( 'Tweets for Saturday', 'tweetrly_auto_tweets' ), 
		'mp_core_textarea',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_tweets_for_saturday',
			'value'       => mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_saturday' ),
			'preset_value'       => "",
			'description' => __( 'Enter a list of tweets for Saturday. Put each new tweet on a new line (Hit "return" to start a new line).', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
	
	add_settings_field(
		'tweetrly_auto_tweets_custom_url_password',
		__( 'Your Custom URL Password', 'tweetrly_auto_tweets' ), 
		'mp_core_textbox',
		'tweetrly_auto_tweets_settings_general',
		'general_settings',
		array(
			'name'        => 'tweetrly_auto_tweets_custom_url_password',
			'value'       => $tweetrly_auto_tweets_custom_url_password,
			'preset_value'       => "",
			'description' => __( 'You don\'t need to change this unless you shared your Tweet url with a 3rd party. If you did and want to void that URL, make up a secret password that will be required in the URL in order for these tweets to be posted.', 'tweetrly_auto_tweets' ),
			'registration'=> 'tweetrly_auto_tweets_settings_general',
		)
	);
		
	//additional general settings
	do_action('tweetrly_auto_tweets_settings_additional_general_settings_hook');
}
add_action( 'admin_init', 'tweetrly_auto_tweets_settings_general_create' );
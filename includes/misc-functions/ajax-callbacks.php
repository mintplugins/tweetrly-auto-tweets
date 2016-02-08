<?php

/**
 * Post Tweet upon ajax callback
 *
 * @since    1.0.0
 * @link       http://mintplugins.com/doc/
 * @see      function_name()
 * @param  array $args See link for description.
 * @return   void
 */
function tweetrly_auto_tweets_ajax_tweet() {

	if ( !isset( $_POST['tweetrly_auto_tweets_tweet_num'] ) ) {
		echo 'no content';
		print_r( $content );
		die();
	}
	
	$transient_tweet_num = get_transient( 'tweetrly_auto_tweets_tweet_num' );
	
	$response = array(
		'action' => 'mp_post_tweet',
		'current_status' => __( 'Posting Tweets...', 'tweetrly_auto_tweets' ),
		'license' => sanitize_text_field( $_POST['license'] ),
		'tweeting_started' => sanitize_text_field( $_POST['tweeting_started'] ),
		'tweeting_complete' => get_transient( 'tweetrly_auto_tweets_tweeting_complete' ),
		'tweetrly_auto_tweets_tweet_num' => !empty( $transient_tweet_num ) ? intval( $transient_tweet_num ) : 0,
		'tweetrly_auto_tweets_last_tweet_posted_at' => sanitize_text_field( $_POST['tweetrly_auto_tweets_last_tweet_posted_at'] ),
		'tweetrly_auto_tweets_repost_todays_tweets' => sanitize_text_field( $_POST['tweetrly_auto_tweets_repost_todays_tweets'] ),
		'seconds_till_transients_expire' => sanitize_text_field( $_POST['seconds_till_transients_expire'] ),
		'weekday' => sanitize_text_field( $_POST['weekday'] )
	);
	
	//If the tweeting hasn't even started yet
	if ( $response['tweeting_started'] == 'no' ){
		
		$saved_license = get_option( 'tweetrly-auto-tweets_license_key' );
		
		//If we are checking the saved license
		if ( $response['license'] == 'checking' ){
			$license = $saved_license;
			if ( empty( $license ) ){
				$response['license'] = 'blank';
				echo json_encode( $response );
				die();
			}
		}
		else{
			//If there's no entered license and no saved license
			if ( ( $response['license'] == 'no' || $response['license'] == 'blank' ) && empty( $saved_license ) ){
				
				$response['license'] = 'blank';
				echo json_encode( $response );
				die();
				
			}
			elseif( $response['license'] != 'no' && $response['license'] != 'blank' ){
				$license = $response['license'];
			}
			else{
				$license = $saved_license;
			}
		}
		
		$args = array(
			'software_name'      => 'Tweetrly Auto Tweets',
			'software_api_url'   => 'http://mintplugins.com',
			'software_license_key'   => $license, 
			'software_store_license' => true, //Store this newly submitted license
		);
					
		if ( !mp_core_verify_license( $args ) ){
			
			$response['license'] = 'invalid';
			
			echo json_encode( $response );
			die();
		}
		else{
			$response['license'] = 'valid';
		}
		
	}
	
	$response['tweeting_started'] = true;
	
	//If we should be reposting today's tweets, reset all teh transients
	if ( $response['tweetrly_auto_tweets_repost_todays_tweets'] == 'repost_tweets' ){
		
		$response['current_status'] = __( 'RePosting Tweets...', 'tweetrly_auto_tweets' );
		$response['tweeting_started'] = true;
		$response['tweeting_complete'] = false;
		$response['tweetrly_auto_tweets_tweet_num'] = 0;
		$response['tweetrly_auto_tweets_repost_todays_tweets'] = false;
		
		set_transient( 'tweetrly_auto_tweets_tweeting_complete', false, $response['seconds_till_transients_expire'] );
		set_transient( 'tweetrly_auto_tweets_tweet_num', 0, $response['seconds_till_transients_expire'] );
					
	}
	
	//If the tweets have already been completed for today.
	if ( $response['tweeting_complete'] ){
		
		set_transient( 'tweetrly_auto_tweets_tweeting_complete', true, $response['seconds_till_transients_expire'] );
		
		$response['current_status'] = __( 'Tweeting Complete.' );
		$response['tweeting_complete'] = true;

		echo json_encode( $response );
		die();	
	
	}
	
	//Get the statuses we want to post
	$statuses = apply_filters( 'tweetrly_auto_tweets_statuses', array(), $response['weekday'] );

	//If there are no tweets set up to post
	if ( empty( $statuses[0] ) ) {
		
		$response['current_status'] = __( 'No Tweets entered for today.' );
		$response['tweeting_complete'] = true;
			
		echo json_encode( $response );
		die();
	}
		
	//If there is no more statuses to show
	if ( !isset( $statuses[$response['tweetrly_auto_tweets_tweet_num']] )) {
		
		set_transient( 'tweetrly_auto_tweets_tweeting_complete', true, $response['seconds_till_transients_expire'] );
		
		$response['current_status'] = __( 'Tweeting Complete.' );
		$response['tweeting_complete'] = true;

		echo json_encode( $response );
		die();
	}
	
	//The Status to post
	$status = trim( $statuses[$response['tweetrly_auto_tweets_tweet_num']] );
		
	//Include the Twitter Library
	require( TWEETRLY_AUTO_TWEETS_PLUGIN_DIR . 'includes/misc-functions/oauth/twitteroauth.php' );
	
	if ( !isset( $_POST['test_mode'] ) && true == false ){			
			
		$consumer_key = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_consumer_key' );
		$consumer_secret = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_consumer_secret' );
		$access_token = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_access_token' );
		$access_token_secret = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_twitter_access_token_secret' );
	
		// The TwitterOAuth instance
		$twitteroauth = new TwitterOAuth( $consumer_key, $consumer_secret, $access_token, $access_token_secret );
			
		$content = $twitteroauth->get("account/verify_credentials");
			
		//If we were not able to verify this user correctly
		if ( !isset( $content->screen_name ) ){
			
			$response['current_status'] = __( 'No Twitter User Verified' );
	
			echo json_encode( $response );
			die();
			
		}
		
		//Post the next tweet in the list
		$twitteroauth->post("statuses/update", array("status" => $status ) );	
	
	}
	
	//Increment the tweet num
	$response['tweetrly_auto_tweets_tweet_num'] = $response['tweetrly_auto_tweets_tweet_num'] + 1;
	set_transient( 'tweetrly_auto_tweets_tweet_num', $response['tweetrly_auto_tweets_tweet_num'], $response['seconds_till_transients_expire'] );
	
	$response['screen_name'] = isset( $content->screen_name ) ? $content->screen_name : __( 'No Screen Name Found', 'tweetrly_auto_tweets' );
	$response['last_tweet'] = $status;
	$response['tweeting_complete'] = false;
	
	//If we've just posted our last tweet
	if ( !isset( $statuses[$response['tweetrly_auto_tweets_tweet_num']] ) ){
		
		set_transient( 'tweetrly_auto_tweets_tweeting_complete', true, $response['seconds_till_transients_expire'] );
		
		$response['current_status'] = __( 'Tweeting Complete.' );
		$response['tweeting_complete'] = true;

		echo json_encode( $response );
		die();
		
	}
	
	//Return it to the js
	echo json_encode( $response );
	die();
	
}
add_filter( 'wp_ajax_mp_post_tweet', 'tweetrly_auto_tweets_ajax_tweet', 10, 2 );
add_filter( 'wp_ajax_nopriv_mp_post_tweet', 'tweetrly_auto_tweets_ajax_tweet', 10, 2 );
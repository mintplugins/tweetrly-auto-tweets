<?php

//List of tweets to post on Sundays
function tweetrly_auto_tweets_statuses( $statuses, $weekday ){
		
	$statuses = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_tweets_for_' . $weekday );
	
	$statuses_array = array_values( array_filter( preg_split("/\r\n|\n|\r/", $statuses) ) );
	
	return $statuses_array;
		
}
add_filter( 'tweetrly_auto_tweets_statuses', 'tweetrly_auto_tweets_statuses', 10, 2 );

function test_function(){
	
	if ( isset( $_GET['sljghsgs'] ) ){
		print_r( array_values( array_filter( tweetrly_auto_tweets_statuses( array(), 'monday' ) ) ) ); 
		die();
	}
	
}
add_action( 'init', 'test_function' );
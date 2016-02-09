<?php

/**
 * Output the js we need for the ajax tweet posting
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $args See link for description.
 * @return   void
 */
function tweetrly_auto_tweets_page(){
	
	$tweetrly_auto_tweets_custom_url_password = mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_custom_url_password' );
	
	if ( isset( $_GET['tweetrly_auto_tweets_post_tweets'] ) && isset( $_GET['tweetrly_auto_tweets_key'] ) && $_GET['tweetrly_auto_tweets_key'] == $tweetrly_auto_tweets_custom_url_password ){
	
		wp_enqueue_style( 'dashicons' );
		
		$time_delay = intval(mp_core_get_option( 'tweetrly_auto_tweets_settings_general',  'tweetrly_auto_tweets_time_delay' )) * 1000;
		if ( empty( $time_delay ) ){
			$time_delay = 1800000;
		}

		?>
        <!DOCTYPE html>
            <html <?php language_attributes(); ?>>
            <head>
            	<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.3/react.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.3/react-dom.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>
                
                <meta charset="<?php bloginfo( 'charset' ); ?>" />
                <title><?php echo __( 'Tweetrly Auto Tweets' ); ?></title>
                <link rel="profile" href="//gmpg.org/xfn/11" />
                <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
                <!--[if lt IE 9]>
                <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
                <![endif]-->
                
                <!-- Jquery From WordPress -->
				<script type='text/javascript' src='<?php bloginfo( 'wpurl' ); ?>/wp-includes/js/jquery/jquery.js'></script>                
                
				<style type="text/css">
					html{
						width:100%;
						height:100%;	
						background-color:#88c9f9;
					}
					body{
						width:100%;
						height:100%;
						display:table;
						margin:0px;
						background-color:#88c9f9;
						color:#FFFFFF;
						text-align:center;
						font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
						box-sizing:border-box;
					}
					#tweetrly_auto_tweets_notifications{
						padding:20px;
						display:table-cell;	
						vertical-align:middle;
						box-sizing:border-box;
					}
					.tweetrly-auto-tweets-notification{
						position:relative;
						width:100%;
						display:inline-block;
					}
					a{
						color: #0084B4;	
					}
					.button{
						background-color: #f5f8fa;
						background-image: linear-gradient(#fff,#f5f8fa);
						-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f5f8fa)";
						border: 1px solid #e1e8ed;
						border-radius: 4px;
						color: #66757f;
						cursor: pointer;
						display: inline-block;
						font-size: 14px;
						font-weight: bold;
						line-height: normal;
						padding: 8px 16px;
						position: relative;	
					}
					.NumberOfTweets{
						-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f5f8fa)";
						border: 1px solid #e1e8ed;
						border-radius: 4px;
						color: #FFFFFF;
						display: inline-block;
						font-size: 14px;
						line-height: normal;
						padding: 8px 16px;
						position: relative;
					}
					.LicenseKeyField{
						border: none;
						border-radius: 4px;
						display: inline-block;
						position: relative;
						padding: 10px;
						font-size: 12px;
						width:250px;
						text-align:center;
						border-top-right-radius:0px;
						border-bottom-right-radius:0px;
					}
					.LicenseKeyTitle{
						color:#fff;
						margin-bottom:17px;	
					}
					.LicenseKeyLink{
						color:#fff;
						margin-top:17px;	
						font-size:15px;
					}
					.LicenseKeySubmitBtn{
						background-color: #0084b4;
						border: 1px solid transparent;
						color: #fff;
						padding: 10px;
						text-align: center;	
						border-radius: 4px;
						border-top-left-radius:0px;
						border-bottom-left-radius:0px;
					}
					
					.TweetsPostedNumber{
						position: absolute;
						top: -7px;
						right: -7px;
						border-radius: 50px;
						background-color: #dc0d17;
						padding: 3px 5px;
						font-size: 10px;
						color: #fff;
						border-radius: 2px;
						box-shadow: 0 1px 1px rgba(0, 0, 0, .7);	
					}
					.CountDown{
						margin-top:10px;	
					}
				</style>
                
            </head>
            
            <body>
                    
              	<div id="tweetrly_auto_tweets_notifications"></div>
    
                <div style="display:none;">
                    <?php wp_footer(); ?>
                </div>
                
                <script type="text/babel">
				
					function tweetrly_auto_tweets_get_minute_of_day(){
						var d = new Date();
						return d.getMinutes();
					}
					
					function tweetrly_auto_tweets_get_week_day(){
					
						var d = new Date();
						var day_num = d.getDay();
						
						if ( day_num == 0 ){
							return 'sunday';	
						}
						
						if ( day_num == 1 ){
							return 'monday';	
						}
						
						if ( day_num == 2 ){
							return 'tuesday';	
						}
						
						if ( day_num == 3 ){
							return 'wednesday';	
						}
						
						if ( day_num == 4 ){
							return 'thursday';	
						}
						
						if ( day_num == 5 ){
							return 'friday';	
						}
						
						if ( day_num == 6 ){
							return 'saturday';	
						}
							
					}
					
					var MP_Post_Tweet_Notification = React.createClass({
													 						
						getInitialState: function() {
							
							this.times = {
								total_pause_length: 0,
								tweets_paused_at: 0,
								tweets_resumed_at: 0,
								time_left: 0,
								tweet_delay: <?php echo $time_delay; ?>
							};
							
							return {
								action_states: {
									tweeting_paused: false
								},
								
								animation_keyframe: {},
					
								data: {
									action: 'mp_post_tweet',
									current_status: 'loading...',
									tweeting_started: 'no',
									license: 'checking',
									tweeting_complete: false,
									tweetrly_auto_tweets_tweet_num: 0,
									tweetrly_auto_tweets_last_tweet_posted_at: this.get_current_time(),
									tweetrly_auto_tweets_repost_todays_tweets: false,
									seconds_till_transients_expire: this.seconds_till_transients_expire(),
									weekday: this.props.weekday
								}
							};
					  	},
						
						componentDidMount: function() {
							
							this.post_tweets();
							
						},
						
						seconds_till_transients_expire: function(){
							var mid= new Date(), 
							ts= mid.getTime();
							mid.setHours(24, 0, 0, 0);
							return Math.floor((mid - ts)/60000) * 60;
						},
						
						post_tweets: function(){
							
							var that = this;
							
							var data = that.state.data;
							
							//Set the time the transients will expire							
							data.seconds_till_transients_expire = that.seconds_till_transients_expire();
							data.weekday = this.props.weekday
								
							if ( !that.state.action_states.tweeting_paused ){
								jQuery.ajax({
									type: "POST",
									data: data,
									dataType:"json",
									url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
									success: function (data) {
										
										console.log( data );
										
										data.tweetrly_auto_tweets_last_tweet_posted_at = that.get_current_time();
						
										that.setState({data: data});
										
									}
								}).fail(function (data) {
									console.log(data);
								});	
							}
											
						},
							
						get_current_time: function(){
							var d = new Date();
							var n = d.getTime();
							return n;
						},
						
						get_paused_time: function(){
							if ( !this.times.tweets_resumed_at ){
								return 0;
							}
							else{
								return this.times.total_pause_length + ( this.times.tweets_resumed_at - this.times.tweets_paused_at );
							}
						},
						
						get_time_left: function(){
							
							if ( this.state.action_states.tweeting_paused ){
								return "Tweeting Paused";
							}
							else if( this.state.data.no_more_tweets ){
								return null;
							}
							
							var n = this.get_current_time();
							
							var last_tweet_time = this.state.data.tweetrly_auto_tweets_last_tweet_posted_at;
							var time_delay = <?php echo $time_delay; ?>;
							
							var paused_time = this.times.total_pause_length;
							
							var time_left = ( ( last_tweet_time + time_delay + paused_time ) - n );	
														
							if ( time_left < 1000 && !this.state.data.tweeting_complete ){
								this.times.total_pause_length = 0;
								this.post_tweets();	
							}
																					
							return time_left;
							
						},
						
						//Function which plays or pauses the tweets by setting the state accordingly	
						play_pause_tweets: function(){
							
							var current_state = this.state;
							
							//If the tweets are currently  paused
							if ( current_state.action_states.tweeting_paused ){
								//Resume the tweets
								current_state.action_states.tweeting_paused = false;	
								this.times.tweets_resumed_at = this.get_current_time();
								this.times.total_pause_length = this.times.total_pause_length + (this.times.tweets_resumed_at - this.times.tweets_paused_at);
							
							}
							//If the tweets are currently not paused
							else{
								
								//Pause the tweets
								current_state.action_states.tweeting_paused = true;	
								this.times.tweets_paused_at = this.get_current_time();
													
							}
														
							this.setState({action_states: current_state.action_states});
						},
						
						//This is fired when the repost button gets clicked
						repost_tweets_button_callback: function(){
							
							var current_state = this.state;
							
							current_state.data.tweetrly_auto_tweets_repost_todays_tweets = 'repost_tweets';
							
							this.setState({data: current_state.data});
							
							this.post_tweets();
							
						},
						
						onLicenseFormSubmit: function( event ){
							event.preventDefault();
							
							this.submit_animation();
							
							var license_key = event.target[0].value;
							
							this.state.data.license = license_key;	
														
							this.post_tweets();	
												
						},
						
						submit_animation: function(){
						
							var that = this;
							
							//Set first keyframe
							that.setState({ animation_keyframe: that.animation_keyframe_1() }); 
							
							//Set second keyframe
							var animation_keyframe_2 = setInterval( function(){
								that.setState({ animation_keyframe: that.animation_keyframe_2() }); 
								
								clearInterval( animation_keyframe_2 );
								
							}, 1000 );
							
						},
						
						animation_keyframe_1: function(){
								
							return {
								  opacity:0,
								  //color: '#000000',
								  
								  WebkitTransform: 'translate3d(0px, 0px, 0px)',
								  transform: 'translate3d(0px, 0px, 0px)',
									
								  WebkitTransition: 'all .50s linear', // note the capital 'W' here
								  msTransition: 'all .50s linear', // 'ms' is the only lowercase vendor prefix
								  transition: 'all .50s linear',
							} 
						},
						
						animation_keyframe_2: function(){
							
							return {
								  opacity:1,
								  //color: '#000000',
								  
								  WebkitTransform: 'translate3d(0px, 0px, 0px)',
								  transform: 'translate3d(0px, 0px, 0px)',
									
								  WebkitTransition: 'all .50s linear', // note the capital 'W' here
								  msTransition: 'all .50s linear', // 'ms' is the only lowercase vendor prefix
								  transition: 'all .50s linear',
							} 
							
								
						},
								
						render: function(){
							
							if ( this.state.data.license == 'checking' ){
								return (<CurrentStatus current_status={this.state.data.current_status} /> );
							}
							else if ( this.state.data.license != 'valid' ){
								
								return(
									<div className="tweetrly-auto-license-form" style={this.state.animation_keyframe}>
										
										<LicenseForm license_status={this.state.data.license} onSubmit={this.onLicenseFormSubmit} />
										
									</div>
								);
							}
							else{
								return(
									<div className="tweetrly-auto-tweets-notification" style={this.state.animation_keyframe}>
										
										<NumberOfTweets tweet_num={this.state.data.tweetrly_auto_tweets_tweet_num} />
										
										<CurrentStatus current_status={this.state.data.current_status} />
										
										<LastTweetPosted last_tweet={this.state.data.last_tweet} screen_name={this.state.data.screen_name} />
																																								
										<PauseTweetsButton click_function={this.play_pause_tweets} tweeting_complete={this.state.data.tweeting_complete} tweeting_paused={this.state.action_states.tweeting_paused} tweet_num={this.state.data.tweetrly_auto_tweets_tweet_num} />
										
										<RepostTweetsButton click_function={this.repost_tweets_button_callback} tweeting_complete={this.state.data.tweeting_complete} />
										
										<NextTweetCountDown countdown={ this.get_time_left() } tweeting_complete={this.state.data.tweeting_complete} tweet_num={this.state.data.tweetrly_auto_tweets_tweet_num} />
										
									</div>
								);
							}
								
						}
					});
					
					var LicenseForm = React.createClass({
						
						getInitialState: function(){
							
							return { 
								field_value: '' ,
								license_status: 'checking',
								old_license_status: ''
								
							};
							
						},
						
						componentWillReceiveProps: function(){
							//If we got a new status for the license since the last render
							if ( this.props.license_status != this.state.old_license_status ){
								this.setState({ license_status: this.props.license_status });
								this.setState({ old_license_status: this.props.license_status });
							}
						},
						
						handleChange: function(event) {
							this.setState({license_status: 'waitingforuserinput'});
							this.setState({field_value: event.target.value});
						},
												
						render: function(){
							
							if ( this.state.license_status == 'checking' ){
								return null;	
							}
							
							var value = '';
																					
							if ( this.state.license_status == 'invalid' ){							
								var placeholder = "<?php echo __( 'Invalid License. Please try again.', 'tweetrly_auto_tweets' ); ?>";
							}
							else{
								var placeholder = "<?php echo __( 'Enter your License Key', 'tweetrly_auto_tweets' ); ?>";
								value = this.state.field_value;
							}
																	
							return ( 
								<div className="LicenseFormContainer">
									<div className="LicenseKeyTitle"><?php echo __( 'Please enter your License Key to begin.', 'tweetrly_auto_tweets' ); ?></div>
									<form className="LicenseKeyFieldForm" onSubmit={ this.props.onSubmit } >
										<input className="LicenseKeyField" type="text" placeholder={placeholder} value={value} onChange={ this.handleChange } />
										<input className="LicenseKeySubmitBtn" type="submit" />
									</form>
									<div className="LicenseKeyLink"><?php echo __( 'Don\'t have one? get one at ', 'tweetrly_auto_tweets' ); ?>
									<a href="http://tweetrly.com/" target="_blank">www.tweetrly.com</a></div>
								</div>
							);
						}
						
					});
					
					var PauseTweetsButton = React.createClass({
						
						render: function(){
							
							var button_text_string = null;
							
							if ( !this.props.tweet_num || this.props.tweeting_complete ){
								return null;	
							}
							
							if ( this.props.tweeting_paused ){
								button_text_string = "<?php echo __( 'Continue Tweeting', 'tweetrly_auto_tweets' ); ?>";
							}
							else{
								button_text_string = "<?php echo __( 'Pause Tweeting', 'tweetrly_auto_tweets' ); ?>";
							}
							
							return (
							   <div className="tweetrly-auto-tweets-pause-btn-container">
							  	 <div className="tweetrly-auto-tweets-pause-btn button" onClick={this.props.click_function}>{button_text_string}</div>
							   </div>
							)
						}
					});
					
					var RepostTweetsButton = React.createClass({
						
						render: function(){
							
							var button_text_string = "<?php echo __( 'Repost today\'s tweets', 'tweetrly_auto_tweets' ); ?>";
							
							if ( this.props.tweeting_complete ){
							
								return (
								   <div className="tweetrly-auto-tweets-repost-btn-container">
									 <div className="tweetrly-auto-tweets-repost-btn button" onClick={this.props.click_function}>{button_text_string}</div>
								   </div>
								)
							}
							else{
								return null;	
							}
							
						}
					});
					
					var NumberOfTweets = React.createClass({
							
						render: function(){
						
							if ( this.props.tweet_num ){
								return (
									<div className="NumberOfTweets">
										<div className="TweetsPostedTitle">Tweets Posted</div>
										<div className="TweetsPostedNumber">{this.props.tweet_num}</div>
									</div>
								)
							}
							else{
								return( false )
							}
						}
					});
					
					var LastTweetPosted = React.createClass({
							
						render: function(){
						
							if ( this.props.last_tweet ){
								return (
									<div className="LastTweetPosted">
										<h4>Last tweet posted to {this.props.screen_name}:</h4>
										<h1>"{this.props.last_tweet}"</h1>
									</div>
								)
							}
							else{
								return( false )
							}
						}
					});
					
					var CurrentStatus = React.createClass({
							
						render: function(){
						
							if ( this.props.current_status ){
								return (
									<div className="CurrentStatus">
										<h1>{this.props.current_status}</h1>
									</div>
								)
							}
							else{
								return( false )
							}
						}
					});
					
					var NextTweetCountDown = React.createClass({
							
						render: function(){
						
							if ( this.props.countdown && !this.props.tweeting_complete && this.props.tweet_num){
								
								var countdown = Math.round( this.props.countdown/1000 );
								
								if ( isNaN( countdown ) ){
									var CountDownOutput = this.props.countdown;
								}
								else{
									var CountDownOutput = 'Next tweet will be posted in ' + countdown + ' seconds';
								}
								
								return (
									<div className="CountDown">
										<div className="CountdownTitle">{ CountDownOutput }</div>
									</div>
								)
	
							}
							else{
								return( false )
							}
						}
					});

					setInterval( function(){						
						ReactDOM.render( <MP_Post_Tweet_Notification key={tweetrly_auto_tweets_get_week_day()} weekday={tweetrly_auto_tweets_get_week_day()} />, document.getElementById( 'tweetrly_auto_tweets_notifications' ) );		
					}, 1000 );
					
														
				</script>	
		
            
            </body>
        </html>
    	
		<?php
		die();
    
	}
}
add_action( 'init', 'tweetrly_auto_tweets_page' );
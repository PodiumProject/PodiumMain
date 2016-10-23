<?php
    class widget_tweets extends WP_Widget {

        function widget_tweets() {
            $widget_ops = array( 'classname' => 'tweets', 'description' => 'Display Latest tweets' );
            parent::__construct( 'widget_touchsize_tweets' ,  __('Latest tweets','esquise') , $widget_ops );
        }

        function form($instance) {
            if( isset($instance['title']) ){
                $title = esc_attr($instance['title']);
            }else{
                $title = null;
            }

            if( isset($instance['number']) ){
                $number = esc_attr($instance['number']);
            }else{
                $number = 10;
            }

            if( isset($instance['username']) ){
                $username = esc_attr($instance['username']);
            }else{
                $username = null;
            }
            ////////////////////
            if( isset($instance['consumerKey']) ){
                $consumerKey = esc_attr($instance['consumerKey']);
            }else{
                $consumerKey = null;
            }

            if( isset($instance['consumerSecret']) ){
                $consumerSecret = esc_attr($instance['consumerSecret']);
            }else{
                $consumerSecret = null;
            }

            if( isset($instance['accessToken']) ){
                $accessToken = esc_attr($instance['accessToken']);
            }else{
                $accessToken = null;
            }
            if( isset($instance['accessTokenSecret']) ){
                $accessTokenSecret = esc_attr($instance['accessTokenSecret']);
            }else{
                $accessTokenSecret = null;
            }

            /////////////////////
            if( isset($instance['dynamic']) ){
                $dynamic = esc_attr( $instance['dynamic'] );
            }else{
                $dynamic = '';
            }
            if( isset($instance['followus']) ){
                $followus = esc_attr( $instance['followus'] );
            }else{
                $followus = '';
            }
        ?>
         <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' , 'esquise' ); ?>:</label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Twitter User Name' , 'esquise' ); ?>:</label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of latest tweets to show' , 'esquise' ); ?>:</label>
          <input id="<?php echo $this->get_field_id( 'number' ); ?>"  size="3" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'dynamic' ); ?>"><?php _e( 'Animated' , 'esquise' ); ?>:</label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'dynamic' ); ?>"  <?php checked( $dynamic , true ); ?>  name="<?php echo $this->get_field_name( 'dynamic' ); ?>"  value="1" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'followus' ); ?>"><?php _e( 'Show follow us' , 'esquise' ); ?>:</label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'followus' ); ?>"  <?php checked( $followus , true ); ?>  name="<?php echo $this->get_field_name( 'followus' ); ?>"  value="1" />
        </p>

        <p>
            <span class="hint"><?php echo sprintf(__( 'Now you need to do it to create an application in %s https://dev.twitter.com/apps %s and fill the requirements there. Once finished you will have your consumer key, consumer secret, access token and access token secret.' , 'esquise' ), '<a href="https://dev.twitter.com/apps">', '</a>' ); ?></span>

        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'consumerKey' ); ?>"><?php _e( 'Consumer key' , 'esquise' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'consumerKey' ); ?>"   name="<?php echo $this->get_field_name('consumerKey'); ?>" type="text" value="<?php echo $consumerKey; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'consumerSecret' ); ?>"><?php _e( 'Consumer secret' , 'esquise' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'consumerSecret' ); ?>"   name="<?php echo $this->get_field_name('consumerSecret'); ?>" type="text" value="<?php echo $consumerSecret; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'accessToken' ); ?>"><?php _e( 'Access token' , 'esquise' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'accessToken' ); ?>"   name="<?php echo $this->get_field_name('accessToken'); ?>" type="text" value="<?php echo $accessToken; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'accessTokenSecret' ); ?>"><?php _e( 'Access token secret' , 'esquise' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'accessTokenSecret' ); ?>"   name="<?php echo $this->get_field_name('accessTokenSecret'); ?>" type="text" value="<?php echo $accessTokenSecret; ?>" />
        </p>

        <?php
        }

        function update( $new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']      = strip_tags($new_instance['title']);
            $instance['number']     = strip_tags($new_instance['number']);
            $instance['username']   = strip_tags($new_instance['username']);
            $instance['dynamic']   = strip_tags($new_instance['dynamic']);
            $instance['followus']   = strip_tags($new_instance['followus']);


            $instance['consumerKey']   = strip_tags($new_instance['consumerKey']);
            $instance['consumerSecret']   = strip_tags($new_instance['consumerSecret']);
            $instance['accessToken']   = strip_tags($new_instance['accessToken']);
            $instance['accessTokenSecret']   = strip_tags($new_instance['accessTokenSecret']);
            return $instance;
        }

        function widget( $args, $instance ) {
            $title                     = apply_filters( 'widget_title', $instance['title'] );
            $username                  = trim($instance['username']);
            $limit                     = (isset($instance['number']) && is_numeric($instance['number'])) ? $instance['number'] : 5;
            $oauth_access_token        = trim($instance['accessToken']);
            $oauth_access_token_secret = trim($instance['accessTokenSecret']);
            $consumer_key              = trim($instance['consumerKey']);
            $consumer_secret           = trim($instance['consumerSecret']);
            $dynamic                   = (isset($instance['dynamic']) && absint($instance['dynamic']) == 1) ? 'dynamic' : 'static';
            $followus                  = (isset($instance['followus']) && absint($instance['followus']) == 1) ? 'yes' : 'no';

            echo $args['before_widget'];

            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            // Get the tweets.
            $timelines = $this->twitter_timeline( $username, $limit, $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret );

            if ( isset($timelines) && is_array($timelines) && !empty($timelines) ) {

                // Add links to URL and username mention in tweets.
                $patterns = array( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '/@([A-Za-z0-9_]{1,15})/' );
                $replace = array( '<a href="$1">$1</a>', '<a href="http://twitter.com/$1">@$1</a>' );

                $followus_html = '';
                if($followus != 'no'){
                    $followus_html =    '<div class="twitter-follow">
                                            <i class="icon-twitter"></i>
                                            <a href="http://twitter.com/' . $username . '" target="_blank">
                                                ' . __('Follow us', 'esquise') . ' <b>@' . $username . '</b>
                                            </a>
                                        </div>';

                }

                $html = '<div class="ts-twitter-container ' . $dynamic . '">
                            <div class="touchsize_twitter">
                                <div class="slides_container">
                                    <ul class="widget-items">';

                foreach ( $timelines as $timeline ) {
                    $result = preg_replace($patterns, $replace, $timeline->text);

                    $html .=            '<li class="tweet-entry">
                                            <div class="tweet-data">
                                                <i class="icon-twitter"></i>
                                                ' . $timeline->user->name . ': ' . $result . '
                                            </div>
                                            <span class="tweet-date date st">' . $this->tweet_time($timeline->created_at) . '</span>
                                        </li>';

                }
                $html .=            '</ul>
                                </div>
                            </div>
                            ' . $followus_html . '
                        </div>';

                echo $html;

            } else {
                _e( 'Error fetching feeds. Please verify the Twitter settings in the widget.', 'esquise' );
            }

            echo $args['after_widget'];
        }

        function twitter_timeline( $username, $limit, $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret ) {

            if ( false === ( $timeline = get_transient( 'ts-twitter-' . sanitize_title_with_dashes( $username ) ) ) ) {

                require_once 'TwitterAPIExchange.php';

                /** Set access tokens here - see: https://dev.twitter.com/apps/ */
                $settings = array(
                    'oauth_access_token'        => $oauth_access_token,
                    'oauth_access_token_secret' => $oauth_access_token_secret,
                    'consumer_key'              => $consumer_key,
                    'consumer_secret'           => $consumer_secret
                );

                $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
                $getfield = '?screen_name=' . $username . '&count=' . $limit;
                $request_method = 'GET';

                $twitter_instance = new TwitterAPIExchange( $settings );

                $query = $twitter_instance
                    ->setGetfield( $getfield )
                    ->buildOauth( $url, $request_method )
                    ->performRequest();

                $timeline = json_decode( $query );

                // do not set an empty transient - should help catch private or empty accounts
                if ( ! empty( $timeline ) ) {

                    set_transient( 'ts-twitter-' . sanitize_title_with_dashes( $username ), $timeline, MINUTE_IN_SECONDS * 20 );
                }

            }

            return $timeline;
        }

        function tweet_time( $time ) {
            // Get current timestamp.
            $now = strtotime( 'now' );

            // Get timestamp when tweet created.
            $created = strtotime( $time );

            // Get difference.
            $difference = $now - $created;

            // Calculate different time values.
            $minute = 60;
            $hour = $minute * 60;
            $day = $hour * 24;
            $week = $day * 7;

            if ( is_numeric( $difference ) && $difference > 0 ) {

                // If less than 3 seconds.
                if ( $difference < 3 ) {
                    return __( 'right now', 'esquise' );
                }

                // If less than minute.
                if ( $difference < $minute ) {
                    return floor( $difference ) . ' ' . __( 'seconds ago', 'esquise' );;
                }

                // If less than 2 minutes.
                if ( $difference < $minute * 2 ) {
                    return __( 'about 1 minute ago', 'esquise' );
                }

                // If less than hour.
                if ( $difference < $hour ) {
                    return floor( $difference / $minute ) . ' ' . __( 'minutes ago', 'esquise' );
                }

                // If less than 2 hours.
                if ( $difference < $hour * 2 ) {
                    return __( 'about 1 hour ago', 'esquise' );
                }

                // If less than day.
                if ( $difference < $day ) {
                    return floor( $difference / $hour ) . ' ' . __( 'hours ago', 'esquise' );
                }

                // If more than day, but less than 2 days.
                if ( $difference > $day && $difference < $day * 2 ) {
                    return __( 'yesterday', 'esquise' );;
                }

                // If less than year.
                if ( $difference < $day * 365 ) {
                    return floor( $difference / $day ) . ' ' . __( 'days ago', 'esquise' );
                }

                // Else return more than a year.
                return __( 'over a year ago', 'esquise' );
            }
        }

    }



  function register_twitter_widget() {
      register_widget( 'widget_tweets' );
  }
  add_action( 'widgets_init', 'register_twitter_widget' );

?>
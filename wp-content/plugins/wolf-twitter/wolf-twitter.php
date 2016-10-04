<?php
/**
 * Plugin Name: Wolf Twitter
 * Plugin URI: http://wpwolf.com/plugin/wolf-twitter
 * Description: A widget and a shortcode to display your Twitter Feed
 * Version: 2.0.6.1
 * Author: WpWolf
 * Author URI: http://wpwolf.com
 * Requires at least: 3.5
 * Tested up to: 4.0
 *
 * Text Domain: wolf
 * Domain Path: /lang/
 *
 * @package WolfTwitter
 * @author WpWolf
 *
 * Being a free product, this plugin is distributed as-is without official support. 
 * Verified customers however, who have purchased a premium theme
 * at http://themeforest.net/user/BrutalDesign/portfolio?ref=BrutalDesign
 * will have access to support for this plugin in the forums
 * http://help.wpwolf.com/
 *
 * Copyright (C) 2014 Constantin Saguin
 * This WordPress Plugin is a free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * It is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * See http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Wolf_Twitter' ) ) {

class Wolf_Twitter {

	var $update_url = 'http://plugins.wpwolf.com/update';
	var $cache_duration_hour = 2; // cache duration in hour (can be decimal e.g : 1.5)

	public function __construct() {

		define( 'WOLF_TWITTER_URL', plugins_url( '/' . basename( dirname( __FILE__ ) ) ) );
		define( 'WOLF_TWITTER_DIR', dirname( __FILE__ ) );

		// Require widget script
		include_once WOLF_TWITTER_DIR . '/wolf-twitter-widget.php';

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );
		
		// shortcode
		add_shortcode( 'wolf_tweet', array( $this, 'shortcode') );
		
		// styles
		add_action( 'wp_enqueue_scripts', array( $this, 'print_styles' ) );

		add_action( 'admin_init', array( $this, 'plugin_update' ) );
	}

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {

		$domain = 'wolf';
		$locale = apply_filters( 'wolf', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	}

	/**
	 * Print twitter styles
	 */
	public function print_styles() {
		wp_register_style( 'wolf-twitter', WOLF_TWITTER_URL . '/css/twitter.css', array(), '2.0.6.1', 'all' );
		wp_enqueue_style( 'wolf-twitter' );
	}

	/**
	 * Display an error
	 */
	public function twitter_error( $username, $list = false ) {

		$error_message = sprintf( __( 'Our Twitter feed is currently unavailable but you can visit our official twitter page  <a href="%1s" target="_blank">%2s</a>.', 'wolf' ), "http://twitter.com/$username", "@$username"  );
			
		if ( $list ) {

			$output = "<ul class=\"wolf-tweet-list\"><li>$error_message</li></ul>";

		} else {

			$output = "<div class=\"wolf-bigtweet-content\"><span class=\"wolf-tweet-text\" style=\"font-size:14px\">$error_message</span></div>";

		}


		return $output;

	}

	/**
	 * Get the Twitter feed 
	 */
	public function get_twitter_feed( $username ) {

		$trans_key = 'wolf_twitter_'.$username;
		// 
		$url = "http://twitter.wpwolf.com/username/$username";
		$cache_duration = ceil( $this->cache_duration_hour*3600 );
		if ( $cache_duration < 3600 )
			$cache_duration = 3600;

		if ( false === ( $cached_data = get_transient( $trans_key ) ) ) {

			if ( function_exists( 'curl_init') ) {
				$c = curl_init( $url);
				curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $c, CURLOPT_HEADER, 0 );
				curl_setopt( $c, CURLOPT_TIMEOUT, 10 );
				$data = curl_exec( $c );
				curl_close( $c );
			} else {
				$data = file_get_contents( $url );
			}
			
			if ( $data ) {
				set_transient( $trans_key, $data, $cache_duration ); 
			}
		} else {

			$data = $cached_data;
		}
			
		return  json_decode( $data );
	} 
  
	/**
	 * Display tweets as list or single tweet 
	 */
	public function twitter( $username, $count = 3, $list = true ) {
		
		$tweet ='';
		$data = $this->get_twitter_feed( $username );

		if ( $data && is_array( $data ) ) {
			/* Display as list */
			if ( $list) {
				if ( isset( $data[0] ) ) {
					$tweet .= "<ul class=\"wolf-tweet-list\">"; 
					for ( $i=0; $i<$count; $i++ ) {
						if ( isset( $data[$i] ) ) {
							$content = $data[$i]->text;
							$created = $data[$i]->created_at;
							$id = $data[$i]->id_str;
							$tweet_link = "https://twitter.com/$username/statuses/$id";
							
							$tweet .= "<li>";
							$tweet .= "<span class=\"wolf-tweet-time\"><a href=\"$tweet_link\" target=\"_blank\">".__( 'about', 'wolf' )." ". $this->twitter_time_ago( $created )."</a></span>";    
							$tweet .= "<span class=\"wolf-tweet-text\">".$this->twitter_to_link( $content )."</span>";
							$tweet .= "</li>";
						}
					}
					$tweet .= "</ul>";
				} else {
					$tweet = $this->twitter_error( $username, $list );
				}
			
			/* Display as single tweet */
			} else {
				if ( isset( $data[0] ) ) {
					$content = $data[0]->text;
					$created = $data[0]->created_at;
					$id = $data[0]->id_str;
					$tweet_link = "https://twitter.com/$username/statuses/$id";

					$tweet .= "<div class=\"wolf-bigtweet-content\"><span class=\"wolf-tweet-text\">". $this->twitter_to_link( $content )."</span>";
					$tweet .= "<br><span class=\"wolf-tweet-time_big\"><a href=\"$tweet_link\" target=\"_blank\">".__( 'about', 'wolf' )." ". $this->twitter_time_ago( $created )."</a> 
					<span class=\"wolf-tweet-separator\">|</span> <a href=\"http://twitter.com/$username/\" target=\"_blank\">@$username</a></span></div>"; 
				} else {
					$tweet = $this->twitter_error( $username, $list );
				}
			}

		} else {
			$tweet = $this->twitter_error( $username, $list );
		}
		
		return $tweet;
	}

	/**
	 * Find url strings, tags and username strings and make them as link
	 */
	public function  twitter_to_link( $text ) {
		
		// Match URLs
		$text = preg_replace( '/(^|[^=\"\/])\b((?:\w+:\/\/|www\.)[^\s<]+)((?:\W+|\b)(?:[\s<]|$))/m', '<a href="$0" target="_blank">$0</a>', $text);

		// Match @name
		$text = preg_replace( '/(@)([a-zA-ZÀ-ú0-9\_]+)/', '<a href="https://twitter.com/$2" target="_blank">@$2</a>', $text);

		// Match #hashtag
		$text = preg_replace( '/(#)([a-zA-ZÀ-ú0-9\_]+)/', '<a href="https://twitter.com/search/?q=$2" target="_blank">#$2</a>', $text);
		
		return $text;
	}

	/**
	 * Convert the twitter date to "X ago" type
	 */        
	public function twitter_time_ago( $date ) {
		return esc_html(
			sprintf(
				__( '%s ago', 'wolf' ), human_time_diff( strtotime( $date ), current_time( 'timestamp' ) )
			)
		);
	} 

	/**
	 * Shortcode
	 */ 
	public function shortcode( $atts ) {

		extract( shortcode_atts( array(
			'username' => '',
			'type' => 'single',
			'count' => 1
		), $atts ) );

		$list = ( 'list' == $type ) ? true : false;

		return $this->twitter( $username, $count, $list );	
	}

	/**
	 * Plugin update
	 */
	public function plugin_update() {
		
		$plugin_data = get_plugin_data( __FILE__ );

		$current_version = $plugin_data['Version'];
		$plugin_slug = plugin_basename( dirname( __FILE__ ) );
		$plugin_path = plugin_basename( __FILE__ );
		$remote_path = $this->update_url . '/' . $plugin_slug;
		
		if ( ! class_exists( 'Wolf_WP_Update' ) )
			include_once( 'class/class-wp-update.php');
		
		new Wolf_WP_Update( $current_version, $remote_path, $plugin_path );
	}

} // end class

global $wolf_twitter;
$wolf_twitter = new Wolf_Twitter;

// Widget function
function wolf_twitter_widget( $username, $count ) {
	global $wolf_twitter;
	echo $wolf_twitter->twitter( $username, $count , true );
}

} // end class check
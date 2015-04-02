<?php
/**
* Plugin Name: CAP Social Highlighter
* Description: Select content and tweet it out
* Version: 1.0
* Author: Seth Rubenstein for Center for American Progress
* Author URI: http://sethrubenstein.info
* License: GPL2
*/
function cap_tweet_highlight_script() {
    // // Only call cap-tweet-highlight if a twitter username is set and were on a single post page.
    if ( is_singular() ) {
        $handle = 'jquery';
        $list = 'enqueued';
        if (!wp_script_is( $handle, $list )) {
            wp_enqueue_script( 'jquery' );
        }
        wp_enqueue_style( 'cap-social-sharer', plugin_dir_url(__FILE__).'css/social-sharer.css');

        wp_register_script( 'cap-social-sharer', plugin_dir_url(__FILE__).'js/min/cap-social-sharer.min.js');
        wp_enqueue_script( 'cap-social-sharer' );
    }
}
add_action( 'wp_enqueue_scripts', 'cap_tweet_highlight_script' );

function cap_selection_share_callback() {
    if ( is_singular() ) {
        ?>
        <span class="share-selection"><i class="share-twitter"></i> <i class="share-facebook"></i></span>
        <?php
    }
}
add_action('wp_footer','cap_selection_share_callback', 500);

function cap_tweet_highlight_shortcode( $atts , $content = null ) {
    // Use shortcode [tweet-text]Your text here[/tweet-text]
    $markup = '<span class="shareable-text">';
    $markup .= $content;
    $markup .= '</span>';
    return $markup;
}
add_shortcode( 'tweet-text', 'cap_tweet_highlight_shortcode' );

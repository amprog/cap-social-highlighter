<?php
/**
* Plugin Name: CAP Social Highlighter
* Description: Select content and tweet it out
* Version: 1.0
* Author: Seth Rubenstein for Center for American Progress
* Author URI: http://sethrubenstein.info
* License: GPL2
*/
function cap_shareable_scripts_styles() {
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
add_action( 'wp_enqueue_scripts', 'cap_shareable_scripts_styles' );

function cap_shareable_callback() {
    if ( is_singular(array('post', 'reports', 'press')) ) {
        ?>
        <span class="share-selection"><i class="share-twitter"></i> <i class="share-facebook" style="display: none;"></i></span>
        <script>
            jQuery(document).ready(function(){
                <?php if (has_filter('cap_tweet_highlight_class') && has_filter('cap_tweet_highlight_class_for_lists')) {
                echo 'jQuery("'.apply_filters('cap_tweet_highlight_class', "").'").selectionSharer();';
                //make list items in content shareable
                echo 'jQuery("'.apply_filters('cap_tweet_highlight_class_for_lists', "").'").selectionSharer();';
                } else {
                    // Default to .entry-content p which if we're creating the theme should be mostly everything.
                    echo 'jQuery("body:not(.lasso-editing) .entry-content section > p:not(.wide-paragraph), body:not(.lasso-editing) .entry-content section > .aesop-content-component p").selectionSharer();';
                }
                ?>
            });
        </script>
        <?php
    }
}
add_action('wp_footer','cap_shareable_callback', 500);

function cap_shareable_shortcode( $atts , $content = null ) {
    // Use shortcode [shareable]Your text here[/shareable]
    $markup = '<span class="shareable-text">';
    $markup .= $content;
    $markup .= '</span>';
    return $markup;
}
add_shortcode( 'shareable', 'cap_shareable_shortcode' );

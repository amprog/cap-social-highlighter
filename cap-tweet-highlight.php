<?php
/**
* Plugin Name: CAP Tweet Highlighter
* Description: Select content and tweet it out
* Version: 1.0
* Author: Seth Rubenstein for Center for American Progress
* Author URI: http://sethrubenstein.info
* License: GPL2
*/
if( function_exists('register_field_group') ) {
    register_field_group(array (
        'key' => 'group_54b6b016f34a1',
        'title' => 'CAP Tweet Highlighter',
        'fields' => array (
            array (
                'key' => 'field_54b6b020fadc5',
                'label' => 'Twitter Username',
                'name' => 'cap_tweet_highlight_username',
                'prefix' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'readonly' => 0,
                'disabled' => 0,
            ),
        ),
        'location' => array (
            array (
                array (
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'acf-options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
    ));
}
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}

function cap_tweet_highlight_script() {
    if (get_option('options_cap_tweet_highlight_username')) {
        $handle = 'jquery';
        $list = 'enqueued';
        if (wp_script_is( $handle, $list )) {
            return;
        } else {
            wp_enqueue_script( 'jquery' );
            wp_register_script( 'cap-tweet-highlight', plugin_dir_url(__FILE__).'tweet-highlighter.js');
            wp_enqueue_script( 'cap-tweet-highlight' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'cap_tweet_highlight_script' );

function cap_tweet_highlight_callback() {
    // Get site twitter name through options table
    $twitter_username = get_option('options_cap_tweet_highlight_username');
    if (!empty($twitter_username)) {
        echo '
        <script>
        jQuery(document).ready(function(){
            jQuery("p, .tweetable").tweettext("'.$twitter_username.'");
        });
        </script>
        ';
    }
}
add_action('wp_head','cap_tweet_highlight_callback');

function cap_tweet_highlight_shortcode( $atts , $content = null ) {
    // Use shortcode [tweet-text]Your text here[/tweet-text]
    $markup = '<span class="tweetable">';
    $markup .= $content;
    $markup .= '</span>';
    return $markup;
}
add_shortcode( 'tweet-text', 'cap_tweet_highlight_shortcode' );

function cap_tweet_highlight_style() {
    if (get_option('options_cap_tweet_highlight_username')){
    ?>
    <style>
    ::selection {
        background-color: rgba(0, 172, 237, 0.7);
        color: #fff;
    }
    /*Twitter Icon*/
    #tweettext, .tweetable:hover:after {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAMAAABFjsb+AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAADPUExURQAAAP///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6iInkcAAABEdFJOUwCrQN3DyOERBQSEjMrCAq02omMKD1eFqUf2A/S6/SRpkEtGmPdd1M3H6vlehxSaMf4WNOvQC1MuiCMnVQcr5K/8P8W1IZj2+gAAAJVJREFUGBmdwUUCglAABNBBREDA7u7u7pz7n0ngG7hx4Xv4Q6RSb+rQhggZGoRUkaR/0Fax43kJR4auntHHkZR9sKUpKCr0E8lOuZQrUFCBw/5CRzZPVwPANjCnlwwgtOCXKGzjLr2ScNQUfgQ0OIL0iMGljyZ8CeNlbVHwJyBMVxYFKQ6Xz+RTq4q3a/Bu3qTNDL88AM/nLQA4tvI4AAAAAElFTkSuQmCC');
    }
    #tweettext:hover, .tweetable:after {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAMAAABFjsb+AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAADPUExURQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAJljpgQAAABEdFJOUwCr3UDDyOERBQSEjMrCAgoPV602omMkhalH9gP0uv1pkEtGmPdd1BTNx+r5XoeaMf4WNOvQCy5TiK/8IydVByvkP8W12KLvBQAAAJVJREFUGBmdwUUCglAABNBBREDA7u7u7pz7n0ngG7hx4Xv4Q7xSb+rQ2ggZGoR0iaR/2FFx4HYJR5auvjHAmfTJsGUoKCr0C8luOZUvUlCB03FHR65AVwPAPjCnlw9AaMEvCdjGPXpF4agp/AhocATpkYRLH034EsPLyqLgj0CYri0KUhgu2eRTq4q3a/Bu3qTNDL88ANGwLQCiOPGPAAAAAElFTkSuQmCC');
    }
    /*Icon Size*/
    #tweettext, .tweetable:after {
        background-position: center;
        background-size: 16px;
        height: 16px;
        width: 16px;
    }
    /*Selection Tweet Icon*/
    #tweettext {
        background-color: rgba(0, 172, 237, 1.0);
        border: 8px solid rgba(0, 172, 237, 1.0);
        border-radius: 19px;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }
    /*Pre Defined Tweet Text*/
    .tweetable {
        background-color: #eaeaea;
        padding: 1px 0 1px 3px;
        text-decoration: none;
        cursor: pointer;
    }
    .tweetable:after {
        content:"";
        display: inline-block;
        vertical-align: top;
        margin-top: 3px;
        margin-bottom: 3px;
        margin-left: 5px;
        margin-right: 3px;
    }
    .tweetable:hover {
        background-color: rgba(0, 172, 237, 0.7);
        color: #fff!important;
    }

    </style>
    <?php }
}
add_action('wp_head','cap_tweet_highlight_style');

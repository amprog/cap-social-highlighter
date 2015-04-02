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
        <span id="share-selection"><i class="mdi mdi-twitter"></i> <i class="mdi mdi-facebook"></i></span>
        <script>
        jQuery(document).ready(function(){
            appId = jQuery('meta[property="fb:app_id"]').attr("content") || jQuery('meta[property="fb:app_id"]').attr("value");
            url2share = jQuery('meta[property="og:url"]').attr("content") || jQuery('meta[property="og:url"]').attr("value") || window.location.href;

            smart_truncate = function(str, n){
                if (!str || !str.length) return str;
                var toLong = str.length>n,
                    s_ = toLong ? str.substr(0,n-1) : str;
                s_ = toLong ? s_.substr(0,s_.lastIndexOf(' ')) : s_;
                return  toLong ? s_ +'...' : s_;
            };

            jQuery(".entry-content p:not(.wide-paragraph)").selectionSharer();

            jQuery("#share-selection").appendTo("blockquote p, .shareable-text");
            jQuery("blockquote p, .shareable-text").each(function(){

                jQuery(this).find(".mdi-twitter").click(function() {

                    var text = "“"+smart_truncate(jQuery(this).closest("p").text(), 114)+"”";
                    var url = 'http://twitter.com/intent/tweet?text='+encodeURIComponent(text)+'&related='+self.relatedTwitterAccounts+'&url='+encodeURIComponent(window.location.href);

                    var w = 640, h=440;
                    var left = (screen.width/2)-(w/2);
                    var top = (screen.height/2)-(h/2)-100;
                    window.open(url, "share_twitter", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                });

                jQuery(this).find(".mdi-facebook").click(function() {

                    var text = jQuery(this).closest("p").text();
                    var url = 'https://www.facebook.com/dialog/feed?app_id='+appId+'&display=page&name='+encodeURIComponent(text)+'&link='+encodeURIComponent(url2share)+'&redirect_uri='+encodeURIComponent(url2share);

                    var w = 640, h=440;
                    var left = (screen.width/2)-(w/2);
                    var top = (screen.height/2)-(h/2)-100;
                    window.open(url, "share_facebook", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                });

            });

            jQuery("#lasso--edit").click(function(){
                jQuery(".entry-content p").each(function(){
                    if ( jQuery(this).hasClass( "selectionShareable" ) ) {
                        jQuery(this).removeAttr("class");
                    }
                });
            });
        });
        </script>
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

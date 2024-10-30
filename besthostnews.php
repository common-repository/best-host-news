<?php
/*
Plugin Name: BestHostNews
Plugin URI: https://www.thewebmaster.com
Description: Display the RSS feed from Besthostnews.com
Version: 1.7
Author: Jonathan Griffin
Author URI: https://www.thewebmaster.com
License: GPLv2 or later
*/

function besthostnews_add_dashboard_widgets() {
	wp_add_dashboard_widget(
                 'besthostnews_dashboard_widget',
                 'BestHostNews',
                 'besthostnews_dashboard_widget_display'
        );
}
function dashboard_widget_function() {
	echo '<h3><a href="https://www.thewebmaster.com">Thewebmaster</a> is a Web Hosting news and reviews website dedicated to bringing all the latest hosting consumer news.  Please check out our latest posts below:</h3>';
     $rss = fetch_feed( "https://www.thewebmaster.com/feed/" );

     if ( is_wp_error($rss) ) {
          if ( is_admin() || current_user_can('manage_options') ) {
               echo '<p>';
               printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
               echo '</p>';
          }
     return;
}

if ( !$rss->get_item_quantity() ) {
     echo '<p>Apparently, there are no updates to show!</p>';
     $rss->__destruct();
     unset($rss);
     return;
}

echo "<ul>\n";

if ( !isset($items) )
     $items = 5;

     foreach ( $rss->get_items(0, $items) as $item ) {
          $publisher = '';
          $site_link = '';
          $link = '';
          $content = '';
          $date = '';
          $link = esc_url( strip_tags( $item->get_link() ) );
          $title = esc_html( $item->get_title() );
          $content = $item->get_content();
          $content = wp_html_excerpt($content, 250) . ' ...';

         echo "<li><a class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content</div>\n";
}

echo "</ul>\n";
$rss->__destruct();
unset($rss);

}

function add_dashboard_widget() {
     wp_add_dashboard_widget('besthostnews_dashboard_widget', 'Recent Posts from www.besthostnews.com', 'dashboard_widget_function');
}

add_action('wp_dashboard_setup', 'add_dashboard_widget');

?>

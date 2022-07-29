<?php
global $post;

echo "<!-- Talk Talk OpenGraph Script start -->";
printf( "<meta property='og:title' content='%s' />",  esc_attr($post->post_title) );
printf( "<meta property='og:description' content='%s' />", esc_attr($post->post_excerpt) );
printf( "<meta property='og:image' content='%s' />", wp_get_attachment_url( get_post_thumbnail_id() ) );
echo "<!-- Talk Talk OpenGraph Script end -->";
?>
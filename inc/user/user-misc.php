<?php
add_action( 'wp', function () {
    show_admin_bar( false);
});

add_action('wp_logout', function(){
    wp_safe_redirect("https://elmirador.dk/log-ind/");
    exit;
});

add_action('wp_head', function(){
    global $post;
    if (get_the_ID() == 17346 && !is_user_logged_in()) {
        wp_safe_redirect("https://elmirador.dk/log-ind/");
        exit;
    }
    if(get_the_ID() == 17346 && count(get_user_meta(get_current_user_id(), 'boliger', true)) < 1){
        wp_safe_redirect("https://elmirador.dk/");
        exit;
    }
    if(($post->post_parent == 5819 || get_the_ID() == 5819 || get_the_ID() == 17346) && !is_user_logged_in()){
        wp_safe_redirect("https://elmirador.dk/log-ind/");
        exit;
    }
});
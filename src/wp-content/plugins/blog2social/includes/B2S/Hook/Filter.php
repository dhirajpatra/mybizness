<?php

class B2S_Hook_Filter{
    
    function get_wp_user_post_author_display_name($wp_post_author_id = 0) {
        $user_data = get_userdata($wp_post_author_id);
        if($user_data != false && !empty($user_data->display_name)) {
            $wp_display_name = apply_filters('b2s_filter_wp_user_post_author_display_name', $user_data->display_name, $wp_post_author_id);
            return $wp_display_name;
        }
        return '';
    }
    
}

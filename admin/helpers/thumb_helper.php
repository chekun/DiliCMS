<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

if (!function_exists('create_thumb_preferences')) {

    function create_thumb_preferences(& $data)
    {
        if ($data['hasattach']) {
            $thumb_preferences = array();
            $thumb_preferences['enabled'] = array();
            $thumb_preferences['default'] = 'original';
            $thumbnails = CI()->input->post('thumbnail', TRUE);
            if (is_array($thumbnails) and count($thumbnails) > 0) {
                foreach ($thumbnails as $thumbnail => $_e) {
                    $thumb_preferences['enabled'][] = (String) $thumbnail;
                }
                $default = (String) CI()->input->post('thumb_default');
                if ($default and in_array($default, $thumb_preferences['enabled'])) {
                    $thumb_preferences['default'] = CI()->input->post('thumb_default', true);
                }
            }
            $data['thumb_preferences'] = json_encode($thumb_preferences);
        }
    }

}
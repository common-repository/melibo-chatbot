<?php

class MeliboAjaxFunctions {

    public static function getAllPages() {
        $returnPageArray = array();

        $search_results = new WP_Query( array( 
            's'=> $_GET['q'], // the search query
            'post_type' => 'page',
            'post_status' => 'publish', // if you don't want drafts to be returned
            'posts_per_page' => 50 // how much to show at once
        ) );

        if($search_results->have_posts()) {
            while($search_results->have_posts()) {
                $search_results->the_post();
                $returnPageArray[] = array(
                    'ID' => $search_results->post->ID,
                    'post_author' => $search_results->post->post_author,
                    'post_date' => $search_results->post->post_date,
                    'post_date_gmt' => $search_results->post->post_date_gmt,
                    'post_title' => $search_results->post->post_title,
                    'post_excerpt' => $search_results->post->post_excerpt,
                    'post_status' => $search_results->post->post_status,
                    'post_name' => $search_results->post->post_name,
                    'post_modified' => $search_results->post->post_modified,
                    'post_modified_gmt' => $search_results->post->post_modified_gmt,
                    'post_parent' => $search_results->post->post_parent,
                    'guid' => $search_results->post->guid,
                    'menu_order' => $search_results->post->menu_order,
                    'post_type' => $search_results->post->post_type,
                );
            }
        }

        echo json_encode($returnPageArray);
        die;
    }
}
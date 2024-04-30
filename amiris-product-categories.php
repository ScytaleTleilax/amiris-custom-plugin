<?php
/*
Plugin Name: Amiris Product Categories
Description: Plugin for creating custom product categories.
Version: 1.0
Author: andreivradulescu@gmail.com
*/
function create_product_category()
{
    try {
        $cats = array(
            array('thumb' => '/assets/img/categ_icons/central-heating.png', 'name' => 'centrale2', 'description' => 'descriere categorie centrale', 'slug' => 'centrale-termice-in-condensare-BAXI', 'parent_categ_id' => ''),
            array('thumb' => '/assets/img/categ_icons/central-heating.png', 'name' => 'boilere2', 'description' => 'descriere categorie boilere', 'slug' => 'boilere', 'parent_categ_id' => 0),
            array('thumb' => '/assets/img/categ_icons/central-heating.png', 'name' => 'radiatoare2', 'description' => 'descriere categorie radiatoare', 'slug' => 'radiatoare', 'parent_categ_id' => 0),
        );

        foreach ($cats as $data) {
            $thumb_id = get_attachment_id_from_url($data['thumb']);

            $cat_args = array(
                'description' => $data['description'],
                'slug' => $data['slug'],
                'parent' => $data['parent_categ_id']
            );

            $cat_id = wp_insert_term($data['name'], 'product_cat', $cat_args);
            $flag = taxonomy_exists('product_cat');
            echo ($flag);

            if (!is_wp_error($cat_id) && isset($cat_id['term_id'])) {
                // Log success message
                error_log('Category created successfully. Term ID: ' . $cat_id['term_id']);

                // Set thumbnail for category
                update_term_meta($cat_id['term_id'], 'thumbnail_id', $thumb_id);

                // Log thumbnail assignment
                error_log('Thumbnail assigned to category. Thumbnail ID: ' . $thumb_id);
            } else {
                // Log error message
                if (is_wp_error($cat_id)) {
                    error_log('Error creating category: ' . $cat_id->get_error_message());
                } else {
                    error_log('Unknown error occurred while creating category.');
                }
            }
        }
    } catch (Exception $e) {
        // Log any exceptions
        error_log('Exception caught: ' . $e->getMessage());
    }
}

// Function to get attachment ID from URL
function get_attachment_id_from_url($url)
{
    $attachment_id = 0;
    $attachment_url = esc_url($url);
    $attachment_id = attachment_url_to_postid($attachment_url);
    return $attachment_id;
}

add_action('init', 'create_product_category');

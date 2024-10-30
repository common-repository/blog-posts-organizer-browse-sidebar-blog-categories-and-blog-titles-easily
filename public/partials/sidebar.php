<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post;
$current_post_id = $post ? $post->ID : 0;
$categories      = get_categories(
    array(
		'hide_empty' => false,
		'meta_query' => array(
			array(
				'key'     => 'atw_category_show',
				'value'   => '1',
				'compare' => '=',
			),
		),
		'orderby'    => 'meta_value_num',
		'meta_key'   => 'atw_category_order',
		'order'      => 'ASC',
	)
);
$output          = '<div id="accordion" class="rmd-accordion">';

foreach ( $categories as $category_id ) {
    $category            = get_category( $category_id );
    $category_post_count = $category->count;
    $icon_url            = get_term_meta( $category->term_id, 'category_icon', true );
    $icon_html           = $icon_url ? '<img src="' . esc_url( $icon_url ) . '" alt="" class="category-icon">' : '';
    $output             .= '<h2>' . $icon_html . $category->name . ' <span class="rmd-category-count">' . $category_post_count . '</span></h2>';
    $output             .= '<div>';
    $output             .= '<ul>';

    $subcategories = get_categories(
        array(
			'parent'     => $category->term_id,
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'     => 'atw_category_show',
					'value'   => '1',
					'compare' => '=',
				),
			),
			'orderby'    => 'meta_value_num',
			'meta_key'   => 'atw_category_order',
			'order'      => 'ASC',
		)
    );

    if ( $subcategories ) {
        foreach ( $subcategories as $subcategory ) {
            $subcategory_post_count = $subcategory->count;
            $output                .= '<li class="subcategory">' . $subcategory->name . ' (' . $subcategory_post_count . ')</li>';
            $output                .= '<ul>';

            $posts = get_posts(
                array(
					'category'    => $subcategory->term_id,
					'post_status' => 'publish',
					'numberposts' => -1,
				)
            );

            if ( $posts ) {
                foreach ( $posts as $post_item ) {
                    $custom_title  = get_post_meta( $post_item->ID, '_atw_custom_title', true );
                    $display_title = $custom_title ? $custom_title : $post_item->post_title;
                    $active_class  = $post_item->ID == $current_post_id ? 'class="active"' : '';
                    $output       .= '<li ' . $active_class . '><a href="' . get_permalink( $post_item->ID ) . '">' . $display_title . '</a></li>';
                }
            }

            $output .= '</ul>';
        }
    } else {
        $posts = get_posts(
            array(
				'category'    => $category->term_id,
				'post_status' => 'publish',
				'numberposts' => -1,
			)
        );

        if ( $posts ) {
            foreach ( $posts as $post_item ) {
                $custom_title  = get_post_meta( $post_item->ID, '_atw_custom_title', true );
                $display_title = $custom_title ? $custom_title : $post_item->post_title;
                $active_class  = $post_item->ID == $current_post_id ? 'class="active"' : '';
                $output       .= '<li ' . $active_class . '><a href="' . get_permalink( $post_item->ID ) . '">' . $display_title . '</a></li>';
            }
        }
    }

    $output .= '</ul>';
    $output .= '</div>';
}

$output .= '</div>';
$output .= '</div>';

echo wp_kses_post( $output );

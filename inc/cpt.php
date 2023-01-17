<?php

if ( !function_exists( 'unimarken_business_cpt' ) )
{
    function unimarken_business_cpt()
    {
        $labels = array(
            'name'                  => _x( 'Business', 'Post type general name', unimarken_textDomain() ),
            'singular_name'         => _x( 'Business', 'Post type singular name', unimarken_textDomain() ),
            'menu_name'             => _x( 'Business', 'Admin Menu text', unimarken_textDomain() ),
            'name_admin_bar'        => _x( 'Business', 'Add New on Toolbar', unimarken_textDomain() ),
            'add_new'               => __( 'Add Business', unimarken_textDomain() ),
            'add_new_item'          => __( 'Add Name Business', unimarken_textDomain() ),
            'new_item'              => __( 'New Business', unimarken_textDomain() ),
            'edit_item'             => __( 'Edit Business', unimarken_textDomain() ),
            'view_item'             => __( 'View Business', unimarken_textDomain() ),
            'all_items'             => __( 'All Business', unimarken_textDomain() ),
            'search_items'          => __( 'Search Business', unimarken_textDomain() ),
            'parent_item_colon'     => __( 'Parent Business:', unimarken_textDomain() ),
            'not_found'             => __( 'No Business found.', unimarken_textDomain() ),
            'not_found_in_trash'    => __( 'No Business found in Trash.', unimarken_textDomain() ),
            'featured_image'        => _x( 'Business Logo', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', unimarken_textDomain() ),
            'set_featured_image'    => _x( 'Set business logo', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', unimarken_textDomain() ),
            'remove_featured_image' => _x( 'Remove business logo', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', unimarken_textDomain() ),
            'use_featured_image'    => _x( 'Use as business logo', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', unimarken_textDomain() ),
            'archives'              => _x( 'Business archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', unimarken_textDomain() ),
            'insert_into_item'      => _x( 'Insert into business', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', unimarken_textDomain() ),
            'uploaded_to_this_item' => _x( 'Uploaded to this business', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', unimarken_textDomain() ),
            'filter_items_list'     => _x( 'Filter business list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', unimarken_textDomain() ),
            'items_list_navigation' => _x( 'Business list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', unimarken_textDomain() ),
            'items_list'            => _x( 'Business list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', unimarken_textDomain() ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-building',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'business' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'thumbnail', 'author' ),
            'taxonomies'         => array(),
        );

        register_post_type( 'business', $args );
    }
    add_action( 'init', 'unimarken_business_cpt' );
}
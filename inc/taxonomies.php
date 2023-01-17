<?php

function unimarken_register_taxonomies()
{
    $taxonomies = [
        array( 'taxonomy' => 'ubCountries', 'slug' => 'ubcountries', 'name' => 'Countries', 'singular' => 'Country' ),
        array( 'taxonomy' => 'ubSections',  'slug' => 'ubsections',  'name' => 'Sections',  'singular' => 'Section' ),
        array( 'taxonomy' => 'ubDivisions', 'slug' => 'ubdivisions', 'name' => 'Divisions', 'singular' => 'Division' ),
        array( 'taxonomy' => 'ubGroups',    'slug' => 'ubgroups',    'name' => 'Groups',    'singular' => 'Group' ),
        array( 'taxonomy' => 'ubClasses',   'slug' => 'ubclasses',   'name' => 'Classes',   'singular' => 'Class' ),
        array( 'taxonomy' => 'ubBags',      'slug' => 'ubbags',      'name' => 'Stock exchange',      'singular' => 'Bag' ),
        array( 'taxonomy' => 'ubCurrency',  'slug' => 'ubcurrency',  'name' => 'Currency',  'singular' => 'Currency' )
    ];

    foreach( $taxonomies as $taxonomy )
    {
        $labels = array(
            'name'              => _x( $taxonomy['name'], 'taxonomy general name', unimarken_textDomain() ),
            'singular_name'     => _x( $taxonomy['singular'], 'taxonomy singular name', unimarken_textDomain() ),
            'search_items'      => __( 'Search ' . $taxonomy['name'], unimarken_textDomain() ),
            'all_items'         => __( 'All ' . $taxonomy['name'], unimarken_textDomain() ),
            'parent_item'       => __( 'Parent ' . $taxonomy['singular'], unimarken_textDomain() ),
            'parent_item_colon' => __( 'Parent ' . $taxonomy['singular'] . ':', unimarken_textDomain() ),
            'edit_item'         => __( 'Edit ' . $taxonomy['singular'], unimarken_textDomain() ),
            'update_item'       => __( 'Update ' . $taxonomy['singular'], unimarken_textDomain() ),
            'add_new_item'      => __( 'Add New ' . $taxonomy['singular'], unimarken_textDomain() ),
            'new_item_name'     => __( 'New Name ' . $taxonomy['singular'], unimarken_textDomain() ),
            'menu_name'         => __( $taxonomy['name'], unimarken_textDomain() ),
        );
    
        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => $taxonomy['slug'] ),
        );
    
        register_taxonomy( $taxonomy['taxonomy'], array( 'business' ), $args );
    }
}
add_action( 'init', 'unimarken_register_taxonomies');


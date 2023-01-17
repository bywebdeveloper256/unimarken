<?php

function unimarken_call_ajax_save_business_profile()
{
    $r = array( 
        "r" => false, 
        "m" => "Acción no permitida." 
    );

    $nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

    $nonce  = sanitize_text_field( $nonce );

    if ( wp_verify_nonce( $nonce, 'unimarken-ajax-nonce' ) )
    {
        require unimarken_name_dir . 'ajax/business_profile_save.php';
    }

    echo json_encode( $r );

    wp_die();
}
add_action( 'wp_ajax_nopriv_unimarken_save_business_profile', 'unimarken_call_ajax_save_business_profile' );
add_action( 'wp_ajax_unimarken_save_business_profile', 'unimarken_call_ajax_save_business_profile' );

function unimarken_call_ajax_search_business()
{
    $r = array( 
        "r" => false, 
        "m" => "Acción no permitida." 
    );

    $nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

    $nonce  = sanitize_text_field( $nonce );

    if ( wp_verify_nonce( $nonce, 'unimarken-sban' ) )
    {
        require unimarken_name_dir . 'ajax/search_business.php';
    }

    echo json_encode( $r );

    wp_die();
}
add_action( 'wp_ajax_nopriv_unimarken_search_business', 'unimarken_call_ajax_search_business' );
add_action( 'wp_ajax_unimarken_search_business', 'unimarken_call_ajax_search_business' );

function unimarken_call_ajax_add_bag()
{
    $r = array( 
        "r" => false, 
        "m" => "Acción no permitida." 
    );

    $nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

    $nonce  = sanitize_text_field( $nonce );

    if ( wp_verify_nonce( $nonce, 'unimarken-addBag-nonce' ) )
    {
        require unimarken_name_dir . 'ajax/add_bags.php';
    }

    echo json_encode( $r );

    wp_die();
}
add_action( 'wp_ajax_nopriv_unimarken_add_bag', 'unimarken_call_ajax_add_bag' );
add_action( 'wp_ajax_unimarken_add_bag', 'unimarken_call_ajax_add_bag' );

function unimarken_call_ajax_filter()
{
    $r = array( 
        "r" => false, 
        "m" => "Acción no permitida." 
    );

    $nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

    $nonce  = sanitize_text_field( $nonce );

    if ( wp_verify_nonce( $nonce, 'unimarken-filter-nonce' ) )
    {
        require unimarken_name_dir . 'ajax/filter.php';
    }

    echo json_encode( $r );

    wp_die();
}
add_action( 'wp_ajax_nopriv_unimarken_filter', 'unimarken_call_ajax_filter' );
add_action( 'wp_ajax_unimarken_filter', 'unimarken_call_ajax_filter' );

function unimarken_call_ajax_filter_tax()
{
    $r = array( 
        "r" => false, 
        "m" => "Acción no permitida." 
    );

    $nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

    $nonce  = sanitize_text_field( $nonce );

    if ( wp_verify_nonce( $nonce, 'unimarken-filter-tax-nonce' ) )
    {
        require unimarken_name_dir . 'ajax/filter_tax.php';
    }

    echo json_encode( $r );

    wp_die();
}
add_action( 'wp_ajax_nopriv_unimarken_filter_tax', 'unimarken_call_ajax_filter_tax' );
add_action( 'wp_ajax_unimarken_filter_tax', 'unimarken_call_ajax_filter_tax' );

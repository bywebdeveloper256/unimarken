<?php

function unimarken_enqueue_css_and_js()
{
    if( is_page( array( 8, 107, 263, 352, 357 ) ) || is_front_page() || is_singular( 'business' ) )
    {
        wp_enqueue_style( 'unimarken-bootstrapcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', array(), unimarken_version() );

        wp_enqueue_script( 'unimarken-bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), unimarken_version(), true );
    }

    if( is_front_page() )
    {
        wp_enqueue_style( 'unimarkSearchBusinessCss', unimarken_url_plugin . 'assets/css/business_search.css', array(), unimarken_version() );

        wp_enqueue_script( 'unimarkSearchBusinessJs', unimarken_url_plugin . 'assets/js/business_search.js', array('jquery'), unimarken_version(), true );

        wp_enqueue_script( 'unimarkFilterTax', unimarken_url_plugin . 'assets/js/filter_tax.js', array('jquery'), unimarken_version(), true );

        wp_localize_script( 'unimarkSearchBusinessJs', 'unimarken_sbar', 
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_search_business',
                'nonce'     => wp_create_nonce( 'unimarken-sban' ) 
            )
        );

        wp_localize_script( 'unimarkFilterTax', 'unimarken_ft', 
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_filter_tax',
                'nonce'     => wp_create_nonce( 'unimarken-filter-tax-nonce' ) 
            )
        );

    }

    if( is_page( 263 ) )
    {
        wp_enqueue_script( 'unimarken_filterJs', unimarken_url_plugin . 'assets/js/filter.js', array('jquery'), unimarken_version(), true );

        wp_localize_script( 'unimarken_filterJs', 'unimarken_filter',
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_filter',
                'nonce'     => wp_create_nonce( 'unimarken-filter-nonce' ) 
            )
        );

        wp_enqueue_script( 'unimarkFilterTax', unimarken_url_plugin . 'assets/js/filter_tax.js', array('jquery'), unimarken_version(), true );

        wp_localize_script( 'unimarkFilterTax', 'unimarken_ft', 
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_filter_tax',
                'nonce'     => wp_create_nonce( 'unimarken-filter-tax-nonce' ) 
            )
        );
    }

    if( is_page( 8 ) )
    {
        wp_enqueue_style( 'unimarkencss', unimarken_url_plugin . 'assets/css/business_profile.css', array(), unimarken_version() );

        wp_enqueue_script( 'unimarkenjs', unimarken_url_plugin . 'assets/js/business_profile.js', array('jquery'), unimarken_version(), true );

        wp_enqueue_script( 'unimarkenBagJs', unimarken_url_plugin . 'assets/js/business_add_bag.js', array('jquery'), unimarken_version(), true );

        wp_localize_script( 'unimarkenjs', 'unimarken_addBag', 
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_add_bag',
                'nonce'     => wp_create_nonce( 'unimarken-addBag-nonce' ) 
            )
        );

        wp_localize_script( 'unimarkenBagJs', 'unimarken_ajax_requests', 
            array(
                'url'       => admin_url( 'admin-ajax.php' ),
                'action'    => 'unimarken_save_business_profile',
                'nonce'     => wp_create_nonce( 'unimarken-ajax-nonce' )
            )
        );
    }

    wp_enqueue_style( 'unimarkenGeneralCss', unimarken_url_plugin . 'assets/css/business_general.css', array(), unimarken_version() );
    wp_enqueue_script( 'unimarkenGeneralJs', unimarken_url_plugin . 'assets/js/business_general.js', array('jquery'), unimarken_version(), true );
}
add_action( 'wp_enqueue_scripts', 'unimarken_enqueue_css_and_js' );


function unimarken_enqueue_css_and_js_backend()
{
    if( get_post_type() === 'business' )
    {
        wp_enqueue_style( 'unimarken-bootstrapcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', array(), unimarken_version() );

        wp_enqueue_script( 'unimarken-bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), unimarken_version(), true );

        wp_enqueue_script( 'unimarkenBagJs_back', unimarken_url_plugin . 'assets/back/js/business_add_bag.js', array('jquery'), unimarken_version(), true );

        wp_localize_script( 'unimarkenBagJs_back', 'unimarken_addBagBack', 
            array( 
                'url'       => admin_url( 'admin-ajax.php' ), 
                'action'    => 'unimarken_add_bag',
                'nonce'     => wp_create_nonce( 'unimarken-addBag-nonce' ) 
            )
        );

        wp_enqueue_style( 'unimarkenGeneralCss', unimarken_url_plugin . 'assets/back/css/business_general.css', array(), unimarken_version() );
    }
}
add_action( 'admin_enqueue_scripts', 'unimarken_enqueue_css_and_js_backend' );
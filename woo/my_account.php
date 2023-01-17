<?php

if ( !function_exists( 'unimarken_endpoints_business_profile' ) )
{
    function unimarken_endpoints_business_profile()
    {
        add_rewrite_endpoint( 'business_profile', EP_ROOT | EP_PAGES );
    }
    add_action( 'init', 'unimarken_endpoints_business_profile' );
}
 

if ( !function_exists( 'unimarken_business_profile_endpoint_content' ) )
{
    function unimarken_business_profile_endpoint_content()
    {
        if( unimaken_get_role_user() === 'subscriber' || unimaken_get_role_user() === 'administrator' ):

            unimarken_get_template_part_plugin( 'business', 'profile' );
        else:
            echo '<p>' . esc_html__( 'Renew your subscription in the subscriptions tab to view and/or edit your company profile.', unimarken_textDomain() ) . '</p>';
    
        endif;
    }
    add_action( 'woocommerce_account_business_profile_endpoint', 'unimarken_business_profile_endpoint_content' );
}

if ( !function_exists( 'unimarken_account_menu_order' ) )
{
    function unimarken_account_menu_order( $items )
    {
        $items = array(
            'dashboard'                     => __( 'Dashboard', 'woocommerce' ),
            'ppcp-paypal-payment-tokens'    => __( 'PayPal payments', 'woocommerce' ),
            'subscriptions'                 => __( 'Subscriptions', 'woocommerce' ),
            'business_profile'              => __( 'Business profile', 'woocommerce' ),
            'edit-account'                  => __( 'Account details', 'woocommerce' ),
            'customer-logout'               => __( 'Logout', 'woocommerce' ),
        );
        
        return $items;
    }
    add_filter ( 'woocommerce_account_menu_items', 'unimarken_account_menu_order' );
}

if( !function_exists('unimarken_add_content_dashboard') )
{
    function unimarken_add_content_dashboard()
    {
        echo '<strong>' . esc_html__( 'To add or edit your company go to the Business profile tab', unimarken_textDomain() ) . '</strong>';
    }
    add_action( 'woocommerce_account_dashboard', 'unimarken_add_content_dashboard' );
}
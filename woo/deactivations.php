<?php

/**
 * Desactiva la página shop
 */
function unimarken_shop_url_redirect()
{
    if( is_shop() )
    {
        wp_redirect( home_url() );  
        exit();
    }
}
add_action( 'template_redirect', 'unimarken_shop_url_redirect' );
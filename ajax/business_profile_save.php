<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$ubNameLa         = isset( $_REQUEST['ubNameLa'] )      ? $_REQUEST['ubNameLa']       : '';
$ubIntName      = isset( $_REQUEST['ubIntName'] )   ? $_REQUEST['ubIntName']    : '';
$ubWeb          = isset( $_REQUEST['ubWeb'] )       ? $_REQUEST['ubWeb']        : '';
$ubComments     = isset( $_REQUEST['ubComments'] )  ? $_REQUEST['ubComments']   : '';
//$ubSymbol       = isset( $_REQUEST['ubSymbol'] )    ? $_REQUEST['ubSymbol']     : '';
//$ubSymbolTwo    = isset( $_REQUEST['ubSymbolTwo'] ) ? $_REQUEST['ubSymbolTwo']  : '';
$ubCountry      = isset( $_REQUEST['ubCountry'] )   ? $_REQUEST['ubCountry']    : '';
$ubSection      = isset( $_REQUEST['ubSection'] )   ? $_REQUEST['ubSection']    : '';
$ubDivision     = isset( $_REQUEST['ubDivision'] )  ? $_REQUEST['ubDivision']   : '';
$ubGroup        = isset( $_REQUEST['ubGroup'] )     ? $_REQUEST['ubGroup']      : '';
$ubClass        = isset( $_REQUEST['ubClass'] )     ? $_REQUEST['ubClass']      : '';

if(
    !empty( $ubNameLa )     && !empty( $ubCountry ) && !empty( $ubWeb ) && !empty( $ubSection ) && 
    !empty( $ubDivision )
){
    if( is_user_logged_in() )
    {
        $current_user_id = get_current_user_id();

        $business_post = array(
            'post_type'     => 'business',
            'ID'            => unimarken_get_company_id(),
            'post_title'    => sanitize_text_field( $ubNameLa ),
            'post_status'   => 'publish',
            'post_author'   => $current_user_id,
        );

        if( isset( $_FILES['ubLogo'] ) &&  !empty( $_FILES['ubLogo'] ) )
        {
            $logo = unimarken_insert_logo( unimarken_get_company_id(), $_FILES['ubLogo'] );

            if( $logo === false )
            {
                $r['m'] = esc_html__( 'The inserted logo is not correct. Be sure to use jpg, jpeg or png as the extension.', unimarken_textDomain() );
                
                echo json_encode( $r );
                wp_die();
            }
        }

        $id_post = wp_insert_post( $business_post ); 

        if( !is_wp_error( $id_post ) )
        {
            $isin_values = get_post_custom_values( 'unimarken_isin', $id_post );
            if( $isin_values ):
                foreach ( $isin_values as $value ):
                    delete_post_meta( $id_post, 'unimarken_isin', $value );
                endforeach;
            endif;

            $ticker_values = get_post_custom_values( 'unimarken_ticker', $id_post );
            if( $ticker_values ):
                foreach ( $ticker_values as $value ):
                    delete_post_meta( $id_post, 'unimarken_ticker', $value );
                endforeach;
            endif;

            $ticker2_values = get_post_custom_values( 'unimarken_ticker2', $id_post );
            if( $ticker2_values ):
                foreach ( $ticker2_values as $value ):
                    delete_post_meta( $id_post, 'unimarken_ticker2', $value );
                endforeach;
            endif;

            wp_set_post_terms( $id_post, $ubCountry, 'ubCountries' );
            wp_set_post_terms( $id_post, $ubSection, 'ubSections' );
            wp_set_post_terms( $id_post, $ubDivision, 'ubDivisions' );
            wp_set_post_terms( $id_post, $ubGroup, 'ubGroups' );
            wp_set_post_terms( $id_post, $ubClass, 'ubClasses' );

            update_post_meta( $id_post, 'unimarken_business_name',      sanitize_text_field( $ubIntName ) );
            update_post_meta( $id_post, 'unimarken_comments',           sanitize_text_field( $ubComments ) );
            update_post_meta( $id_post, 'unimarken_web',                sanitize_url( $ubWeb, array( 'http', 'https' ) ) );

            $ubBag  = str_replace("\\", "", $_REQUEST['ubBag'] );
            
            $deBag = json_decode( $ubBag, true );
            
            $bags     = [];
            $currency = [];
            $isin     = [];
            $ticker   = [];
            $ticker2  = [];
            $relation = [];

            foreach( $deBag as $v ):

                $bags[]     = sanitize_text_field( $v['bag'] );
                $currency[] = sanitize_text_field( $v['currency'] );
                $relation[] = $v;
                //update_post_meta( $id_post, 'unimarken_symbol',             sanitize_text_field( $ubSymbol ) );
                //update_post_meta( $id_post, 'unimarken_symboltwo',          sanitize_text_field( $ubSymbolTwo ) );
            endforeach;

            foreach ( $relation as $value):
                if( !empty( $value['isin'] ) ) add_post_meta( $id_post, 'unimarken_isin', $value['isin'] );
                if( !empty( $value['ticker'] ) ) add_post_meta( $id_post, 'unimarken_ticker', $value['ticker'] );
                if( !empty( $value['ticker2'] ) ) add_post_meta( $id_post, 'unimarken_ticker2', $value['ticker2'] );
            endforeach;

            if( !empty( $bags ) )       wp_set_post_terms( $id_post, $bags, 'ubBags' );
            if( !empty( $currency ) )   wp_set_post_terms( $id_post, $currency, 'ubCurrency' );

            if( !empty( $relation ) )   update_post_meta( $id_post, 'unimarken_relation_bag', $relation );

            $r['r'] = true;
            $r['m'] = esc_html__( 'Changes have been saved successfully', unimarken_textDomain() );
    
        }else{
            $r['m'] = esc_html__( 'An error has occurred', unimarken_textDomain() );
        }
    }
}else{
    $r['m'] = esc_html__( 'An error has occurred', unimarken_textDomain() );
}

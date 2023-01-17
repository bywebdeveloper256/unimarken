<?php

    $tax = [];
    $query = [];
    $meta   = [];
    $meta_query = [];
    $tax_query  = [];
    
    $ubIntName      = isset( $_REQUEST['ubIntName'] )   && !empty( $_REQUEST['ubIntName'] )     ? $_REQUEST['ubIntName'] : '';
    $ubIsin         = isset( $_REQUEST['ubIsin'] )      && !empty( $_REQUEST['ubIsin'] )        ? $meta["unimarken_isin"]          = $_REQUEST['ubIsin']    : '';
    $ubTicker       = isset( $_REQUEST['ubTicker'] )    && !empty( $_REQUEST['ubTicker'] )      ? $meta["unimarken_ticker"]        = $_REQUEST['ubTicker']  : '';

    $ubBag          = isset( $_REQUEST['ubBag'] )       && !empty( $_REQUEST['ubBag'] )         ? $tax['ubBags']        = $_REQUEST['ubBag']        : '';
    $ubCurrency     = isset( $_REQUEST['ubCurrency'] )  && !empty( $_REQUEST['ubCurrency'] )    ? $tax['ubCurrency']    = $_REQUEST['ubCurrency']   : '';
    $ubCountry      = isset( $_REQUEST['ubCountry'] )   && !empty( $_REQUEST['ubCountry'] )     ? $tax['ubCountries']   = $_REQUEST['ubCountry']    : '';
    $ubSection      = isset( $_REQUEST['ubSection'] )   && !empty( $_REQUEST['ubSection'] )     ? $tax['ubSections']    = $_REQUEST['ubSection']    : '';
    $ubDivision     = isset( $_REQUEST['ubDivision'] )  && !empty( $_REQUEST['ubDivision'] )    ? $tax['ubDivisions']   = $_REQUEST['ubDivision']   : '';
    $ubGroup        = isset( $_REQUEST['ubGroup'] )     && !empty( $_REQUEST['ubGroup'] )       ? $tax['ubGroups']      = $_REQUEST['ubGroup']      : '';
    $ubClass        = isset( $_REQUEST['ubClass'] )     && !empty( $_REQUEST['ubClass'] )       ? $tax['ubClasses']     = $_REQUEST['ubClass']      : '';

    $users = unimaken_get_users_subscriber_administrator();

    $args = array(
        'post_type'         => 'business',
        'posts_per_page'    => -1,
        'fields'            => 'ids',
        'author__in'        => $users,
        'orderby'           => 'ID',
        'order'             => 'ASC',
    );

    if( !empty( $ubIntName ) ) $args['s'] = $ubIntName;

    if( !empty( $meta ) ):

        foreach( $meta as $k => $v ):

            $meta_query[] = array(  'key' => $k, 'value' => $v, 'compare' => 'LIKE' );
        endforeach;

        $args['meta_query'] = array( $meta_query );
    endif;

    if( !empty( $tax ) ):

        foreach( $tax as $k => $v ):
        
            $query[]= array( 'taxonomy' => $k, 'terms' => $v );
        endforeach;
    

        if( count( $query ) > 1 ):
        
            $tax_query  = array( 'relation' => 'AND', $query );

            $args['tax_query'] = $tax_query;

        elseif( count( $query ) == 1 ):

            $tax_query  = array( $query );

            $args['tax_query'] = $tax_query;

        endif;
    endif;

    $someposts = get_posts( $args );

    if( empty($someposts)){
        $someposts = array();
    }

    $termsBags      = get_terms( array( 'taxonomy' => 'ubBags',      'object_ids' => $someposts, 'orderby' => 'title', 'order' => 'ASC' ) );
    $termsCurrency  = get_terms( array( 'taxonomy' => 'ubCurrency',  'object_ids' => $someposts, 'orderby' => 'title', 'order' => 'ASC' ) );
    $termsCountries = get_terms( array( 'taxonomy' => 'ubCountries', 'object_ids' => $someposts, 'orderby' => 'title', 'order' => 'ASC' ) );
    $termsSections  = get_terms( array( 'taxonomy' => 'ubSections',  'object_ids' => $someposts, 'orderby' => 'id',    'order' => 'ASC' ) );
    $termsDivisions = get_terms( array( 'taxonomy' => 'ubDivisions', 'object_ids' => $someposts, 'orderby' => 'id',    'order' => 'ASC' ) );
    $termsGroups    = get_terms( array( 'taxonomy' => 'ubGroups',    'object_ids' => $someposts, 'orderby' => 'id',    'order' => 'ASC' ) );
    $termsClasses   = get_terms( array( 'taxonomy' => 'ubClasses',   'object_ids' => $someposts, 'orderby' => 'id',    'order' => 'ASC' ) );
    
    $r['bags']      = $termsBags;
    $r['currency']  = $termsCurrency;
    $r['countries'] = $termsCountries;
    $r['sections']  = $termsSections;
    $r['divisions'] = $termsDivisions;
    $r['groups']    = $termsGroups;
    $r['classes']   = $termsClasses;

    $r['selectBag']         = $ubBag;
    $r['selectCurrency']    = $ubCurrency;
    $r['selectCountry']     = $ubCountry;
    $r['selectSection']     = $ubSection;
    $r['selectDivision']    = $ubDivision;
    $r['selectGroup']       = $ubGroup;
    $r['selectClass']       = $ubClass;

    $r['r'] = true;

    
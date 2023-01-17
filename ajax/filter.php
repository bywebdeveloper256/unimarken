<?php
    $meta   = [];
    $tax    = [];
    $query  = [];
    $meta_query = [];
    $tax_query  = [];

    $ubIntName      = isset( $_REQUEST['ubIntName'] )   && !empty( $_REQUEST['ubIntName'] )     ? $_REQUEST['ubIntName'] : '';
    $ubIsin         = isset( $_REQUEST['ubIsin'] )      && !empty( $_REQUEST['ubIsin'] )        ? $meta["unimarken_isin"]          = $_REQUEST['ubIsin']    : '';

    $ubBag          = isset( $_REQUEST['ubBag'] )       && !empty( $_REQUEST['ubBag'] )         ? $tax['ubBags']        = $_REQUEST['ubBag']        : '';
    $ubCurrency     = isset( $_REQUEST['ubCurrency'] )  && !empty( $_REQUEST['ubCurrency'] )    ? $tax['ubCurrency']    = $_REQUEST['ubCurrency']   : '';
    $ubCountry      = isset( $_REQUEST['ubCountry'] )   && !empty( $_REQUEST['ubCountry'] )     ? $tax['ubCountries']   = $_REQUEST['ubCountry']    : '';
    $ubSection      = isset( $_REQUEST['ubSection'] )   && !empty( $_REQUEST['ubSection'] )     ? $tax['ubSections']    = $_REQUEST['ubSection']    : '';
    $ubDivision     = isset( $_REQUEST['ubDivision'] )  && !empty( $_REQUEST['ubDivision'] )    ? $tax['ubDivisions']   = $_REQUEST['ubDivision']   : '';
    $ubGroup        = isset( $_REQUEST['ubGroup'] )     && !empty( $_REQUEST['ubGroup'] )       ? $tax['ubGroups']      = $_REQUEST['ubGroup']      : '';
    $ubClass        = isset( $_REQUEST['ubClass'] )     && !empty( $_REQUEST['ubClass'] )       ? $tax['ubClasses']     = $_REQUEST['ubClass']      : '';

    $users = unimaken_get_users_subscriber_administrator();

    $args = array(  'post_type'     => 'business', 
                    'post_status'   => 'publish',
                    'paged'         => get_query_var('paged') ? get_query_var('paged') : 1,
                    'author__in'    => $users,
                    'orderby'       => 'ID',
                    'order'         => 'ASC'
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

        if( count( $tax ) > 1 ):
        
            $tax_query  = array( 'relation' => 'AND', $query );

            $args['tax_query'] = $tax_query;
        else:
            $tax_query  = array( $query );

            $args['tax_query'] = $tax_query;
        endif;
    endif;

    

    $loop = new WP_Query( $args );
    
    $count = $loop->found_posts;

    if ( $loop->have_posts() ):
        
        while ( $loop->have_posts() ):
            
            $loop->the_post();
            
            $termCountries = get_the_terms( get_the_ID(), 'ubCountries' );

            ob_start();
                include unimarken_name_dir . '/templates/business_items.php';
            $items[] = ob_get_clean();
        endwhile;

        $big = 999999999;
        
        $r['r'] = true;
        $r['m'] = '';
        $r['items'] = $items;

        $PubIntName  = !empty( $ubIntName )     ? '&ubIntName=' . $ubIntName    : '';
        $PubCountry  = !empty( $ubCountry )     ? '&ubCountry=' . $ubCountry    : '';
        $PubSection  = !empty( $ubSection )     ? '&ubSection=' . $ubSection    : '';
        $PubDivision = !empty( $ubDivision )    ? '&ubDivision=' . $ubDivision  : '';
        $PubGroup    = !empty( $ubGroup )       ? '&ubGroup=' . $ubGroup        : '';
        $PubClass    = !empty( $ubClass )       ? '&ubClass=' . $ubClass        : '';
        $PubIsin     = !empty( $ubIsin )        ? '&ubIsin=' . $ubIsin          : '';
        $PubBag      = !empty( $ubBag )         ? '&ubBag=' . $ubBag            : '';

        $url_parameter = $PubIntName . $PubCountry . $PubDivision . $PubGroup . $PubClass . $PubIsin . $PubBag;

        $paginate = paginate_links( array(
                            'base'      => str_replace( array( $big, 'wp-admin/admin-ajax.php' ), array( '%#%', 'business-results/' ), esc_url( get_pagenum_link( $big ) . $url_parameter ) ),
                            'format'    => '?paged=%#%',
                            'current'   => max( 1, get_query_var('paged') ),
                            'total'     => $loop->max_num_pages
                        ) );       

        $r['paginate'] = $paginate;
    else:
        $r['m'] = esc_html__( 'No results found', unimarken_textDomain() );
    endif;

    $r['count'] = $count;

    wp_reset_postdata();
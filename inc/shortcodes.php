<?php
set_time_limit(0);

add_shortcode( "form_search_busines_home", "unimarken_form_search_busines_home" );

function unimarken_form_search_busines_home()
{   
    unimarken_get_template_part_plugin( 'business', 'search' );
}

add_shortcode( "result_search_business", "unimarken_result_search_business" );

function unimarken_result_search_business()
{   
    $meta   = [];
    $tax    = [];
    $query  = [];
    $query_meta = [];
    $tax_query  = [];
    
    //$ubTicker2      = isset( $_REQUEST['ubTicker'] )    ? $meta["unimarken_ticker2"]        = $_REQUEST['ubTicker']     : '';
    //$ubIntName      = isset( $_REQUEST['ubIntName'] )   ? $meta["unimarken_business_name"]  = $_REQUEST['ubIntName']    : '';
    $ubIntName      = isset( $_REQUEST['ubIntName'] )   ? $_REQUEST['ubIntName'] : '';
    $ubTicker1      = isset( $_REQUEST['ubTicker'] )    ? $meta["unimarken_ticker"]         = $_REQUEST['ubTicker']     : '';
    $ubIsin         = isset( $_REQUEST['ubIsin'] )      ? $meta["unimarken_isin"]           = $_REQUEST['ubIsin']       : '';
    $ubBag          = isset( $_REQUEST['ubBag'] )       ? $tax['ubBags']        = $_REQUEST['ubBag']        : '';
    $ubCurrency     = isset( $_REQUEST['ubCurrency'] )  ? $tax['ubCurrency']    = $_REQUEST['ubCurrency']   : '';
    $ubCountry      = isset( $_REQUEST['ubCountry'] )   ? $tax['ubCountries']   = $_REQUEST['ubCountry']    : '';
    $ubSection      = isset( $_REQUEST['ubSection'] )   ? $tax['ubSections']    = $_REQUEST['ubSection']    : '';
    $ubDivision     = isset( $_REQUEST['ubDivision'] )  ? $tax['ubDivisions']   = $_REQUEST['ubDivision']   : '';
    $ubGroup        = isset( $_REQUEST['ubGroup'] )     ? $tax['ubGroups']      = $_REQUEST['ubGroup']      : '';
    $ubClass        = isset( $_REQUEST['ubClass'] )     ? $tax['ubClasses']     = $_REQUEST['ubClass']      : '';
    $type           = isset( $_REQUEST['type'] )        ? $_REQUEST['type'] : '0';

    $users = unimaken_get_users_subscriber_administrator();

    $args = array(  
        'post_type'     => 'business', 
        'post_status'   => 'publish',
        'paged'         => get_query_var('paged') ? get_query_var('paged') : 1,
        'author__in'    => $users,
        'orderby'       => 'ID',
        'order'         => 'ASC'
    );

    if( !empty( $ubIntName ) && $type === '0' ): 
        
        $args['s'] = $ubIntName;

    elseif( !empty( $ubIntName ) && $type === '1' ):

        $meta["unimarken_business_name"]  = $_REQUEST['ubIntName'];

    endif;

    if( !empty( $meta ) ):

        foreach( $meta as $k => $v ):
        
            $query_meta[] = array(  'key' => $k, 'value' => $v, 'compare' => 'LIKE' );
            
        endforeach;

        if( count( $query_meta ) >= 1 ):

            $args['meta_query'] = $query_meta;

        endif;

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
    
    echo '<h4>';
    echo esc_html__( 'Business: ', unimarken_textDomain() );
    echo '<span id="count-business">' . $loop->found_posts . '</span>';
    echo '</h4>';
    echo '<div id="content-result-business">';
    
    if ( $loop->have_posts() ):
        
        while ( $loop->have_posts() ):
            
            $loop->the_post();
            
            $termCountries = get_the_terms( get_the_ID(), 'ubCountries' );

            include unimarken_name_dir . '/templates/business_items.php';

            the_posts_pagination( array(
                'mid_size' => 2,
                'prev_text' => __( 'Previous Page', 'textdomain' ),
                'next_text' => __( 'Next Page', 'textdomain' ),
            ) );

            

        endwhile;

        $big = 999999999;

        echo '<div class="my-3">';

        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $loop->max_num_pages
        ) );
        echo '</div>';
    else:
        esc_html_e( 'No results found', unimarken_textDomain() );
    endif;

    echo '</div>';

    wp_reset_postdata();
}

add_shortcode( "single_page_business", "unimarken_single_page_business" );

function unimarken_single_page_business()
{   
    include unimarken_name_dir . '/templates/business_single.php';
}

add_shortcode( "filter_page_result", "unimarken_filter_page_result" );
function unimarken_filter_page_result()
{
    include unimarken_name_dir . '/templates/business_filter.php';
}

add_shortcode( "filter_page_result_mobile", "unimarken_filter_page_result_mobile" );
function unimarken_filter_page_result_mobile()
{
    include unimarken_name_dir . '/templates/business_filter_mobile.php';
}

add_shortcode( "countries_and_sections", "unimarken_countries_and_sections" );
function unimarken_countries_and_sections()
{
    $ubCountries = get_categories( array( 'taxonomy' => 'ubCountries', 'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );
    $ubSections  = get_categories( array( 'taxonomy' => 'ubSections',  'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );

    foreach( $ubCountries as $country ):

        if( $country->count > 0 ):

            echo '<div class="d-flex align-items-center mb-4">';
            echo '<img class="sizeflags me-3" src="' . get_term_meta( $country->term_id, 'unimarken_country_flag', true ) . '" alt="' . $country->name . '" title="' . $country->name . '">';
            echo '<span class="h3 mb-0"> '. $country->name . ' (' . $country->count . ')</span>';
            echo '</div>';
            
            foreach( $ubSections as $section ):

                if( $section->count > 0 ):

                    $args = array(  'post_type'     => 'business',
                                    'post_status'   => 'publish',
                                    'nopaging'      => true,
                                    'tax_query'     => array(
                                        'relation'  => 'AND',
                                        array( 'taxonomy' => 'ubCountries', 'terms' => $country->term_id ),
                                        array( 'taxonomy' => 'ubSections', 'terms' => $section->term_id ),  
                                    )
                                );

                    $loop = new WP_Query( $args );

                    if( count( $loop->posts ) > 0 ):
            
                        echo '<div class="">';
                        echo '<p>' . $section->name.' (' . count( $loop->posts ) . ')</p>';
                        echo '</div>';
                    endif;
                endif;

            endforeach;
        endif;

    endforeach;
}

add_shortcode( "sections_and_countries", "unimarken_sections_and_countries" );
function unimarken_sections_and_countries()
{
    $ubCountries = get_categories( array( 'taxonomy' => 'ubCountries', 'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );
    $ubSections  = get_categories( array( 'taxonomy' => 'ubSections',  'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );

    foreach( $ubSections as $section ):

        if( $section->count > 0 ):

            echo '<div class="d-flex align-items-center mb-4">';
            echo '<span class="h3 mb-0"> '. $section->name . ' (' . $section->count . ')</span>';
            echo '</div>';
            
            foreach( $ubCountries as $country ):

                if( $country->count > 0 ):

                    $args = array(  'post_type'     => 'business',
                                    'post_status'   => 'publish', 
                                    'nopaging'      => true,
                                    'tax_query'     => array(
                                        'relation'  => 'AND',
                                        array( 'taxonomy' => 'ubCountries', 'terms' => $country->term_id ),
                                        array( 'taxonomy' => 'ubSections', 'terms' => $section->term_id ),  
                                    )
                                );

                    $loop = new WP_Query( $args );

                    if( count( $loop->posts ) > 0 ):
            
                        echo '<div>';
                        echo '<p class="d-flex align-items-center">';
                        echo '<img class="sizeflags me-3" src="' . get_term_meta( $country->term_id, 'unimarken_country_flag', true ) . '" alt="' . $country->name . '" title="' . $country->name . '">';
                        echo $country->name . ' (' . count( $loop->posts ) . ')';
                        echo '</p>';
                        echo '</div>';
                    endif;
                endif;

            endforeach;
        endif;

    endforeach;
}


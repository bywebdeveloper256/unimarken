<?php

if ( !function_exists( 'unimarken_fields_business' ) )
{
    function unimarken_fields_business()
    {
        $business = new_cmb2_box( array(
            'id'            => 'unimarken_fields_business',
            'title'         => __( 'Business', unimarken_textDomain() ),
            'object_types'  => array( 'business' ), // Post type
            'context'       => 'normal',
            'priority'      => 'low',
            'show_names'    => true, 
        ) );

        $business->add_field( array(
            'name'  => __( 'Business name', unimarken_textDomain() ),
            'id'    => 'unimarken_business_name',
            'type'  => 'text',
        ) );

        $business->add_field( array(
            'name'              => __( 'Country', unimarken_textDomain() ),
            'id'                => 'unimarken_country',
            'taxonomy'          => 'ubCountries', //Enter Taxonomy Slug
            'type'              => 'taxonomy_select',
            'remove_default'    => 'true',
            'query_args'        => array(
                // 'orderby' => 'slug',
                // 'hide_empty' => true,
            ),
            /*'attributes'        => array(
                'data-validation'   => 'required',
            ),*/
        ) );

        $business->add_field( array(
            'name'          => __( 'Web', unimarken_textDomain() ),
            'id'            => 'unimarken_web',
            'type'          => 'text_url',
            'protocols'     => array( 'http', 'https' ), // Array of allowed protocols
            /*'attributes'    => array(
                'data-validation'   => 'required',
            ),*/
        ) );

        $business->add_field( array(
            'name'  => __( 'Comments', unimarken_textDomain() ),
            'id'    => 'unimarken_comments',
            'type'  => 'textarea'
        ) );
    }
    add_action( 'cmb2_admin_init', 'unimarken_fields_business' );
    add_action( 'cmb2_init', 'unimarken_fields_business' );
}

if ( !function_exists( 'unimarken_fields_activities' ) )
{
    function unimarken_fields_activities()
    {
        $activities = new_cmb2_box( array(
            'id'            => 'unimarken_fields_activities',
            'title'         => __( 'Activities', unimarken_textDomain() ),
            'object_types'  => array( 'business' ), // Post type
            'context'       => 'normal',
            'priority'      => 'low',
            'show_names'    => true, 
        ) );

        $activities->add_field( array(
            'name'              => __( 'Section', unimarken_textDomain() ),
            'id'                => 'unimarken_section',
            'taxonomy'          => 'ubSections', //Enter Taxonomy Slug
            'type'              => 'taxonomy_select',
            'remove_default'    => 'true', 
            'query_args'        => array(
                // 'orderby' => 'slug',
                // 'hide_empty' => true,
            ),
            /*'attributes'        => array(
                'data-validation'   => 'required',
            ),*/
        ) );

        $activities->add_field( array(
            'name'              => __( 'Division', unimarken_textDomain() ),
            'id'                => 'unimarken_division',
            'taxonomy'          => 'ubDivisions', //Enter Taxonomy Slug
            'type'              => 'taxonomy_select',
            'remove_default'    => 'true', 
            'query_args'        => array(
                // 'orderby' => 'slug',
                // 'hide_empty' => true,
            ),
            /*'attributes' => array(
                'data-validation' => 'required',
            ),*/
        ) );

        $activities->add_field( array(
            'name'              => __( 'Group', unimarken_textDomain() ),
            'id'                => 'unimarken_group',
            'taxonomy'          => 'ubGroups', //Enter Taxonomy Slug
            'type'              => 'taxonomy_select',
            'remove_default'    => 'true', 
            'query_args'        => array(
                // 'orderby' => 'slug',
                // 'hide_empty' => true,
            ),
        ) );

        $activities->add_field( array(
            'name'              => __( 'Class', unimarken_textDomain() ),
            'id'                => 'unimarken_class',
            'taxonomy'          => 'ubClasses', //Enter Taxonomy Slug
            'type'              => 'taxonomy_select',
            'remove_default'    => 'true', 
            'query_args'        => array(
                // 'orderby' => 'slug',
                // 'hide_empty' => true,
            ),
        ) );
    }
    add_action( 'cmb2_admin_init', 'unimarken_fields_activities' );
}

if ( !function_exists( 'unimarken_fields_actions' ) )
{
    function unimarken_fields_actions() {
        add_meta_box( 'unimarken_fields_actions', __( 'Actions', unimarken_textDomain() ), 'unimarken_actions_content', 'business', 'normal', 'low' );
    }
    add_action( 'add_meta_boxes', 'unimarken_fields_actions' );
}

if ( !function_exists( 'unimarken_actions_content' ) )
{
    function unimarken_actions_content( $post )
    {
        require unimarken_name_dir . 'templates/back/business_bags.php';
    }
}

if ( !function_exists( 'unimarken_actions_save' ) )
{
    function unimarken_actions_save( $post_id ) {
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['unimarken_actions_nonce'] ) || ! wp_verify_nonce( $_POST['unimarken_actions_nonce'], 'unimarken_actions_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }

        if ( isset( $_POST['bag'] ) && !empty( $_POST['bag'] ) && isset( $_POST['currency'] ) && !empty( $_POST['currency'] ) )
        {
            $isin_values = get_post_custom_values( 'unimarken_isin', $post_id );
            foreach ( $isin_values as $value ):
                delete_post_meta( $post_id, 'unimarken_isin', $value );
            endforeach;

            $ticker_values = get_post_custom_values( 'unimarken_ticker', $post_id );
            foreach ( $ticker_values as $value ):
                delete_post_meta( $post_id, 'unimarken_ticker', $value );
            endforeach;

            $ticker2_values = get_post_custom_values( 'unimarken_ticker2', $post_id );
            foreach ( $ticker2_values as $value ):
                delete_post_meta( $post_id, 'unimarken_ticker2', $value );
            endforeach;

            $relation   = [];
            $bags       = [];
            $currencies = [];
            $isins      = [];
            $tickers    = [];
            $tickers2   = [];

            for ( $i = 0; $i < count( $_POST['bag'] ); $i++ )
            { 
                $bag        = isset( $_POST['bag'][$i] )        ? sanitize_text_field( $_POST['bag'][$i] ) : '';
                $currency   = isset( $_POST['currency'][$i] )   ? sanitize_text_field( $_POST['currency'][$i] ) : '';
                $isin       = isset( $_POST['isin'][$i] )       ? sanitize_text_field( $_POST['isin'][$i] ) : '';
                $ticker     = isset( $_POST['ticker-1'][$i] )   ? sanitize_text_field( $_POST['ticker-1'][$i] ) : '';
                $ticker2    = isset( $_POST['ticker-2'][$i] )   ? sanitize_text_field( $_POST['ticker-2'][$i] ) : '';
                
                $bags[]         = $bag;
                $currencies[]   = $currency;
            
                $relation[] = array( 
                    'bag'       => $bag, 
                    'currency'  => $currency, 
                    'isin'      => $isin, 
                    'ticker'    => $ticker, 
                    'ticker2'   => $ticker2 
                );
            }

            foreach ( $relation as $value)
            {
                if( !empty( $value['isin'] ) ) add_post_meta( $post_id, 'unimarken_isin', $value['isin'] );
                if( !empty( $value['ticker'] ) ) add_post_meta( $post_id, 'unimarken_ticker', $value['ticker'] );
                if( !empty( $value['ticker2'] ) ) add_post_meta( $post_id, 'unimarken_ticker2', $value['ticker2'] );
            }

            wp_set_post_terms( $post_id, $bags, 'ubBags' );
            wp_set_post_terms( $post_id, $currencies, 'ubCurrency' );

            update_post_meta( $post_id, 'unimarken_relation_bag', $relation );  
        }
    }
    add_action( 'save_post', 'unimarken_actions_save' );
}

if ( !function_exists( 'unimarken_after_form_do_js_validation' ) )
{
    function unimarken_after_form_do_js_validation( $post_id, $cmb )
    {
        static $added = false;

        // Only add this to the page once (not for every metabox)
        if ( $added ) {
            return;
        }

        $added = true;
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {

            $form = $( document.getElementById( 'post' ) );
            $htmlbody = $( 'html, body' );
            $toValidate = $( '[data-validation]' );

            if ( ! $toValidate.length ) {
                return;
            }

            function checkValidation( evt ) {
                var labels = [];
                var $first_error_row = null;
                var $row = null;

                function add_required( $row ) {
                    $row.css({ 'background-color': 'rgb(255, 170, 170)' });
                    $first_error_row = $first_error_row ? $first_error_row : $row;
                    labels.push( $row.find( '.cmb-th label' ).text() );
                }

                function remove_required( $row ) {
                    $row.css({ background: '' });
                }

                $toValidate.each( function() {
                    var $this = $(this);
                    var val = $this.val();
                    $row = $this.parents( '.cmb-row' );

                    if ( $this.is( '[type="button"]' ) || $this.is( '.cmb2-upload-file-id' ) ) {
                        return true;
                    }

                    if ( 'required' === $this.data( 'validation' ) ) {
                        if ( $row.is( '.cmb-type-file-list' ) ) {

                            var has_LIs = $row.find( 'ul.cmb-attach-list li' ).length > 0;

                            if ( ! has_LIs ) {
                                add_required( $row );
                            } else {
                                remove_required( $row );
                            }

                        } else {
                            if ( ! val ) {
                                add_required( $row );
                            } else {
                                remove_required( $row );
                            }
                        }
                    }

                });

                if ( $first_error_row ) {
                    evt.preventDefault();
                    alert( '<?php _e( 'The following fields are required and highlighted below:', 'cmb2' ); ?> ' + labels.join( ', ' ) );
                    $htmlbody.animate({
                        scrollTop: ( $first_error_row.offset().top - 200 )
                    }, 1000);
                } 
            }

            $form.on( 'submit', checkValidation );
        });
        </script>
        <?php
    }
    add_action( 'cmb2_after_form', 'unimarken_after_form_do_js_validation', 10, 2 );
}

if ( !function_exists( 'unimarken_fields_term_country' ) )
{
    function unimarken_fields_term_country()
    {
        $country_term = new_cmb2_box( array(
            'id'               => 'unimarken_fields_term_country',
            'title'            => __( 'Flag', unimarken_textDomain() ),
            'object_types'     => array( 'term' ), 
            'taxonomies'       => array( 'ubCountries' ), 
            'new_term_section' => true, 
        ) );

       $country_term->add_field( array(
            'name' => esc_html__( 'Add URL country flag', unimarken_textDomain() ),
            'id'   => 'unimarken_country_flag',
            'type' => 'text_url',
            'protocols' => array( 'http', 'https' ),
        ) );

    }
    add_action( 'cmb2_admin_init', 'unimarken_fields_term_country' );
}

if ( !function_exists( 'unimarken_fields_term_currency' ) )
{
    function unimarken_fields_term_currency()
    {
        $currency_term = new_cmb2_box( array(
            'id'               => 'unimarken_fields_term_currency',
            'title'            => __( 'Symbols', unimarken_textDomain() ),
            'object_types'     => array( 'term' ), 
            'taxonomies'       => array( 'ubCurrency' ), 
            'new_term_section' => true, 
        ) );

       $currency_term->add_field( array(
            'name' => esc_html__( 'Add symbol', unimarken_textDomain() ),
            'id'   => 'unimarken_country_symbol',
            'type' => 'text_small',
        ) );

        $currency_term->add_field( array(
            'name' => esc_html__( 'Add symbol 2', unimarken_textDomain() ),
            'id'   => 'unimarken_country_symbol2',
            'type' => 'text_small',
        ) );
    }
    add_action( 'cmb2_admin_init', 'unimarken_fields_term_currency' );
}

if ( !function_exists( 'unimarken_fields_term_bags' ) )
{
    function unimarken_fields_term_bags()
    {
        $currency_term = new_cmb2_box( array(
            'id'               => 'unimarken_fields_term_bags',
            'title'            => __( 'URL Bags', unimarken_textDomain() ),
            'object_types'     => array( 'term' ), 
            'taxonomies'       => array( 'ubBags' ), 
            'new_term_section' => true, 
        ) );

       $currency_term->add_field( array(
            'name' => esc_html__( 'URL Bags', unimarken_textDomain() ),
            'id'   => 'unimarken_url_bag',
            'type' => 'text_url',
            'protocols' => array( 'http', 'https' ),
        ) );
    }
    add_action( 'cmb2_admin_init', 'unimarken_fields_term_bags' );
}

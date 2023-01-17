<?php

/*$tax = [];
$query = [];

$sCountries = isset( $_GET['ubCountry'] )   ? $tax['ubCountries']   = $_GET['ubCountry'] : '';
$sSections  = isset( $_GET['ubSection'] )   ? $tax['ubSections']    = $_GET['ubSection'] : ''; 
$sDivisions = isset( $_GET['ubDivision'] )  ? $tax['ubDivisions']   = $_GET['ubDivision'] : '';
$sGroups    = isset( $_GET['ubGroup'] )     ? $tax['ubGroups']      = $_GET['ubGroup'] : '';
$sClasses   = isset( $_GET['ubClass'] )     ? $tax['ubClasses']     = $_GET['ubClass'] : '';
$sBags      = isset( $_GET['ubBag'] )       ? $tax['ubBags']        = $_GET['ubBag'] : '';

$args = array(
    'post_type'         => 'business',
    'posts_per_page'    => -1,
    'fields'            => 'ids', // return an array of ids
);

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

$ubBags      = get_terms( array( 'taxonomy' => 'ubBags',         'object_ids'    => $someposts ) );
$ubCountries = get_terms( array( 'taxonomy' => 'ubCountries',    'object_ids'    => $someposts ) );
$ubSections  = get_terms( array( 'taxonomy' => 'ubSections',     'object_ids'    => $someposts ) );
$ubDivisions = get_terms( array( 'taxonomy' => 'ubDivisions',    'object_ids'    => $someposts ) );
$ubGroups    = get_terms( array( 'taxonomy' => 'ubGroups',       'object_ids'    => $someposts ) );
$ubClasses   = get_terms( array( 'taxonomy' => 'ubClasses',      'object_ids'    => $someposts ) );
?>

<div class="container-fluid">
    <h4><?= esc_html__( 'Filter by', unimarken_textDomain() ) ?></h4>
    <div class="row">
        <div class="col mb-3">
            <label for="business-country-m" class="form-label"><?= esc_html__( 'Country', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-country-m">

                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubCountries as $term ): ?>                 
                        <option value="<?= $term->term_id ?>" <?php selected( $sCountries, $term->term_id ); ?>><?= $term->name ?></option>
                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-section-m" class="form-label"><?= esc_html__( 'Section', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-section-m">

                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubSections as $term ): ?>                 
                        <option value="<?= $term->term_id ?>" <?php selected( $sSections, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-division-m" class="form-label"><?= esc_html__( 'Division', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-division-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubDivisions as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sDivisions, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-group-m" class="form-label"><?= esc_html__( 'Group', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-group-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubGroups as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sGroups, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-class-m" class="form-label"><?= esc_html__( 'Class', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-class-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubClasses as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sClasses, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-bag-m" class="form-label"><?= esc_html__( 'Stock exchange', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-bag-m">
                
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubBags as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sBags, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>
    </div>
</div>*/

$tax = [];
$query = [];

$sCountries = isset( $_GET['ubCountry'] )   ? $tax['ubCountries']   = $_GET['ubCountry'] : '';
$sSections  = isset( $_GET['ubSection'] )   ? $tax['ubSections']    = $_GET['ubSection'] : ''; 
$sDivisions = isset( $_GET['ubDivision'] )  ? $tax['ubDivisions']   = $_GET['ubDivision'] : '';
$sGroups    = isset( $_GET['ubGroup'] )     ? $tax['ubGroups']      = $_GET['ubGroup'] : '';
$sClasses   = isset( $_GET['ubClass'] )     ? $tax['ubClasses']     = $_GET['ubClass'] : '';
$sBags      = isset( $_GET['ubBag'] )       ? $tax['ubBags']        = $_GET['ubBag'] : '';

$args = array(
    'post_type'         => 'business',
    'posts_per_page'    => -1,
    'fields'            => 'ids',
    'orderby'           => 'ID',
    'order'             => 'ASC'
);

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

$ubBags      = get_terms( array( 'taxonomy' => 'ubBags',         'object_ids'    => $someposts ) );
$ubCountries = get_terms( array( 'taxonomy' => 'ubCountries',    'object_ids'    => $someposts ) );
$ubSections  = get_terms( array( 'taxonomy' => 'ubSections',     'object_ids'    => $someposts ) );
$ubDivisions = get_terms( array( 'taxonomy' => 'ubDivisions',    'object_ids'    => $someposts ) );
$ubGroups    = get_terms( array( 'taxonomy' => 'ubGroups',       'object_ids'    => $someposts ) );
$ubClasses   = get_terms( array( 'taxonomy' => 'ubClasses',      'object_ids'    => $someposts ) );
?>

<div class="container-fluid">
    <h4><?= esc_html__( 'Filter by', unimarken_textDomain() ) ?></h4>
    <div class="row">
        <div class="col mb-3">
            <label for="business-country-m" class="form-label"><?= esc_html__( 'Country', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-country-m">

                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubCountries as $term ): ?>                 
                        <option value="<?= $term->term_id ?>" <?php selected( $sCountries, $term->term_id ); ?>><?= $term->name ?></option>
                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-section-m" class="form-label"><?= esc_html__( 'Section', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-section-m">

                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubSections as $term ): ?>                 
                        <option value="<?= $term->term_id ?>" <?php selected( $sSections, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-division-m" class="form-label"><?= esc_html__( 'Division', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-division-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubDivisions as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sDivisions, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-group-m" class="form-label"><?= esc_html__( 'Group', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-group-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubGroups as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sGroups, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-class-m" class="form-label"><?= esc_html__( 'Class', unimarken_textDomain() ) ?></label>
            <select class="form-select heigh-45px" id="business-class-m">
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubClasses as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sClasses, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label for="business-bag-m" class="form-label"><?= esc_html__( 'Stock exchange', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-bag-m">
                
                <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubBags as $term ): ?>                 
                    <option value="<?= $term->term_id ?>" <?php selected( $sBags, $term->term_id ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>
    </div>
</div>


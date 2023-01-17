<?php
    global $wpdb;

    $ubCountries    = get_categories( array( 'taxonomy' => 'ubCountries', 'orderby' => 'title', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubSections     = get_categories( array( 'taxonomy' => 'ubSections',  'orderby' => 'ID',    'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubDivisions    = get_categories( array( 'taxonomy' => 'ubDivisions', 'orderby' => 'ID',    'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubGroups       = get_categories( array( 'taxonomy' => 'ubGroups',    'orderby' => 'ID',    'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubClasses      = get_categories( array( 'taxonomy' => 'ubClasses',   'orderby' => 'ID',    'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubBags         = get_categories( array( 'taxonomy' => 'ubBags',      'orderby' => 'title', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubCurrency     = get_categories( array( 'taxonomy' => 'ubCurrency',  'orderby' => 'title', 'order' => 'ASC', 'hide_empty'=> FALSE) );
?>

<!-- Row 1 -->
<div class="row">

    <div class="col-md-9 mb-3">

        <label for="business-name" class="form-label"><?= esc_html__( 'Business name', unimarken_textDomain() ) ?></label>

        <div class="row">
            <div class="col-md-4 mb-3">
                <select class="form-select heigh-45px" id="business-type-name">
                    <option value="0"><?= esc_html__( 'Latin characters', unimarken_textDomain() ) ?></option>
                    <option value="1"><?= esc_html__( 'Non Latin characters', unimarken_textDomain() ) ?></option>
                </select>
            </div>
            <div class="col-md-8 ps-md-0">
                <input type="text" class="form-control" id="business-name" value="">
            </div>
        </div>

    </div>

    <div class="col-md-3 mb-3">

        <label for="business-country" class="form-label"><?= esc_html__( 'Country', unimarken_textDomain() ) ?></label>
        
        <select class="form-select heigh-45px" id="business-country">

            <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubCountries as $term ): ?>                 
                    <option value="<?= $term->term_id ?>"><?= $term->name ?></option>
            <?php endforeach; ?>

        </select>
    </div>
</div>

<!-- Row 2 -->
<div class="row">

    <div class="col-md-3 mb-3">
        <label for="business-section" class="form-label"><?= esc_html__( 'Section', unimarken_textDomain() ) ?></label>
        <select class="form-select heigh-45px" id="business-section">
            <option value="" ><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>
            <?php foreach( $ubSections as $term ): ?>                 
                    <option value="<?= $term->term_id ?>"><?= $term->name ?></option>      
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label for="business-division" class="form-label"><?= esc_html__( 'Division', unimarken_textDomain() ) ?></label>
        
        <select class="form-select heigh-45px" id="business-division">

            <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubDivisions as $term ): ?>                 
                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>      
            <?php endforeach; ?>

        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label for="business-group" class="form-label"><?= esc_html__( 'Group', unimarken_textDomain() ) ?></label>
        
        <select class="form-select heigh-45px" id="business-group">
            
            <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubGroups as $term ): ?>                 
                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>      
            <?php endforeach; ?>

        </select>
    </div>

    <div class="col-md-3 mb-3">

        <label for="business-class" class="form-label"><?= esc_html__( 'Class', unimarken_textDomain() ) ?></label>
        
        <select class="form-select heigh-45px" id="business-class">
            
            <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubClasses as $term ): ?>                 
                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>      
            <?php endforeach; ?>

        </select>

    </div>

</div>

<!-- Row 3 -->
<div class="row">

    <div class="col-md-3 mb-3">

        <label for="business-bag" class="form-label"><?= esc_html__( 'Stock exchange', unimarken_textDomain() ) ?></label>
        
        <select class="form-select heigh-45px" id="business-bag" required>
        
            <option value=""><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubBags as $term ): ?>                 
                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>  
            <?php endforeach; ?>
            
        </select>

    </div>

    <div class="col-md-3 mb-3">
        <label for="business-isin" class="form-label"><?= esc_html__( 'ISIN', unimarken_textDomain() ) ?></label>
        
        <input type="text" class="form-control" id="business-isin" value="">
    </div>

    <div class="col-md-3 mb-3">

        <label for="business-ticker" class="form-label"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></label>

        <input type="text" class="form-control" id="business-ticker" value="">

    </div>

    <div class="col-md-3 mb-3">

        <label for="business-currency" class="form-label"><?= esc_html__( 'Currency', unimarken_textDomain() ) ?></label>

        <select class="form-select heigh-45px" id="business-currency">
            
            <option value="" ><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

            <?php foreach( $ubCurrency as $term ): ?>                 
                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>      
            <?php endforeach; ?>

        </select>
    </div>

</div>

<!-- Row 4 -->
<div class="row">

    <div class="col-md-6 mb-3">
        <button id="unimarken-reset-sb" class="d-flex justify-content-center align-items-center w-100"><?= esc_html__( 'Reset', unimarken_textDomain() ) ?></button>
    </div>

    <div class="col-md-6 mb-3">
        <button id="unimarken-search-sb" class="btn w-100">
            <?= esc_html__( 'Search', unimarken_textDomain() ) ?>
        </button>
    </div>

</div>

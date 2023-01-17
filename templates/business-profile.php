<?php
    global $wpdb;

    $ubCountries    = get_categories( array( 'taxonomy' => 'ubCountries',   'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubSections     = get_categories( array( 'taxonomy' => 'ubSections',    'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubDivisions    = get_categories( array( 'taxonomy' => 'ubDivisions',   'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubGroups       = get_categories( array( 'taxonomy' => 'ubGroups',      'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubClasses      = get_categories( array( 'taxonomy' => 'ubClasses',     'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubBags         = get_categories( array( 'taxonomy' => 'ubBags',        'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );
    $ubCurrency     = get_categories( array( 'taxonomy' => 'ubCurrency',    'orderby' => 'ID', 'order' => 'ASC', 'hide_empty'=> FALSE) );

    $company_id = unimarken_get_company_id();
    $title      = !empty( $company_id ) ?  esc_html( get_the_title( $company_id ) ) : '';

    $nameInt   = get_post_meta( $company_id, 'unimarken_business_name', true );
    $web       = get_post_meta( $company_id, 'unimarken_web', true );
    $comments  = get_post_meta( $company_id, 'unimarken_comments', true );
    //$symbol    = get_post_meta( $company_id, 'unimarken_symbol', true );
    //$symbolTwo = get_post_meta( $company_id, 'unimarken_symboltwo', true );
    $relation  = get_post_meta( $company_id, 'unimarken_relation_bag' );
?>

<form class="p-3 g-3 needs-validation" method="POST" enctype="multipart/form-data" id="form-business" novalidate>

    <div class="row my-5">
        <p class="h4"><?= esc_html__( 'Business', unimarken_textDomain() ) ?></p>

        <div class="col-md-6 mb-3">

            <label for="business-latin-name" class="form-label"><?= esc_html__( 'Name', unimarken_textDomain() ) ?></label>
            
            <input type="text" class="form-control" id="business-latin-name" value="<?= $title ?>" required>

            <div class="invalid-feedback">
                <?= esc_html__( 'Company name is required.', unimarken_textDomain() ) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-name" class="form-label"><?= esc_html__( 'Business name', unimarken_textDomain() ) ?></label>
            
            <input type="text" class="form-control" id="business-name" value="<?= $nameInt ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-country" class="form-label"><?= esc_html__( 'Country', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-country" required>

                <option disabled value="" <?php selected( unimarken_get_id_terms( 'ubCountries' ), '' ); ?>><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubCountries as $term ): ?>                 
                        <option value="<?= $term->slug ?>" <?php selected( unimarken_get_id_terms( $term->taxonomy ), $term->slug ); ?>><?= $term->name ?></option>
                <?php endforeach; ?>

            </select>

            <div class="invalid-feedback">
                <?= esc_html__( 'The country of the company is required.', unimarken_textDomain() ) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-web" class="form-label"><?= esc_html__( 'Web', unimarken_textDomain() ) ?></label>
            
            <input type="text" class="form-control" id="business-web" value="<?= $web ?>" required>
            
            <div class="invalid-feedback">
                <?= esc_html__( 'The web address of the company is required.', unimarken_textDomain() ) ?>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <label for="business-comments" class="form-label"><?= esc_html__( 'Comments', unimarken_textDomain() ) ?></label>
            
            <textarea class="form-control" id="business-comments"><?= $comments ?></textarea>
        </div>

        <div class="col-md-12 mb-3">
        
            <label class="form-label" for="business-logo"><?= esc_html__( 'Logo', unimarken_textDomain() ) ?></label>
            <input type="file" class="form-control" name="business-logo" id="business-logo" value="">
            
        </div>

        <div class="col-md-12 mb-3 d-flex justify-content-center">
        
            <?= get_the_post_thumbnail( $company_id, 'medium' ) ?>
            
        </div>
    </div>

    <div class="row mb-5">
        <p class="h4"><?= esc_html__( 'Activities', unimarken_textDomain() ) ?></p>

        <div class="col-md-6 mb-3">
            <label for="business-section" class="form-label"><?= esc_html__( 'Section', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-section" required>

                <option disabled value="" <?php selected( unimarken_get_id_terms( 'ubSections' ), '' ); ?>><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubSections as $term ): ?>                 
                        <option value="<?= $term->slug ?>" <?php selected( unimarken_get_id_terms( $term->taxonomy ), $term->slug ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>

            <div class="invalid-feedback">
                <?= esc_html__( 'The company section is required.', unimarken_textDomain() ) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-division" class="form-label"><?= esc_html__( 'Division', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-division" required>

                <option disabled value="" <?php selected( unimarken_get_id_terms( 'ubDivisions' ), '' ); ?>><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubDivisions as $term ): ?>                 
                    <option value="<?= $term->slug ?>" <?php selected( unimarken_get_id_terms( $term->taxonomy ), $term->slug ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>

            <div class="invalid-feedback">
                <?= esc_html__( 'The company division is required.', unimarken_textDomain() ) ?>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-group" class="form-label"><?= esc_html__( 'Group', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-group">
                
                <option value="" <?php selected( unimarken_get_id_terms( 'ubGroups' ), '' ); ?>><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubGroups as $term ): ?>                 
                    <option value="<?= $term->slug ?>" <?php selected( unimarken_get_id_terms( $term->taxonomy ), $term->slug ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="business-class" class="form-label"><?= esc_html__( 'Class', unimarken_textDomain() ) ?></label>
            
            <select class="form-select heigh-45px" id="business-class">
                
                <option value="" <?php selected( unimarken_get_id_terms( 'ubClasses' ), '' ); ?>><?= esc_html__( ' --- ', unimarken_textDomain() ) ?></option>

                <?php foreach( $ubClasses as $term ): ?>                 
                    <option value="<?= $term->slug ?>" <?php selected( unimarken_get_id_terms( $term->taxonomy ), $term->slug ); ?>><?= $term->name ?></option>      
                <?php endforeach; ?>

            </select>
        </div>
    </div>

    <div class="row mb-5">
        <p class="h4"><?= esc_html__( 'Actions', unimarken_textDomain() ) ?></p>

        <?php include unimarken_name_dir . '/templates/business_bags.php'; ?>
    </div>

    <div class="row mb-5">
        <div class="col-12 mb-5">
            <div class="form-check">
                
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    
                <label class="form-check-label" for="invalidCheck">
                    <?= esc_html__( 'I have read and accept ', unimarken_textDomain() ) . '<a href="/terms-and-conditions" target="_blank">' . esc_html__( 'the privacy policies', unimarken_textDomain() ) . '</a>' ?>
                </label>
                
                <div class="invalid-feedback">
                    <?= esc_html__( 'You must accept the privacy policies.', unimarken_textDomain() ) ?>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit" id="business-save"><?= esc_html__( 'Submit', unimarken_textDomain() ) ?></button>
        </div>
    </div>
</form>

<script>

    (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
        }, false)
    })
    })();

</script>


<?php
$ubBags     = get_categories( array( 'taxonomy' => 'ubBags', 'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );
$ubCurrency = get_categories( array( 'taxonomy' => 'ubCurrency', 'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE ) );
$relation   = get_post_meta( $post->ID, 'unimarken_relation_bag' );

?>

<div class="row mb-5" id="content-bags">
    <div class="mb-2">
        <a class="btn btn-primary text-bg-primary" id="add-bag"><?= esc_html__( 'Add another stock exchange', unimarken_textDomain() ) ?></a>
    </div>

    <?php 
    if( !empty( $relation[0] ) ):
        $i = 0;
        foreach( $relation[0] as $v ):
            ?>
            <div class="row mb-3" row="bag">
                <div class="col-12 mb-2">
                    <label class="form-label"><?= esc_html__( 'Stock exchange', unimarken_textDomain() ) ?></label>
                    <select class="form-select heigh-45px" name="bag[]" dataId="bag" required>
                        
                        <option value="" ><?= esc_html__( 'Select', unimarken_textDomain() ) ?></option>

                        <?php foreach( $ubBags as $term ): ?>                 
                            <option value="<?= $term->slug ?>" <?php selected( $v['bag'], $term->slug ); ?>><?= $term->name ?></option>  
                        <?php endforeach; ?>
                        
                    </select>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                    <label class="form-label"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></label>
                        
                    <input type="text" class="form-control" name="ticker-1[]" dataId="ticker-1" value="<?= $v['ticker'] ?>">
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                    <label class="form-label"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></label>
                        
                    <input type="text" class="form-control" name="ticker-2[]" dataId="ticker-2" value="<?= $v['ticker2'] ?>">
                </div>

                <div class="col-sm-4 col-md-4 col-lg-2 mb-2">
                    <label class="form-label"><?= esc_html__( 'ISIN', unimarken_textDomain() ) ?></label>
                        
                    <input type="text" class="form-control" name="isin[]" dataId="isin" value="<?= $v['isin'] ?>">
                </div>

                <div class="col-sm-4 col-md-6 col-lg-2 mb-2">
                    <label class="form-label"><?= esc_html__( 'Currency', unimarken_textDomain() ) ?></label>
                        
                    <select class="form-select heigh-45px" name="currency[]" dataId="currency" required>
                        
                        <option value="" <?php selected( unimarken_get_id_terms( 'ubCurrency' ), '' ); ?>><?= esc_html__( 'Select', unimarken_textDomain() ) ?></option>

                        <?php foreach( $ubCurrency as $term ): ?>                 
                            <option value="<?= $term->slug ?>" <?php selected( $v['currency'], $term->slug ); ?>><?= $term->name ?></option>      
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-sm-4 col-md-6 col-lg-2 mb-2 d-flex flex-column justify-content-end align-items-center">
                    
                    <?php
                    if( $i !== 0 ):
                        ?>
                        <a class="btn btn-danger text-bg-primary heigh-45px w-100 delete-bag">X</a>
                        <?php
                    endif;
                    ?>

                </div>
            </div>

            <?php
            $i++;
        endforeach;

    else:

        ?>
        <div class="row mb-3" row="bag">
            <div class="col-12 mb-2">
                <label class="form-label"><?= esc_html__( 'Bag', unimarken_textDomain() ) ?></label>
                <select class="form-select heigh-45px" name="bag[]" dataId="bag" required>
                    
                    <option value=""><?= esc_html__( 'Select', unimarken_textDomain() ) ?></option>

                    <?php foreach( $ubBags as $term ): ?>                 
                        <option value="<?= $term->slug ?>"><?= $term->name ?></option>  
                    <?php endforeach; ?>
                    
                </select>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                <label class="form-label"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></label>
                    
                <input type="text" class="form-control" name="ticker-1[]" dataId="ticker-1" value="">
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                <label class="form-label"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></label>
                    
                <input type="text" class="form-control" name="ticker-2[]" dataId="ticker-2" value="">
            </div>

            <div class="col-sm-4 col-md-4 col-lg-2 mb-2">
                <label class="form-label"><?= esc_html__( 'ISIN', unimarken_textDomain() ) ?></label>
                    
                <input type="text" class="form-control" name="isin[]" dataId="isin" value="">
            </div>

            <div class="col-sm-4 col-md-6 col-lg-2 mb-2">
                <label class="form-label"><?= esc_html__( 'Currency', unimarken_textDomain() ) ?></label>
                    
                <select class="form-select heigh-45px" name="currency[]" dataId="currency" required>
                    
                    <option value=""><?= esc_html__( 'Select', unimarken_textDomain() ) ?></option>

                    <?php foreach( $ubCurrency as $term ): ?>                 
                        <option value="<?= $term->slug ?>"><?= $term->name ?></option>      
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-sm-4 col-md-6 col-lg-2 mb-2"></div>
        </div>
        <?php
        
    endif;
    ?>
    <input type="hidden" name="unimarken_actions_nonce" value="<?= wp_create_nonce( 'unimarken_actions_nonce' ) ?>">
</div>
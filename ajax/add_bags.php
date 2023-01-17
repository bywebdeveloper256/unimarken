<?php

global $wpdb;

        $ubBags     = get_categories( array( 'taxonomy' => 'ubBags',        'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE) );
        $ubCurrency = get_categories( array( 'taxonomy' => 'ubCurrency',    'orderby' => 'menu_order', 'order' => 'ASC', 'hide_empty'=> FALSE) );

        $html = '<div class="row mb-3" row="bag">
                <div class="col-12 mb-2">
                    <label class="form-label">' . esc_html__( 'Bag', unimarken_textDomain() ) . '</label>
                    <select class="form-select heigh-45px" name="bag[]" dataId="bag" required>
                        
                        <option value="">' . esc_html__( 'Select', unimarken_textDomain() ) . '</option>';

                        foreach( $ubBags as $term ):                
                            $html .= '<option value="'. $term->slug .'">'. $term->name .'</option>';  
                        endforeach;
                        
        $html .= '</select>
                    <div class="invalid-feedback">' .
                         esc_html__( 'The bag of the company is required.', unimarken_textDomain() ) .
                    '</div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                    <label class="form-label">' . esc_html__( 'Ticker', unimarken_textDomain() ) . '</label>
                        
                    <input type="text" class="form-control" name="ticker-1[]" dataId="ticker-1" value="">
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                    <label class="form-label">' . esc_html__( 'Ticker', unimarken_textDomain() ) . '</label>
                        
                    <input type="text" class="form-control" name="ticker-2[]" dataId="ticker-2" value="">
                </div>

                <div class="col-sm-4 col-md-4 col-lg-2 mb-2">
                    <label class="form-label">' . esc_html__( 'ISIN', unimarken_textDomain() ) . '</label>
                        
                    <input type="text" class="form-control" name="isin[]" dataId="isin" value="">
                </div>

                <div class="col-sm-4 col-md-6 col-lg-2 mb-2">
                    <label class="form-label">'. esc_html__( 'Currency', unimarken_textDomain() ) .'</label>
                        
                    <select class="form-select heigh-45px" name="currency[]" dataId="currency" required>
                        
                        <option value="">'. esc_html__( 'Select', unimarken_textDomain() ) .'</option>';

                        foreach( $ubCurrency as $term ):
                            $html .= '<option value="'. $term->slug .'">'. $term->name .'</option>';
                        endforeach;

        $html .= '</select>

                    <div class="invalid-feedback">' .
                         esc_html__( 'The currency of the company is required.', unimarken_textDomain() ) .
                    '</div>
                </div>
                
                <div class="col-sm-4 col-md-6 col-lg-2 mb-2 d-flex flex-column justify-content-end align-items-center">
                    <a class="btn btn-danger text-bg-primary heigh-45px w-100 delete-bag">X</a>
                </div>
            </div>';

            $r['r'] = true;
            $r['html'] = $html;
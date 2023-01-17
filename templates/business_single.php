<?php

$web = get_post_meta( get_the_ID(), 'unimarken_web', true );

$termCountries  = get_the_terms( get_the_ID(), 'ubCountries' );
$termSections   = get_the_terms( get_the_ID(), 'ubSections' );
$termDivisions  = get_the_terms( get_the_ID(), 'ubDivisions' );
$termGroups     = get_the_terms( get_the_ID(), 'ubGroups' );
$termClasses    = get_the_terms( get_the_ID(), 'ubClasses' );
$relation       = get_post_meta( get_the_ID(), 'unimarken_relation_bag' );

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 p-5 d-flex justify-content-center align-items-center content-logo">
            <img src="<?= get_the_post_thumbnail_url() ?> " alt="<?= esc_html__( get_the_title(), unimarken_textDomain() ) ?>" title="<?= esc_html__( get_the_title(), unimarken_textDomain() ) ?>">
        </div>

        <div class="col-md-6">

            <h1><?= get_post_meta( get_the_ID(), 'unimarken_business_name', true ) ?></h1>

            <h2><?= esc_html__( get_the_title(), unimarken_textDomain() ) ?></h2>

            <h5><?= esc_html__( 'Web: ', unimarken_textDomain() ) ?><a href="<?= $web ?>" target="_blank"><?= $web ?></a></h5>

            <div class="col d-flex align-items-center">
                <img class="sizeflags" src="<?= get_term_meta( $termCountries[0]->term_id, 'unimarken_country_flag', true ) ?> " alt="<?= $termCountries[0]->name ?>" title="<?= $termCountries[0]->name ?>">
                <span class="ms-1"><?= $termCountries[0]->name ?></span>
            </div>

            <h5 class="mt-3 mb-1"><?= esc_html__( 'Comments:', unimarken_textDomain() ) ?></h5>

            <p><?= get_post_meta( get_the_ID(), 'unimarken_comments', true ) ?></p>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Section', unimarken_textDomain() ) ?></h5>

            <?php if( !empty( $termSections ) ):
                foreach( $termSections as $Section ) : echo '<p class="text-center">' . $Section->name . '</p>'; endforeach; 
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Division', unimarken_textDomain() ) ?></h5>
            
            <?php if( !empty( $termDivisions ) ):
                foreach( $termDivisions as $Division ) : echo '<p class="text-center">' . $Division->name . '</p>'; endforeach; 
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Group', unimarken_textDomain() ) ?></h5>
            
            <?php if( !empty( $termGroups ) ):
                foreach( $termGroups as $Group ) : echo '<p class="text-center">' . $Group->name . '</p>'; endforeach; 
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Class', unimarken_textDomain() ) ?></h5>
            
            <?php if( !empty( $termClasses ) ):
                foreach( $termClasses as $Class ) : echo '<p class="text-center">' . $Class->name . '</p>'; endforeach;
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Stock exchange', unimarken_textDomain() ) ?></h5>
            
            <?php if( !empty( $relation[0] ) ):
                foreach( $relation[0] as $v ) : 
                    $term = get_term_by('slug', $v['bag'], 'ubBags');
                    echo '<p class="text-center">' . $term->name . '</p>'; 
                endforeach;
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></h5>

            <?php if( !empty( $relation[0] ) ):
                foreach( $relation[0] as $v ) : 
                    echo '<p class="text-center">' . $v['ticker'] . '</p>'; 
                endforeach;
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Ticker', unimarken_textDomain() ) ?></h5>

            <?php if( !empty( $relation[0] ) ):
                foreach( $relation[0] as $v ) : 
                    echo '<p class="text-center">' . $v['ticker2'] . '</p>'; 
                endforeach;
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'ISIN', unimarken_textDomain() ) ?></h5>

            <?php if( !empty( $relation[0] ) ):
                foreach( $relation[0] as $v ) : 
                    echo '<p class="text-center">' . $v['isin'] . '</p>'; 
                endforeach;
            endif; ?>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-2 mb-3">
            <h5 class="text-center border border-dark"><?= esc_html__( 'Currency', unimarken_textDomain() ) ?></h5>

            <?php if( !empty( $relation[0] ) ):
                foreach( $relation[0] as $v ) : 
                    $term = get_term_by('slug', $v['currency'], 'ubCurrency');
                    echo '<p class="text-center">' . $term->name . '</p>'; 
                endforeach;
            endif; ?>
        </div>
    </div>
</div>
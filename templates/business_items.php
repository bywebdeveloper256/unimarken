<div class="card mb-2">
    
    <div class="card-body d-flex flex-column justify-content-center">
        <div class="row">

            <div class="col-sm-3 d-flex justify-content-center align-items-center">
                <a href="<?= the_permalink() ?>" >
                    <img class="" src="<?= get_the_post_thumbnail_url() ?> " alt="<?= esc_html__( get_the_title(), unimarken_textDomain() ) ?>" title="<?= esc_html__( get_the_title(), unimarken_textDomain() ) ?>">
                </a>
            </div>

            <div class="col-sm-9 d-flex flex-column justify-content-between p-4">
                <div class="row">
                    <p><a href="<?= the_permalink() ?>" ><?= get_post_meta( get_the_ID(), 'unimarken_business_name', true ) ?></a></p>
                </div>
                <div class="row">
                    <p><a href="<?= the_permalink() ?>" ><?= esc_html__( get_the_title(), unimarken_textDomain() ) ?></a></p>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <img class="sizeflags" src="<?= get_term_meta( $termCountries[0]->term_id, 'unimarken_country_flag', true ) ?> " alt="<?= $termCountries[0]->name ?>" title="<?= $termCountries[0]->name ?>">
                        <span class="ms-1"><?= $termCountries[0]->name ?></span>
                    </div>
                    <div class="col d-flex align-items-center">#<?= get_the_ID() ?></div>
                </div>
            </div>
        </div>
    </div>
</div>


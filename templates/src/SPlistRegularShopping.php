<?php

namespace EskofrizImport;

class SPlistRegularShopping{

    public function init()
    {
        add_filter( 'woocommerce_account_menu_items',                   array( $this, 'sp_log_history_link' ), 40 );
        add_action( 'init',                                             array( $this, 'sp_add_endpoint' ) );
        add_action( 'woocommerce_account_compras-habituales_endpoint',  array( $this, 'sp_my_account_endpoint_content' ) );
    }
   
    function sp_log_history_link( $menu_links )
    {	
        $menu_links = array_slice( $menu_links, 0, 5, true ) 
        + array( 'compras-habituales' => 'Compras habituales' )
        + array_slice( $menu_links, 5, NULL, true );
        
        return $menu_links;
    }
    
    function sp_add_endpoint()
    {
        // WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
        add_rewrite_endpoint( 'compras-habituales', EP_PAGES );
    }
   
    function sp_my_account_endpoint_content()
    { ?>
        <style>
            .img-style_product{
                border-radius: 6px !important;
                width: 50px;
            }
            .quantity_input_style{
                width: 65px !important;
                padding: 5px !important;
            }

            a {
                text-decoration: none;
                color: #000000;
            }

            a:hover {
                color: #222222
            }

            /* Dropdown */

            .dropdown {
                display: inline-block;
                position: relative;
            }

            .dd-button {
                display: inline-block;
                padding: 5px 20px 5px 20px;
                background-color: #ffffff;
                cursor: pointer;
                white-space: nowrap;
                font-family: 'Intro Regular';
                font-style: normal;
                font-weight: 400;
                font-size: 17px;
                line-height: 20px;
                color: #1E354E;
                border: none;
            }

            .dd-button:after {
                content: '';
                position: absolute;
                top: 50%;
                right: 5px;
                transform: translateY(-50%);
                width: 0; 
                height: 0; 
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 5px solid white;
            }

            .dd-button:hover {
                background-color: #eeeeee;
            }

            .dd-input {
                display: none;
            }
            .dd-button-a{
                padding: 6px 32px !important;
                font-family: 'Intro Regular' !important;
                font-style: normal !important;
                font-weight: 400 !important;
                font-size: 17px !important;
                line-height: 20px !important;
                color: #1E354E !important;
                border: none !important;
            }
            .dd-menu {
                position: absolute;
                top: 100%;
                border: 1px solid #ccc;
                border-radius: 4px;
                padding: 0;
                margin: 2px 0 0 0;
                box-shadow: 0 0 6px 0 rgba(0,0,0,0.1);
                background-color: #ffffff;
                list-style-type: none;
            }

            .dd-input + .dd-menu {
                display: none;
            } 

            .dd-input:checked + .dd-menu {
                display: block;
                    z-index: 999;
            } 

            .dd-menu li {
                padding: 10px 20px;
                cursor: pointer;
                white-space: nowrap;
            }

            .dd-menu li:hover {
                background-color: #f6f6f6;
            }

            .dd-menu li a {
                display: block;
                margin: -10px -20px;
                padding: 10px 20px;
            }

            .dd-menu li.divider{
                padding: 0;
                border-bottom: 1px solid #cccccc;
            }
                
            .text-custom-style {
                font-size: 14px !important;
            }

            .img-sin-stock {
                text-align: center;
            }
        </style>

        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details list_orders_erp">

            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">SKU</th>
                    <th class="woocommerce-table__product-name product-name">Imagen</th>
                    <th class="woocommerce-table__product-table product-total">Nombre del producto</th>
                    <th class="woocommerce-table__product-table product-total">Cantidad</th>
                    <th class="woocommerce-table__product-table product-total">Precio</th>
                    <th class="woocommerce-table__product-table product-total">Acciones</th>
                </tr>
            </thead>

            <tbody class="list-product-order">

                <?php global $wpdb;

                $tabla          = "{$wpdb->prefix}sp_orders_hab_erp";

                $list_info_user = get_the_author_meta( 'aditional_info_user', get_current_user_id() );

                $code_user      = isset($list_info_user['Código cliente']) ? $list_info_user['Código cliente'] : '1234';
                
                $query          = "SELECT *  FROM $tabla WHERE $tabla.cli_cod = $code_user";

                $list           = $wpdb->get_results( $query,ARRAY_A );

                if( empty( $list ) )
                {
                    echo '<tr class="woocommerce-table__line-item order_item"><td class="woocommerce-table__product-name product-name">';
                    echo 'No hay productos en lista de compras habituales';
                    echo '</td></tr>';
                }
                
                $count_nex      = isset( $_POST['next-art'] ) ? $_POST['next-art'] : 10;

                $count_prev     = $count_nex - 10;

                $data_product   = [];

                for( $i=0; $i < count( $list ); $i++ ):

                    if( empty( $list[$i] ) )
                    {
                        continue;
                    }

                    $id_product = wc_get_product_id_by_sku( $list[$i]['cod_art'] );

                    if( $id_product == 0 )
                    {
                        continue;
                    }

                    $product    = wc_get_product( $id_product );
                    $price      = number_format( $product->get_price(), 2 );

                    $title      = isset( $id_product )                  ? get_the_title( $id_product ): '';
                    $permalink  = isset( $id_product )                  ? get_permalink( $id_product ): '';
                    $code_art   = empty( $list[$i]['cod_art'])          ?  '' :  $list[$i]['cod_art'];
                    $num_uni    = empty( $list[$i]['num_uni'])          ?  '' :  $list[$i]['num_uni'];
                    $uni_cod    = empty( $list[$i]['uni_cod'])          ?  '' :  $list[$i]['uni_cod'];
                    $disabled   = $product->get_stock_quantity() > 0    ? true : false;
                    
                    $data_product[] = array(
                        'id'            => $id_product,
                        'title'         => $title,
                        'url'           => $permalink,
                        'price'         => $price,
                        'sku'           => $code_art,
                        'num_uni'       => $num_uni,
                        'uni_cod'       => $uni_cod,
                        'quantity'      => $product->get_stock_quantity(),
                        'disabled'      => $disabled,
                        'upsell_ids'    => $product->get_upsell_ids(),
                    );

                endfor;
               
                for( $i = $count_prev; $i < $count_nex; $i++ ):

                    if( empty( $data_product[$i] ) )
                    {
                        continue;
                    }		

                    $title_buttom   = empty( $data_product[$i]['disabled'] ) ? 'Sin stock' : 'Añadir al carrito';
                    $product        = wc_get_product( $data_product[$i]['id'] );
                    $image          = wp_get_attachment_image_src( get_post_thumbnail_id( $data_product[$i]['id'] ) ); 
                    $data           = $data_product[$i]['upsell_ids']; ?>
                        
                    <tr class="woocommerce-table__line-item order_item">

                        <td class="woocommerce-table__product-name product-name">
                            <?= $data_product[$i]['sku']; ?>
                        </td>

                        <td class="woocommerce-table__product-name product-name">
                            <a href="<?= $data_product[$i]['url']; ?>">
                                <img class="img-style_product" src="<?= $image[0]; ?>"/>
                            </a>
                        </td>

                        <td class="woocommerce-table__product-name product-name">
                            <a href="<?= $data_product[$i]['url']; ?>">
                                <?= $data_product[$i]['title']; ?>
                            </a>
                        </td>

                        <td class="woocommerce-table__product-total product-total total">

                            <?php if( $data_product[$i]['disabled'] ): ?>
                                <input class="quantity_input_style" id="value-quantity-<?= $data_product[$i]['id']; ?>" type="number" value="<?= $data_product[$i]['num_uni']; ?>" min="1" max="<?= $data_product[$i]['quantity'];?>"/> 
                                <?php else: ?>
                                    <div class="img-sin-stock"> <img src="/wp-content/uploads/2022/06/sin-stock.png"> </div>                                    
                            <?php endif;?>

                        </td>

                        <td class="woocommerce-table__product-total product-total"><?= $data_product[$i]['price']; ?> €	</td>

                        <td class="woocommerce-table__product-total product-total product_alt_btn">

                            <?php if( !empty( $data_product[$i]['disabled'] ) ): ?>

                                <button id="quantity-product-<?= $data_product[$i]['id']; ?>" data-quantity="<?= $data_product[$i]['num_uni']; ?>" class="btn woocommerce-button btn-general button view single_add_to_cart_button_custom" data-product_id="<?= $data_product[$i]['id']; ?>" data-product_sku="" aria-label="Añade '<?= $data_product[$i]['title']; ?>' a tu carrito" rel="nofollow"><?= $title_buttom; ?></button>
                            <?php else:

                                if( count( $data ) > 0 ): ?>
                                    
                                    <button class="btn-sin-stock btn woocommerce-button btn-general button view" id="btn-alternativas">Ver alternativas</button>
                                <?php else: ?>

                                    <button class="btn-sin-stock btn woocommerce-button btn-general button view single_add_to_cart_button_custom" rel="nofollow" disabled>Sin stock</button>
                                <?php endif;

                            endif; ?>
                            
                            <script>
                                
                                try{
                                    var selectElement_<?= $data_product[$i]['id']; ?> = document.getElementById( 'value-quantity-<?= $data_product[$i]['id']; ?>' );

                                    selectElement_<?= $data_product[$i]['id']; ?>.addEventListener( 'change', () => {

                                        var value_quantity_<?= $data_product[$i]['id']; ?> = selectElement_<?= $data_product[$i]['id']; ?>.value;

                                        var quantity_product_<?= $data_product[$i]['id']; ?> = document.getElementById( 'quantity-product-<?= $data_product[$i]['id']; ?>' );

                                            quantity_product_<?= $data_product[$i]['id']; ?>.setAttribute( "data-quantity", value_quantity_<?= $data_product[$i]['id']; ?> );
                                    });

                                }catch{
                                    
                                }
                            
                            </script>
                        </td>

                    </tr>

                    <?php if( count( $data ) > 0 ): ?>

                        <?php foreach( $data as $id_product_alt ){ 

                            $product_alt        = wc_get_product( $id_product_alt ); 
                            $img_product_alt    = wp_get_attachment_image_src( get_post_thumbnail_id( $id_product_alt ) );
                            
                            ?>

                            <tr class="woocommerce-table__line-item order_item product_alt">

                                <td class="woocommerce-table__product-name product-name bg-product_alt">
                                    <a href=""></a><?= $product_alt->get_sku(); ?>
                                </td>

                                <td class="woocommerce-table__product-name product-name bg-product_alt">
                                    <a href="<?= get_the_permalink( $id_product_alt ); ?>">
                                        <img class="img-style_product" src="<?= $img_product_alt[0];  ?>"/>
                                    </a>
                                </td>

                                <td class="woocommerce-table__product-name product-name bg-product_alt">
                                    <a href="<?= get_the_permalink( $id_product_alt ); ?>">
                                        <?= get_the_title( $id_product_alt ); ?>
                                    </a>
                                </td>

                                <td class="woocommerce-table__product-total product-total total bg-product_alt">

                                    <input class="quantity_input_style" id="value-quantity-<?= $id_product_alt ?>" type="number" value="<?= $data_product[$i]['num_uni']; ?>" min="1" max="<?= $product_alt->get_stock_quantity(); ?>" />

                                </td>

                                <td class="woocommerce-table__product-total product-total product_alt_price bg-product_alt"><?= number_format( $product_alt->get_price(), 2 ); ?> €	</td>

                                <td class="woocommerce-table__product-total product-total bg-product_alt">

                                    <button id="quantity-product-<?= $id_product_alt ?>" data-quantity="<?= $data_product[$i]['num_uni']; ?>" class="btn woocommerce-button btn-general button view single_add_to_cart_button_custom" data-product_id="<?= $id_product_alt ?>" data-product_sku="" aria-label="Añade '<?= get_the_title( $id_product_alt ); ?>' a tu carrito" rel="nofollow">Añadir al carrito</button>

                                    <script>
                                        try{
                                            var selectElement_<?= $id_product_alt ?> = document.getElementById( 'value-quantity-<?= $id_product_alt ?>' );

                                            selectElement_<?= $id_product_alt ?>.addEventListener( 'change', () => {

                                                var value_quantity_<?= $id_product_alt ?> = selectElement_<?= $id_product_alt ?>.value;

                                                var quantity_product_<?= $id_product_alt ?> = document.getElementById( 'quantity-product-<?= $id_product_alt ?>' );

                                                    quantity_product_<?= $id_product_alt ?>.setAttribute( "data-quantity", value_quantity_<?= $id_product_alt ?> );
                                            });
                                        }catch{
                                
                                        }
                                    
                                    </script>
                                    
                                </td>
                                
                            </tr>
                        
                        <?php } ?>

                    <?php endif; ?>

                <?php endfor; ?>

		    </tbody>

	    </table>

        <div class="flex-btn-custom">

            <?php if( $count_nex > 10 ): ?>
    
                <form action="" method="post" style="width: 135px;">
                    <input type="hidden" name="next-art" value="<?= $count_nex - 10; ?>">
                    <button class="btn-general" type="submit" name="next-btn">Anterior</button>
                </form>
    
            <?php endif;
           
            if( $count_nex < count( $data_product ) ): ?>

                <form action="" method="post" style="width: 135px;">
                    <input type="hidden" name="next-art" value="<?= $count_nex + 10; ?>">
                    <button class="btn-general" type="submit" name="next-btn">Siguiente</button>
                </form>

            <?php endif; ?>

        </div>

    <?php }
}

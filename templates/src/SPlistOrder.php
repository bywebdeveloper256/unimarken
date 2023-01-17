<?php

namespace EskofrizImport;

class SPlistOrder{

    public function init(){
        add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'sp_add_order_again_aciton'), 10, 2 );
        add_action( 'woocommerce_after_account_orders',         array( $this, 'sp_some_content_after') );
        add_action( 'woocommerce_after_account_orders',         array( $this, 'sp_details_order_after') );
        add_filter( 'woocommerce_account_menu_items',           array( $this, 'sp_rename_downloads') );
        add_action( 'wp_loaded',                                array( $this, 'sp_add_multiple_products_to_cart'), 15 );
        add_action( 'wp_footer',                                array( $this, 'add_to_cart_ajax_custom') );
        add_action( 'wp_ajax_nopriv_sp_add_to_cart_custom',     array( $this, 'sp_add_to_cart_custom') );
        add_action( 'wp_ajax_sp_add_to_cart_custom',            array( $this, 'sp_add_to_cart_custom') );
    }

    function sp_rename_downloads( $menu_links ){
        
        // $menu_links['TAB ID HERE'] = 'NEW TAB NAME HERE';
        $menu_links['orders'] = 'Historial';

        return $menu_links;
    }

    function sp_some_content_after(){

        ?>
        <style>
            .woocommerce-MyAccount-orders{
                display: none;
            }
            .woocommerce-button.woocommerce-button--next.woocommerce-Button.woocommerce-Button--next.button{
                display: none;
            }
        </style>

        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details list_orders_erp"> 
            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">Pedido</th>
                    <th class="woocommerce-table__product-table product-total">Fecha</th>
                    <th class="woocommerce-table__product-table product-total">Estado</th>
                    <th class="woocommerce-table__product-table product-total">Total</th>
                    <th class="woocommerce-table__product-table product-total">Acciones</th>
                </tr>
            </thead>

            <tbody class="list-product-order">

                <?php
                    global $wpdb;

                    $tabla          = "{$wpdb->prefix}sp_orders_cab_erp";

                    $list_info_user = get_the_author_meta( 'aditional_info_user', get_current_user_id() );

                    $code_user      = isset($list_info_user['Código cliente']) ? $list_info_user['Código cliente'] : '1234';
                    
                    //  $query = "SELECT *  FROM $tabla WHERE $tabla.cli_cod = '7431'";
                    $query  = "SELECT *  FROM $tabla WHERE $tabla.cli_cod = '%$code_user%'";
                    $list   = $wpdb->get_results($query,ARRAY_A);

                    if(empty( $list )){
                        echo '<tr class="woocommerce-table__line-item order_item"><td class="woocommerce-table__product-name product-name">';
                        echo 'No tiene ordenes registradas en este momento';
                        echo '</td></tr>';
                    }
                ?>

                <?php for($i=0; $i<count($list); $i++):?>

                    <tr class="woocommerce-table__line-item order_item">
                        <form action="" method="get">
                            <td class="woocommerce-table__product-name product-name"><a href=""></a><?php echo $list[$i]['num_ser'];?></td>
                            <td class="woocommerce-table__product-name product-name"><a href=""></a><?php echo date("Y-m-d", strtotime($list[$i]['dia_ped']));?></td>
                            <td class="woocommerce-table__product-total product-total total">Completado</td>
                            <td class="woocommerce-table__product-total product-total"><?php echo $list[$i]['val_imp'];?> €	</td>
                            <td class="woocommerce-table__product-total product-total">
                                <input type="hidden" value="<?php echo $list[$i]['num_ser'];?>" name="order-again">
                                <button  class="btn btn-primary">Ver</button>
                            </td>
                        </form>
                    </tr>

                <?php endfor; ?>

            </tbody>
            
        </table>

        <?php
    }

    function sp_details_order_after(){

        if(!isset($_GET['order-again'])){
            return false;
        }

        $order_id = $_GET['order-again'];

        ?>
        <style>
            .woocommerce-MyAccount-orders, .list_orders_erp{
                display: none;
            }
            .woocommerce-button.woocommerce-button--next.woocommerce-Button.woocommerce-Button--next.button{
                display: none;
            }
        </style>

        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">Agregar todos los productos al carrito</th>
                    <th class="woocommerce-table__product-table product-total"><a id="add_to_cart_button_custom_all"  class="woocommerce-button button view add_to_cart_button_custom_all">Añadir todos </a></th>
                </tr>
            </thead>
        </table>
            
        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details"> 
            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">SKU</th>
                    <th class="woocommerce-table__product-table product-total">Nombre del producto</th>
                    <th class="woocommerce-table__product-table product-total">Cantidad</th>
                    <th class="woocommerce-table__product-table product-total">Precio</th>
                    <th class="woocommerce-table__product-table product-total">Acciones</th>
                </tr>
            </thead>

            <tbody class="list-product-order">

                <?php
                    global $wpdb;

                    $tabla = "{$wpdb->prefix}sp_orders_lin_erp";

                    $query = "SELECT *  FROM $tabla WHERE $tabla.num_ser = $order_id";

                    $list = $wpdb->get_results($query,ARRAY_A);
                ?>

                <?php for( $i = 0; $i < count( $list ); $i++ ):
                    
                    $id_product = wc_get_product_id_by_sku( $list[$i]['cod_art'] );

                    if( $id_product == 0 ){
                        continue;
                    }

                    $title      = isset( $id_product )  ? get_the_title($id_product)    : 'no stock';
                    $permalink  = isset( $id_product )  ? get_permalink($id_product)    : '';
                    
                    $product        = wc_get_product( $id_product );
                    $price          = number_format( $product->get_price(), 2 );
                    $title_buttom   = $product->get_stock_quantity() > 0 ? 'Añadir al carrito' : 'Sin stock';

                    ?>
                    <tr class="woocommerce-table__line-item order_item">

                        <td class="woocommerce-table__product-name product-name"><a href=""></a> <?php echo $list[$i]['cod_art']; ?></td>
                        <td class="woocommerce-table__product-name product-name"><a href=" <?php echo $permalink; ?>"><?php echo $title; ?></a></td>
                        <td class="woocommerce-table__product-total product-total total"> <?php echo $list[$i]['num_uni']; ?></td>
                        <td class="woocommerce-table__product-total product-total"> <?php echo $price; ?> € </td>
                        <td class="woocommerce-table__product-total product-total">
                            <button data-quantity="<?php echo $list[$i]['num_uni'];?>"  <?php if($product->get_stock_quantity() <= 0){echo "style='    width: 135px;     background: #a1a1a19c; pointer-events: none; '";}?> class="woocommerce-button button view single_add_to_cart_button_custom" data-product_id="<?php echo $id_product; ?>" data-product_sku="" aria-label="Añade “<?php echo $title; ?>” a tu carrito" rel="nofollow"><?php echo $title_buttom; ?></button>        
                        </td>
                    </tr>

                <?php endfor; ?>

            </tbody>

        </table>

        <?php
    }

    function add_to_cart_ajax_custom(){

        // $host = $_SERVER["HTTP_HOST"];
        // $url = $_SERVER["REQUEST_URI"];
        // $url_defoult = "http://" . $host . $url;
            ?>
        <script>
        (function ($) {

        var list_product = $('.list-product-order tr td .single_add_to_cart_button_custom');

            list_product_id = [];
            list_product.each(function( index ) {
                console.log($(this).attr('data-quantity'));
                if($(this).attr('data-quantity') !== undefined){
                    list_product_id.push($(this).attr('data-product_id') + ':' + $(this).attr('data-quantity').split()[0]);
                }

            
            });
            jQuery('#add_to_cart_button_custom_all').attr("href", '?add-to-cart=' + list_product_id.join());
            






        $(document).on('click', '.single_add_to_cart_button_custom', function (e) {
            e.preventDefault();

            var $thisbutton = $(this),
                    $form = $thisbutton.parent('form.cart_custom'),
                    id = $(this).attr('data-product_id'),
                    product_qty = $(this).attr('data-quantity') || 1,
                    product_id = $(this).attr('data-product_id') || id;

            var data = {
                action: 'sp_add_to_cart_custom',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty
            };

            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

            $.ajax({
                type: 'post',
                url: '<?php echo admin_url("admin-ajax.php");?>',
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) {

                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });

            return false;
        });
        })(jQuery);

        </script>
        <?php 
    }

    function sp_add_to_cart_custom() {   
        
        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

            do_action('woocommerce_sp_added_to_cart', $product_id);

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            WC_AJAX :: get_refreshed_fragments();
        } else {

            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

            echo wp_send_json($data);
        }

        wp_die();

    }

    // Add to cart multiple product
    function sp_add_multiple_products_to_cart( $url = false ) {
        // Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
        if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
            return;
        }

        // Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
        remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

        $product_ids = explode( ',', $_REQUEST['add-to-cart'] );
        $count       = count( $product_ids );
        $number      = 0;

        foreach ( $product_ids as $id_and_quantity ) {
            // Check for quantities defined in curie notation (<product_id>:<product_quantity>)
            
            $id_and_quantity = explode( ':', $id_and_quantity );
            $product_id = $id_and_quantity[0];

            $_REQUEST['quantity'] = ! empty( $id_and_quantity[1] ) ? absint( $id_and_quantity[1] ) : 1;

            if ( ++$number === $count ) {
                // Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
                $_REQUEST['add-to-cart'] = $product_id;

                //return WC_Form_Handler::add_to_cart_action('/carrito');
            }

            $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
            $was_added_to_cart = false;
            $adding_to_cart    = wc_get_product( $product_id );

            if ( ! $adding_to_cart ) {
                continue;
            }

            $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );

            // Variable product handling
            if ( 'variable' === $add_to_cart_handler ) {
                woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_variable', $product_id );

            // Grouped Products
            } elseif ( 'grouped' === $add_to_cart_handler ) {
                woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_grouped', $product_id );

            // Custom Handler
            } elseif ( has_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler ) ){
                do_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler, $url );

            // Simple Products
            } else {
                //woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_simple', $product_id );
            }
        }
    }

    // Fire before the WC_Form_Handler::add_to_cart_action callback.
}
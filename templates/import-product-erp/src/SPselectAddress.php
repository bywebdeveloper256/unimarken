<?php

namespace EskofrizImport;

class SPselectAddress{

    public function init()
	{
        add_action('woocommerce_before_order_notes', array( $this, 'sp_add_select_checkout_field'));
        add_action('wp_footer', array($this, 'select_address_js'));
        add_action('woocommerce_checkout_process', array($this, 'sp_select_checkout_field_process'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'sp_select_checkout_field_update_order_meta'));
        add_action( 'woocommerce_admin_order_data_after_billing_address', array($this, 'sp_select_checkout_field_display_admin_order_meta'), 10, 1 );
        add_filter('woocommerce_email_order_meta_keys', array($this, 'sp_select_order_meta_keys'));
    }
   
   public function sp_add_select_checkout_field( $checkout ) {

        woocommerce_form_field( '_field_addresses_select', array(
        
            'type'          => 'select',
        
            'class'         => array( '', '' ),
        
            'label'         => __( 'Seleccione dirección de envió'),
        
            'required'      => 'required',
        
            'options'       => array('Mis Direcciones'),
        
        ),
        $checkout->get_value( '_field_addresses_select' ));
   
   }

   //* Process the checkou


    function sp_select_checkout_field_process() {

    global $woocommerce;

    // Check if set, if its not set add an error.

    if ($_POST['_field_addresses_select'] == "blank")

    wc_add_notice( '<strong>Dirección de envio</strong>', 'error' );

    }

    //* Update the order meta with field value

   

    function sp_select_checkout_field_update_order_meta( $order_id ) {

    if ($_POST['_field_addresses_select']) update_post_meta( $order_id, '_field_addresses_select', esc_attr($_POST['_field_addresses_select']));

    }

    //* Display field value on the order edition page

    

    function sp_select_checkout_field_display_admin_order_meta($order){
    // var_dump($order);
    echo '<p><strong>'.__('Dirección de envio').':</strong> <br>' . get_post_meta( $order->get_id(), '_field_addresses_select', true ) . '</p>';

    }

    //* Add selection field value to emails

    
    function sp_select_order_meta_keys( $keys ) {

    $keys['Dirección de envio'] = '_field_addresses_select';

    return $keys;

    }


   public function select_address_js(){

      if( !is_user_logged_in() ){
            return false;
        }
        global $wpdb;

        $tabla = "{$wpdb->prefix}sp_addresses_user_erp";

        $list_info_user = get_the_author_meta( 'aditional_info_user', get_current_user_id() );
        
        if( empty($list_info_user) ){
            return false;
        }
        $code = $list_info_user['Código cliente'];

        $query = "SELECT $tabla.dir_1, $tabla.email_dir, $tabla.name_dir, $tabla.num_calle, $tabla.poblacion, $tabla.postal_code, $tabla.tel_dir  FROM $tabla WHERE $tabla.cli_cod = $code";

        $list = $wpdb->get_results($query,ARRAY_A);

        if( empty($list) ){
            return false;
        }
        $list = json_encode($list);
    ?>
    
    <script>
try{
    jQuery(document).ready(function($){

var test = <?php echo $list; ?>

$.each(test, function(key, item){

    $("#_field_addresses_select").append($("<option>", {
        id: 'address_erp-'+key,
        value: item['dir_1'] +' | '+item['poblacion']+' | '+item['email_dir']+'| '+item['postal_code']+' | '+item['tel_dir']+'| '+item['name_dir'],
        text: item['dir_1'],
        class: 'address_erp',
    }));

});
$("#_field_addresses_select").change(function(){
    var value = $( "#_field_addresses_select option:selected" ).val();

    var porcion = value.split('|');

    if(value){
        $('#billing_address_1').val(porcion[0]).text(porcion[0]);
        $('#billing_postcode').val(porcion[3]).text(porcion[3]);
        $('#billing_phone').val(porcion[4]).text(porcion[4]);
        $('#billing_email').val(porcion[2]).text(porcion[2]);
        $('#billing_city').val(porcion[1]).text(porcion[1]); 
        $('#billing_company').val(porcion[5]).text(porcion[5]); 
    }
    
});


$('#_field_addresses_select option').each(function(key, item){

    if($(this).attr('value') == 0){
        $(this).attr('selected', true);
		 $(this).attr('disabled', true);
    }
    if($(this).attr('value') == ''){
        $(this).remove();
    }
    });
});
}catch{

}
        
    </script>




    <?php

   }

}
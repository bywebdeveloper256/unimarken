<?php

namespace EskofrizImport;

use EskofrizImport\SPconnectionErp;

class SPpriceUpdate{

   public function init()
   {
      add_action( 'wp_ajax_import_price_product',           array( $this, 'import_price_product' ) );
      add_action( 'wp_ajax_nopriv_import_price_product',    array( $this, 'import_price_product' ) );
      add_filter( 'woocommerce_product_get_price',          array( $this, 'update_price_for_user' ), 10, 2 );
      add_filter( 'woocommerce_product_get_regular_price',  array( $this, 'update_price_for_user' ), 10, 2 );
      add_filter( 'woocommerce_product_get_sale_price',     array( $this, 'update_price_for_user' ), 99, 2 );
	  add_action('woocommerce_product_options_general_product_data', array( $this,'sp_woocommerce_product_custom_fields_price_for_kg'));
      add_action('woocommerce_process_product_meta', array( $this, 'sp_woocommerce_product_custom_fields_price_for_kg_save'));
	   
	   add_action('woocommerce_product_meta_end', array($this, 'sp_message_for_kg'));
   }
	
	
	public function sp_message_for_kg(){
		
		$message = get_post_meta(get_the_id(), '_ap_custom_product_label_formato', true);
		
		$kg = (float) get_post_meta(get_the_id(), '_ap_custom_product_price_for_kg', true);
		
		$product = wc_get_product(get_the_id());
		
		$price_defoult = (float) $product->get_price();
		
		$price = $price_defoult / $kg;
?>
<div class="content_price_for_kg">
<?php if(!empty($message)){ ?>
	<p class="kg_label_massage">
		Formato <strong> <?php echo mb_strtoupper($message); ?></strong>
	</p>
<?php }  ?>
	
<?php if(!empty($kg)){ ?>
	<p class="kg_for_product">
		<strong><?php echo $kg; ?> KG </strong>
	</p>
	<?php if(is_user_logged_in()) : ?>
	<p class="kg_price_for_kilo">
		<strong><?php echo $price; ?> € </strong>
		<span class="text_referential"> Precio por KG</span>
	</p>
	<?php else : ?>
	<?php endif; ?>
<?php }  ?>
	
</div>

<?php
	}

	public function sp_woocommerce_product_custom_fields_price_for_kg()
{

    global $woocommerce, $post;
    echo '<div class="product_custom_field"> <strong>Configuracion de precio por kilo </strong>';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_ap_custom_product_label_formato',
            'placeholder' => 'Estuche de 1 KG',
            'label' => __('Formato', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
      woocommerce_wp_text_input(
        array(
          'id' => '_ap_custom_product_price_for_kg',
          'placeholder' => '5.25',
          'label' => __('Ingrese el peso del producto', 'woocommerce'),
          'desc_tip' => 'true'
      )
    );
    echo '</div>';

    
}
	
	public function sp_woocommerce_product_custom_fields_price_for_kg_save($post_id){
		 $woocommerce_ap_custom_product_label_formato = $_POST['_ap_custom_product_label_formato'];
		if(isset($woocommerce_ap_custom_product_label_formato)){
			update_post_meta($post_id, '_ap_custom_product_label_formato', esc_attr($woocommerce_ap_custom_product_label_formato));
		}
		$woocommerce_ap_custom_product_price_for_kg = $_POST['_ap_custom_product_price_for_kg'];
		if(isset($woocommerce_ap_custom_product_price_for_kg)){
			update_post_meta($post_id, '_ap_custom_product_price_for_kg', esc_attr($woocommerce_ap_custom_product_price_for_kg));
		}
	}
	
   public function import_price_product()
   {
      $token = isset( $_GET['token'] ) ? $_GET['token'] : '';

      if( $token === 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' )
      {
         global $wpdb;

         $files = array( 
            'mbl_out_cli_predto.txt',
            'mbl_out_tar_predto.txt',
         ); 

         $arch = new SPconnectionErp( $files );

         $tabla = "{$wpdb->prefix}sp_price_tar_erp";

         try {

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_tar_predto.txt';

            $archivoID     = fopen( $file_name, "r");

            $ra = 0;
            $rc = 0;
            $mail_create   = '<strong>Tarifas de productos</strong><br>';
            $mail_asun     = 'Importación de tarifas de productos';

            while( !feof( $archivoID ) )
            {
               $linea   = fgets( $archivoID, 1024 );

               $porcion = explode( "	", iconv( "ISO-8859-15", "UTF-8", $linea ) );

               $pre_dto    = isset( $porcion[0] )  ? $porcion[0] : '';
               $tar_agr    = isset( $porcion[2] )  ? $porcion[2] : '';
               $cod_art    = isset( $porcion[6] )  ? $porcion[6] : '';
               $tarp_cod   = isset( $porcion[3] )  ? $porcion[3] : '';
               $val_pre    = isset( $porcion[18] ) ? str_replace( ',', '.', $porcion[18] ) : '';

               if( $tar_agr === '12' )
               {
                  $key     = base64_encode( $cod_art . $tarp_cod );
                  
                  $datos = [ 
                     'pre_dto'  => $pre_dto,
                     'cod_art'  => $cod_art,
                     'tarp_cod' => $tarp_cod,
                     'val_pre'  => floatval( $val_pre ),
                  ];
                        
                  $query = "SELECT * FROM $tabla WHERE IdArt = '$key' LIMIT 1";
                  $prod  = $wpdb->get_results( $query,ARRAY_A );

                  if( count( $prod ) === 1 )
                  {
                     $ra +=  $wpdb->update( $tabla, $datos, array( 'IdArt' => $key ) );
                     
                  }else{

                     $datos['IdArt'] = $key;
                     $rc +=  $wpdb->insert( $tabla, $datos );
                  }
               }
            }
            $mail_create .= $rc > 0 ? $rc . ' Tarifas  creados<br>' : '0 tarifas creados<br>';
            $mail_create .= $ra > 0 ? $ra . ' Tarifas  actualizados<br>' : '0 tarifas actualizados<br>';

            $mail             = self::send_mail_import_product( $mail_create, $mail_asun );
            $import_discount  = self::import_discount_product_for_user();
         }
            
         catch (Exception $e)
         {
            echo $e->getMessage();
            die();
         }
      }else{
         echo 'Acción no permitida';
      }
   }

   function import_discount_product_for_user()
   {
      global $wpdb;

      $tabla         = "{$wpdb->prefix}sp_price_discount_erp";

      $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli_predto.txt';

      $archivoID     = fopen( $file_name, "r" );

      $datos         = [];
	   $mail_create   = '<strong>Descuentos de productos en relacion a los usuarios </strong><br>';
	   $mail_asun     = 'Importación de descuentos de productos por usuarios';

      while( !feof( $archivoID ) )
      {
         $linea   = fgets( $archivoID, 1024);

         $porcion = explode( "	", $linea );

         $pre_agr = isset( $porcion[0] )  ? $porcion[0]  : '';
         $pre_dto = isset( $porcion[1] )  ? $porcion[1]  : '';
         $cli_cod = isset( $porcion[4] )  ? $porcion[4]  : '';
         $lin_cod = isset( $porcion[18] ) ? $porcion[18] : '';
         $cod_art = isset( $porcion[8] )  ? str_replace( ' ', '', $porcion[8] )     : '';
         $por_dt1 = isset( $porcion[21] ) ? str_replace( ',', '.', $porcion[21] )   : '';

         if( !empty( $cod_art) && $pre_agr === '12' )
         {
            $key = base64_encode( $cli_cod . $cod_art );

            $datos = [ 
               'pre_dto'   => $pre_dto,
               'cli_cod'   => $cli_cod,
               'cod_art'   => $cod_art,
               'lin_cod'   => $lin_cod,
               'por_dt1'   => floatval( $por_dt1 ),
            ];

            $query = "SELECT * FROM $tabla WHERE IdArt = '$key' LIMIT 1";
            $prod  = $wpdb->get_results( $query, ARRAY_A );

            if( count( $prod) === 1 )
            {   
               $ra +=  $wpdb->update( $tabla, $datos, array( 'IdArt' => $key ) );
         
            }else{

               $datos['IdArt'] = $key;
               $rc +=  $wpdb->insert( $tabla, $datos );
            }
         }
      }
	   
      $mail_create .= $rc > 0 ? $rc . ' Descuentos creados<br>'      : '0 Descuentos creados<br>';
      $mail_create .= $ra > 0 ? $ra . ' Descuentos actualizados<br>' : '0 Descuentos actualizados<br>';
	   
	   $mail = self::send_mail_import_product( $mail_create, $mail_asun );
   }

   public function update_price_for_user( $price, $product )
   {
      if( current_user_can( 'administrator' )  )
      {
         return '';
      }

      $list_info_user = get_the_author_meta( 'aditional_info_user', get_current_user_id() );

      if( empty( $list_info_user ) )
      {
         return $price;
      }

      $code_user_tar       = $list_info_user["Código tarifa de precios"];

      $code_user_discount  = $list_info_user["Código cliente"];

      $code_art            = $product->get_sku();

      $price_total         = self::price_for_user_tar($code_user_tar , $code_art);

      $price_desc          = self::price_for_user_discount( $code_user_discount, $code_art );

      $price_desc2         = self::price_for_oferta( $product );

      if($price_desc > 0 && $price_desc >= $price_desc2){
         $v             = 100 - $price_desc;
         $price_total   = ( $price_total * $v ) / 100;
      }elseif($price_desc2 > 0){
         $v             = 100 - $price_desc2;
         $price_total   = ( $price_total * $v ) / 100;
      }

      $price_for_kg = (float) get_post_meta($product->get_id(), '_ap_custom_product_price_for_kg', true);
      $formato = trim($product->get_purchase_note());
	   
	   if($formato == 'KG' && !empty($price_for_kg)){
		   return $price_total * $price_for_kg;
	   }
	   
      return $price_total;
   }
   
   function price_for_user_tar( $code_user, $code_art )
   {
      global $wpdb;

      $tabla   = "{$wpdb->prefix}sp_price_tar_erp";

      $key     = base64_encode( $code_art.$code_user );
   
      $query   = "SELECT $tabla.val_pre FROM $tabla WHERE $tabla.IdArt LIKE '%$key%'";

      $list    = $wpdb->get_results( $query, ARRAY_A );

      if( !empty( $list ) )
      {
         $price = $list[0]['val_pre'];
      }
      return $price;
   }

   function price_for_oferta( $producto )
   {

      if(!get_current_user_id()) return 0;

      global $wpdb;

      $tabla   = "{$wpdb->prefix}sp_discount_for_user";

      $user    = wp_get_current_user();

      $ofertas = $wpdb->get_results("SELECT * FROM $tabla WHERE status = '1'");

      $desc    = 0;

      if(!empty($ofertas)) foreach ($ofertas as $o) {

         $cat_conf = json_decode($o->category_discount);
         $pro_conf = json_decode($o->product_discount);
         $usr_conf = json_decode($o->specific_user);
         $rol_conf = json_decode($o->rol_for_user);

         /*//OR
         //Esta en la categoria
         if( is_array($cat_conf) && has_term($cat_conf, 'product_cat', $producto->get_id()) && $o->percentage > $desc) $desc = $o->percentage;
         //Esta en productos
         if( is_array($pro_conf) && in_array($producto->get_id(), $pro_conf) && $o->percentage > $desc) $desc = $o->percentage;
         //Esta en usuario
         if( is_array($usr_conf) && in_array(get_current_user_id(), $usr_conf) && $o->percentage > $desc) $desc = $o->percentage;
         //Esta en rol
         if( is_array($rol_conf) && is_array($user->roles) && count(array_intersect_assoc($rol_conf, $user->roles)) && $o->percentage > $desc ) $desc = $o->percentage; */

         ///AND
         $vd = 1;

         //Esta en la categoria o en el productos
         if( is_array($cat_conf) && !empty($cat_conf)) $vd = (has_term($cat_conf, 'product_cat', $producto->get_id()) && $o->percentage > $desc);
         if( $vd !== true && is_array($pro_conf) && !empty($pro_conf)) $vd = (in_array($producto->get_id(), $pro_conf) && $o->percentage > $desc);

         //Esta en usuario
         if( $vd && is_array($usr_conf) && !empty($usr_conf)) $vd = (in_array(get_current_user_id(), $usr_conf) && $o->percentage > $desc);
         //Esta en rol
         if( $vd && is_array($rol_conf) && is_array($user->roles) && !empty($rol_conf) && !empty($user->roles)) $vd = (count(array_intersect_assoc($rol_conf, $user->roles)) && $o->percentage > $desc);

         if($vd === true && $o->percentage > $desc) $desc = $o->percentage;

      }
      return $desc;
   }


   function price_for_user_discount( $code_user_discount, $code_art )
   {
      global $wpdb;

      $tabla   = "{$wpdb->prefix}sp_price_discount_erp";

      $key     = base64_encode( $code_user_discount.$code_art );

      $query   = "SELECT $tabla.por_dt1 FROM $tabla WHERE $tabla.IdArt LIKE '%$key%'";

      $list    = $wpdb->get_results( $query,ARRAY_A );

      if( !empty( $list ) )
      {
         return $list[0]['por_dt1'];

      }else{

         return false;
      }
   }
	
	function send_mail_import_product( $mail_import, $mail_asun )
   {
      $admin         = sp_get_results_table_conf_key_erp();

		$email_to       = $admin[0]["email_admin"];

      $email_subject  =  $mail_asun;

      ob_start();

      $content = $mail_import;

      ob_get_clean();

      $send_mail = wp_mail( $email_to, $email_subject, $content );

      return $send_mail;
   }
}
   
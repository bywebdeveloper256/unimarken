<?php

namespace EskofrizImport;

use Automattic\WooCommerce\Client;
use EskofrizImport\SPImportGenarateData;
use EskofrizImport\SPconnectionErp;

class SPImportData{

    public function init()
	{
        add_action( 'wp_ajax_data_import_product',          array( $this, 'data_import_product' ) );
        add_action( 'wp_ajax_nopriv_data_import_product',   array( $this, 'data_import_product' ) );
        add_filter( 'wp_mail_content_type',                 array( $this, 'tipo_de_contenido_html') );
    }

    public function wh_deleteProduct($id, $force = FALSE)
    {
        $product = wc_get_product($id);

        if(empty($product))
            return sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id);

        // If we're forcing, then delete permanently.
        if ($force)
        {
            if ($product->is_type('variable'))
            {
                foreach ($product->get_children() as $child_id)
                {
                    $child = wc_get_product($child_id);
                    $child->delete(true);
                }
            }
            elseif ($product->is_type('grouped'))
            {
                foreach ($product->get_children() as $child_id)
                {
                    $child = wc_get_product($child_id);
                    $child->set_parent_id(0);
                    $child->save();
                }
            }

            $product->delete(true);
            $result = $product->get_id() > 0 ? false : true;
        }
        else
        {
            $product->delete();
            $result = 'trash' === $product->get_status();
        }

        if (!$result)
        {
            return sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product');
        }

        // Delete parent product transients.
        if ($parent_id = wp_get_post_parent_id($id))
        {
            wc_delete_product_transients($parent_id);
        }
        return true;
    }

    public function data_import_product()
	{
        $token = isset( $_GET['token'] ) ? $_GET['token'] : '';

        if( $token === 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' )
        {
            $files = array( 
                'mbl_out_art.txt',
                'mbl_out_sto.txt',
                'mbl_out_art_iva.txt', 
            ); 

            $arch = new SPconnectionErp( $files );

            global $wpdb;
 
            $tabla      = $wpdb->prefix.'sp_conf_key_erp';
            $query      = "SELECT * FROM $tabla";
            $list       = $wpdb->get_results($query,ARRAY_A);

            if(empty($list)){
                return false;
            }

            $woocommerce = new Client(
            $list[0]['url_key'],
            $list[0]['key_client'], 
            $list[0]['key_Secret'],
                [
                    'version' => 'wc/v3',
                    'timeout' => 500
                ]
            );

            $data   = new SPImportGenarateData;

            $r      = $data->generate_data_import_product();

            $lotes_creates  = isset( $r['create'] ) ? array_chunk( $r['create'], 1000 ) : array();

            $lotes_updates  = isset( $r['update'] ) ? array_chunk( $r['update'], 1000 ) : array();

            $lotes_updates_dlt  = isset( $r['prdct_dlt'] ) ? array_chunk( $r['prdct_dlt'], 1000 ) : array();

            $mail_import    = '';

            if( !empty( $lotes_creates ) )
            {
                for( $i = 0; $i < count( $lotes_creates ); $i++ )
                { 
                    if( isset( $lotes_creates[$i] ) )
                    {
                        $lote_c = array_chunk( $lotes_creates[$i], 100 );

                        if( !empty( $lote_c ) )
                        {
                            for( $i = 0; $i < count($lote_c); $i++ )
                            {
                                $lote = $i + 1;
            
                                $import_create = [
                                    'create' => $lote_c[$i],
                                ];
            
                                $result = $woocommerce->post( 'products/batch', $import_create );
            
                                if( $result->create )
                                {
                                    $mail_import .= '<strong>inicio de lote ' . $lote . ' de productos importados <br><br></strong>';
            
                                    $count_create = 1;
            
                                    foreach( $result->create as $item_create )
                                    {
                                        $mail_import .= $count_create . ') SKU [ ' . $item_create->sku . ' ] -> ' . $item_create->name . '<br>';
            
                                        $count_create++;
                                    }
                                    $mail_import .= '<br> fin de lote ' . $lote . ' de productos importados <br> <br> <br> <br>';
                                }
                            }
                        }
                    }
                }
            }

            if( !empty( $lotes_updates ) )
            {
                for( $i = 0; $i < count( $lotes_updates ); $i++ )
                {
                    if( isset( $lotes_updates[$i] ) )
                    {
                        $lote_u = array_chunk( $lotes_updates[$i], 100 );

                        if( !empty( $lote_u ) )
                        {
                            for( $i = 0; $i < count($lote_u); $i++ )
                            {
                                $lote = $i + 1;

                                $import_update = [
                                    'update' => $lote_u[$i],
                                ];

                                $needle_dlt = " BR ";
                                $name_find_br = $lote_u[$i][0]['name'];
                                $id_product_dlt =  $lote_u[$i][0]['id'];

                                $result = $woocommerce->post( 'products/batch', $import_update );

                                if( $result->update )
                                {
                                    $mail_import .= '<strong>inicio de lote ' . $lote . ' de productos actualizados <br><br></strong>';

                                    $count_update = 1;

                                    foreach( $result->update as $item_update )
                                    {
                                        $mail_import .= $count_update . ') SKU [ ' . $item_update->sku . ' ] -> ' . $item_update->name . '<br>';

                                        $count_update++;
                                    }
                                    $mail_import .= '<br> fin de lote ' . $lote . ' de productos actualizados <br> <br> <br> <br>';
                                }
                            }
                        }
                    }
                }
            }

            if( !empty( $lotes_updates_dlt ) )
            {
                for( $i = 0; $i < count( $lotes_updates_dlt ); $i++ )
                {
                    if( isset( $lotes_updates_dlt[$i] ) )
                    {
                        $lote_ud = $lotes_updates_dlt[$i];
                        if(!empty($lote_ud)){
                            for($k=0;$k<count($lote_ud);$k++){
                                $name_prdct_dlt = $lote_ud[$k]['name'];
                                $id_prdct_dlt = $lote_ud[$k]['id'];
                                $prdct_dlt = self::wh_deleteProduct($id_prdct_dlt, true);
                                if($prdct_dlt){
                                    echo "Producto con el id: {$id_prdct_dlt} eliminado con exito";
                                }
                            }
                        }
                    }
                }
            }

            if( !empty( $mail_import ) )
            {
                echo  $mail_import;
                $send = self::send_mail_import_product( $mail_import );

            }else{
                echo $mail_import = 'No hay productos nuevos para crear, ni productos que actualizar.';
                $send = self::send_mail_import_product( $mail_import );
            }
        }else{
            echo 'Acción no permitida';
        }
    }

    function send_mail_import_product($mail_import)
    {
        $admin = sp_get_results_table_conf_key_erp();

        $email_to       = $admin[0]["email_admin"];

        $email_subject  = 'Resumen de importacion de productos';

        ob_start();

        $content        = $mail_import;

        ob_get_clean();

        $send_mail      = wp_mail( $email_to, $email_subject, $content );

        return $send_mail;
    }

    function tipo_de_contenido_html() 
    {
        return 'text/html';
    }
}

<?php

namespace EskofrizImport;

class SPImportGenarateData{

    function stocck_erp()
    {
        $file_name     = plugin_dir_path( __DIR__, 1 ).'includes/data/mbl_out_sto.txt';

        $stock = [];

        $archivoStockID = fopen( $file_name, "r" );

        while( !feof( $archivoStockID ) ){

            $register = fgets( $archivoStockID, 1024 );
            
            $uni_quantity = explode("	", iconv( "ISO-8859-15", "UTF-8", $register ) );

            $cod_emp    = isset( $uni_quantity[0] ) ? $uni_quantity[0] : '';
            $art_agr    = isset( $uni_quantity[1] ) ? $uni_quantity[1] : '';
            $cod_art    = isset( $uni_quantity[2] ) ? $uni_quantity[2] : '';
            $uni_cod    = isset( $uni_quantity[6] ) ? $uni_quantity[6] : '';
            $qnt_sto    = isset( $uni_quantity[7] ) ? $uni_quantity[7] : '';

            if( $cod_emp === '12' && $art_agr === '12' ){
				
                $stock[ $cod_art ] = array(

                    'uni_cod' => $uni_cod,
                    'qnt_sto' => $qnt_sto,
                );
            }
        }

        fclose( $archivoStockID );

        return $stock;
    }
	
    function iva_class_erp()
    {
        $file_name     = plugin_dir_path( __DIR__, 1 ).'includes/data/mbl_out_art_iva.txt';

        $iva = [];

        $archivoivaID  = fopen( $file_name, "r" );

        while( !feof( $archivoivaID ) )
        {
            $register   = fgets( $archivoivaID, 1024 );

            $porcion    = explode( "	", iconv( "ISO-8859-15", "UTF-8", $register ) );

            $pai_cod = isset( $porcion[2] ) ? $porcion[2] : '';
            $art_agr = isset( $porcion[3] ) ? $porcion[3] : '';
            $cod_art = isset( $porcion[4] ) ? $porcion[4] : '';
            $iva_cod = isset( $porcion[8] ) ? $porcion[8] : '';

            if($pai_cod == "    " && $art_agr == '12' ){
            
                $iva[ $cod_art ] = array(
                    'iva' => $iva_cod,
                );
            }
        }
        fclose($archivoivaID);

        return $iva;
    }
	
    public function generate_data_import_product()
    {
        $file_name     = plugin_dir_path( __DIR__, 1 ).'includes/data/mbl_out_art.txt';

        $data   = [];

		$create = [];

        $prdct_dlt = [];

		$update = [];

        $stock  = self::stocck_erp();
		
		$iva    = self::iva_class_erp();
         
        $archivoID = fopen( $file_name, "r" );    
        
        while( !feof( $archivoID ) ){

            $linea      = fgets($archivoID, 1024);

            $porcion    = explode( "	", iconv( "ISO-8859-15", "UTF-8", $linea ) );
            
            $art_agr        = isset( $porcion[0] ) ? $porcion[0] : '';
            $cod_art        = isset( $porcion[1] ) ? $porcion[1] : '';
            $nom_art        = isset( $porcion[3] ) ? str_replace( "\/", "-", $porcion[3] ) : '';
            $uni_ven_cod    = isset( $porcion[33] ) ? $porcion[33] : '';
            $iva_product    = isset( $iva[$cod_art]['iva'] ) ?  $iva[$cod_art]['iva'] : 0;
            $qnt_sto        = isset( $stock[$cod_art]['qnt_sto'] ) ? $stock[$cod_art]['qnt_sto'] : 0;
            $quantity       = !empty( $qnt_sto ) ? intval( $qnt_sto ) : 0;

            if( !empty( $cod_art ) && $art_agr === '12' ){

                $id_product = wc_get_product_id_by_sku( $cod_art );

                if( $id_product === 0 ){

                    $needle_dlt = " BR ";

                    if(strpos($nom_art, $needle_dlt) === false){

                       $create[] = [
                        'name'              => $nom_art,
                        'type'              => 'simple',
                        'description'       => $nom_art,
                        'short_description' => $nom_art,
                        'price'             => 0,
                        'sku'               => $cod_art,
                        'purchase_note'     => $uni_ven_cod,
                        'tax_status'        => "taxable",
                        'tax_class'         => $iva_product,
                        'manage_stock'      => true,
                        'stock_quantity'    => $quantity,
                        ]; 

                    }

                    

                }else{

                    $product = wc_get_product( $id_product );

                    $needle_dlt = " BR ";

                    if(strpos($nom_art, $needle_dlt)){

                        $prdct_dlt[] = [
                            'name'              => $nom_art,
                            'id'                => $id_product,
							'manage_stock'      => true,
                            'stock_quantity'    => $quantity,
                        ];
                    }elseif( !empty( $cod_art ) && $product->get_stock_quantity() !== $quantity ){

                        $update[] = [
                            'name'              => $nom_art,
                            'id'                => $id_product,
							'manage_stock'      => true,
                            'stock_quantity'    => $quantity,
                        ];
                    }
                }
            }
        }

        $data = [
           'create' => $create,
           'update' => $update,
           'prdct_dlt'     => $prdct_dlt
        ];
        return $data;
    }
}

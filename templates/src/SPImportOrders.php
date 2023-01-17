<?php

namespace EskofrizImport;

class SPImportOrders{

    public function init()
    {
       add_action( 'init',                               array( $this, 'table_orders_cab' ) );
       add_action( 'init',                               array( $this, 'table_orders_lin' ) );
       add_action( 'init',                               array( $this, 'table_orders_hab' ) );
       add_action( 'wp_ajax_import_orders_cab',          array( $this, 'import_orders_cab' ) );
       add_action( 'wp_ajax_nopriv_import_orders_cab',   array( $this, 'import_orders_cab' ) );
       add_action( 'wp_ajax_import_orders_lin',          array( $this, 'import_orders_lin' ) );
       add_action( 'wp_ajax_nopriv_import_orders_lin',   array( $this, 'import_orders_lin' ) );
       add_action( 'wp_ajax_import_orders_hab',          array( $this, 'import_orders_hab' ) );
       add_action( 'wp_ajax_nopriv_import_orders_hab',   array( $this, 'import_orders_hab' ) );
    }

    function table_orders_cab()
    {
        global $wpdb;

        $sql =  "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_orders_cab_erp ( 
                `IdOrder`   INT NOT NULL AUTO_INCREMENT,  
                `cod_uni`   VARCHAR(100) UNIQUE,
                `cod_ser`   VARCHAR(50) NULL DEFAULT NULL,
                `num_ser`   VARCHAR(50) NULL DEFAULT NULL, 
                `cli_cod`   VARCHAR(50) NULL DEFAULT NULL, 
                `num_adr`   VARCHAR(50) NULL DEFAULT NULL, 
                `dia_ped`   VARCHAR(50) NULL DEFAULT NULL, 
                `ref_cli`   VARCHAR(50) NULL DEFAULT NULL, 
                `val_imp`   FLOAT(5,2) NULL DEFAULT NULL,
                PRIMARY KEY (`IdOrder`));";

        $wpdb->get_results($sql);
    }

    function table_orders_lin()
    {
        global $wpdb;

        $sql =  "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_orders_lin_erp ( 
                `Idorderslin`  INT NOT NULL AUTO_INCREMENT, 
                `cod_uni`      VARCHAR(100) UNIQUE, 
                `cod_ser`      VARCHAR(100) NULL DEFAULT NULL, 
                `num_ser`      VARCHAR(100) NULL DEFAULT NULL, 
                `num_lin`      VARCHAR(100) NULL DEFAULT NULL, 
                `cod_art`      VARCHAR(100) NULL DEFAULT NULL, 
                `uni_ven_cod`  VARCHAR(100) NULL DEFAULT NULL, 
                `num_uni`      VARCHAR(100) NULL DEFAULT NULL, 
                `dia_ent`      VARCHAR(100) NULL DEFAULT NULL, 
                `val_pre`      FLOAT(5,2) NULL DEFAULT NULL, 
                `por_dt1`      FLOAT(5,2) NULL DEFAULT NULL, 
                `val_imp`      FLOAT(5,2) NULL DEFAULT NULL,  
                 PRIMARY KEY (`Idorderslin`));";

        $wpdb->get_results($sql);
    }

    function table_orders_hab()
    {
        global $wpdb;

        $sql =  "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_orders_hab_erp ( 
                `IdRegular` INT NOT NULL AUTO_INCREMENT,  
                `cod_uni`   VARCHAR(100) UNIQUE,
                `cli_cod`   VARCHAR(50) NULL DEFAULT NULL,
                `num_adr`   VARCHAR(50) NULL DEFAULT NULL, 
                `cod_art`   VARCHAR(50) NULL DEFAULT NULL, 
                `fec_com`   VARCHAR(50) NULL DEFAULT NULL,
                `num_uni`   VARCHAR(50) NULL DEFAULT NULL, 
                `uni_cod`   VARCHAR(50) NULL DEFAULT NULL, 
                `val_pre`   FLOAT(5,2) NULL DEFAULT NULL, 
                `val_dt1`   FLOAT(5,2) NULL DEFAULT NULL,
                PRIMARY KEY (`IdRegular`));";

        $wpdb->get_results($sql);
    }

    function import_orders_cab()
    {
        $token = isset( $_GET['token'] ) ? $_GET['token'] : '';

        if( $token === 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' )
        {
            global $wpdb;

            $files = array( 
                'mbl_out_con_hab.txt',
                'mbl_out_ped_cab.txt',
                'mbl_out_ped_lin.txt',
            ); 

            $arch = new SPconnectionErp( $files );

            $tabla = "{$wpdb->prefix}sp_orders_cab_erp";

            try {

                $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_ped_cab.txt';

                $archivoID      = fopen( $file_name, "r" );

                $ra = 0;
                $rc = 0;
                $mail_asun = "Lista de orendes Importadas";
                $mail_import = '';
                while( !feof($archivoID)){

                    $linea      = fgets($archivoID, 1024);

                    $porcion    = explode("	", iconv("ISO-8859-15", "UTF-8", $linea));

                    $cod_emp    = isset( $porcion[0] ) ? $porcion[0] : '';
                    $cod_ser    = isset( $porcion[1] ) ? $porcion[1] : '';
                    $num_ser    = isset( $porcion[2] ) ? $porcion[2] : '';
                    $cli_cod    = isset( $porcion[5] ) ? $porcion[5] : '';
                    $num_adr    = isset( $porcion[6] ) ? $porcion[6] : '';
                    $dia_ped    = isset( $porcion[7] ) ? $porcion[7] : '';
                    $ref_cli    = isset( $porcion[4] ) ? $porcion[4] : '';
                    $val_imp    = isset( $porcion[9] ) ? floatval( $porcion[9] ) : '';

                    if( $cod_emp === '12' ){

                        $key = base64_encode( $num_ser . '-' . $cli_cod );

                        $datos = [
                            'cod_ser'    => $cod_ser,
                            'num_ser'    => $num_ser,
                            'cli_cod'    => $cli_cod,
                            'num_adr'    => $num_adr,
                            'dia_ped'    => $dia_ped,
                            'ref_cli'    => $ref_cli,
                            'val_imp'    => $val_imp
                        ];

                        $query = "SELECT * FROM $tabla WHERE cod_uni = '$key' LIMIT 1";
                        $prod  = $wpdb->get_results( $query,ARRAY_A );

                        if( count( $prod) === 1 ){

                            $ra +=  $wpdb->update( $tabla, $datos, array( 'cod_uni' => $key ) );

                        }else{    
                            $datos['cod_uni'] = $key;
                            $rc +=  $wpdb->insert( $tabla, $datos );
                        }
                    }
                }
                $mail_import .= 'Los siguientes archivos se han importado correctamente:<br>';
                $mail_import .= 'mbl_out_con_hab.txt<br>';
                $mail_import .= 'mbl_out_ped_cab.txt<br>';
                $mail_import .= 'mbl_out_ped_lin.txt<br><br>';
                $mail_import .= 'CABECERAS IMPORTADAS O ACTUALIZADAS<br>';
                $mail_import .= $rc > 0 ? $rc . ' orders_cab creados<br>' : '0 orders_cab creados<br>';
                $mail_import .= $ra > 0 ? $ra . ' orders_cab actualizados<br>' : '0 orders_cab actualizados<br>';
                $mail = self::send_mail_import_product($mail_import,  $mail_asun);
            } 
                
            catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        }else{
            echo 'Acción no permitida';
        }
    }

    function import_orders_lin()
    {
        $token = isset( $_GET['token'] ) ? $_GET['token'] : '';

        if( $token === 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' )
        {
            global $wpdb;

            $tabla = "{$wpdb->prefix}sp_orders_lin_erp";

            try {
    
                $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_ped_lin.txt';

                $archivoID     = fopen( $file_name, "r" );

                $ra = 0;
                $rc = 0;
                $mail_import = '';
                $mail_asun = 'Lista de articulos por orden Importadas';
                while( !feof($archivoID)){

                    $linea      = fgets($archivoID, 1024);

                    $porcion    = explode( "	", iconv("ISO-8859-15", "UTF-8", $linea) );

                    $cod_emp       = isset( $porcion[0] )    ? $porcion[0] : '';
                    $cod_ser       = isset( $porcion[1] )    ? $porcion[1] : '';
                    $num_ser       = isset( $porcion[2] )    ? $porcion[2] : '';
                    $num_lin       = isset( $porcion[3] )    ? $porcion[3] : '';
                    $cod_art       = isset( $porcion[5] )    ? $porcion[5] : '';
                    $uni_ven_cod   = isset( $porcion[9] )    ? $porcion[9] : '';
                    $num_uni       = isset( $porcion[10] )  ? $porcion[10] : '';
                    $dia_ent       = isset( $porcion[12] )  ? $porcion[12] : '';
                    $val_pre       = isset( $porcion[13] )  ? str_replace( ",", ".", $porcion[13] ) : '';
                    $por_dt1       = isset( $porcion[14] )  ? str_replace( ",", ".", $porcion[14] ) : '';
                    $val_imp       = isset( $porcion[6] )    ? $porcion[6] : '';

                    if( $cod_emp === '12' ){

                        $datos = [
                            'cod_ser'       => $cod_ser,
                            'num_ser'       => $num_ser,
                            'num_lin'       => $num_lin,
                            'cod_art'       => $cod_art,
                            'uni_ven_cod'   => $uni_ven_cod,
                            'num_uni'       => $num_uni,
                            'dia_ent'       => $dia_ent,
                            'val_pre'       => round( $val_pre, 2 ),
                            'por_dt1'       => round( $por_dt1, 2 ),
                            'val_imp'       => $val_imp,
                        ];

                        $key = base64_encode( $num_ser . '-' . $num_lin );

                        $query = "SELECT * FROM $tabla WHERE cod_uni = '$key' LIMIT 1";
                        $prod  = $wpdb->get_results( $query,ARRAY_A );

                        if( count( $prod) === 1 ){

                            $ra +=  $wpdb->update( $tabla, $datos, array( 'cod_uni' => $key ) );
                            
                        }else{

                            $datos['cod_uni'] = $key;
                            $rc +=  $wpdb->insert( $tabla, $datos );
                        }
                    }
                }
                $mail_import .= 'ORDENES EN LINEAS IMPORTADAS O ACTUALIZADAS<br>';
                $mail_import .= $rc > 0 ? $rc . ' orders_lin creados<br>' : '0 orders_lin creados<br>';
                $mail_import .= $ra > 0 ? $ra . ' orders_lin actualizados<br>' : '0 orders_lin actualizados<br>';
                $mail = self::send_mail_import_product($mail_import,  $mail_asun);
            } 
                
            catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        }else{
            echo 'Acción no permitida';
        }
    }

    function import_orders_hab()
    {
        $token = isset( $_GET['token'] ) ? $_GET['token'] : '';

        if( $token === 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' )
        {
            global $wpdb;

            $tabla = "{$wpdb->prefix}sp_orders_hab_erp";

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_con_hab.txt';

            $archivoID     = fopen( $file_name, "r" );

            $ra = 0;
            $rc = 0;
            $mail_import = '';
            $mail_asun = 'Lista de compras habituales importadas';
            while( !feof($archivoID)){

                $linea      = fgets($archivoID, 1024);
                
                $porcion    = explode("	", iconv("ISO-8859-15", "UTF-8", $linea));

                $cod_emp    = isset( $porcion[0] ) ? $porcion[0] : '';
                $cli_cod    = isset( $porcion[3] ) ? $porcion[3] : '';
                $num_adr    = isset( $porcion[4] ) ? $porcion[4] : '';
                $cod_art    = isset( $porcion[6] ) ? $porcion[6] : '';
                $fec_com    = isset( $porcion[8] ) ? $porcion[8] : '';
                $num_uni    = isset( $porcion[9] ) ? $porcion[9] : '';
                $uni_cod    = isset( $porcion[12] ) ? $porcion[12] : '';
                $val_pre    = isset( $porcion[14] ) ? str_replace( ",", ".", $porcion[14] ) : '';
                $val_dt1    = isset( $porcion[15] ) ? str_replace( ",", ".", $porcion[15] ) : '';

                if( $cod_emp === '12' ){

                    $key = base64_encode( $cli_cod . '-' . $cod_art );
                    
                    $datos = [
                        'cli_cod'   => $cli_cod,
                        'num_adr'   => $num_adr,
                        'cod_art'   => $cod_art,
                        'fec_com'   => $fec_com,
                        'num_uni'   => $num_uni,
                        'uni_cod'   => $uni_cod,
                        'val_pre'   => round( $val_pre, 2 ),
                        'val_dt1'   => round( $val_dt1, 2 ),
                    ];

                    $query = "SELECT * FROM $tabla WHERE cod_uni = '$key' LIMIT 1";
                    $prod  = $wpdb->get_results( $query,ARRAY_A );

                    if( count( $prod ) === 1 ){

                        $ra +=  $wpdb->update( $tabla, $datos, array( 'cod_uni' => $key ) );

                    }else{

                        $datos['cod_uni'] = $key;
                        $rc +=  $wpdb->insert( $tabla, $datos );
                    }
                }
            }
            $mail_import .= 'COMPRAS HABITUALES IMPORTADAS O ACTUALIZADAS<br>';
            $mail_import .= $rc > 0 ? $rc . ' compras habituales creadas<br>' : '0 compras habituales creadas<br>';
            $mail_import .= $ra > 0 ? $ra . ' compras habituales actualizadas<br>' : '0 compras habituales actualizadas<br>';
            
            $mail = self::send_mail_import_product($mail_import,  $mail_asun);
        }else{
            echo 'Acción no permitida';
        }
    }
	
	
	function send_mail_import_product($mail_import,  $mail_asun)
    {
        $admin = sp_get_results_table_conf_key_erp();

		$email_to       = $admin[0]["email_admin"];

        $email_subject  =  $mail_asun;

        ob_start();

        $content        = $mail_import;

        ob_get_clean();

        $send_mail      = wp_mail( $email_to, $email_subject, $content );

        return $send_mail;
    }   
}
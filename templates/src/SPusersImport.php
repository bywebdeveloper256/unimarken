<?php

namespace EskofrizImport;

class SPusersImport{

    public function init()
    {
        add_action( 'wp_ajax_import_user_erp',          array( $this, 'import_user_erp') );
        add_action( 'wp_ajax_nopriv_import_user_erp',   array( $this, 'import_user_erp') );
        add_action( 'init',                             array( $this, 'table_addresses') ); 
        add_action( 'user_new_form',                    array( $this, 'erp_new_extra_user_profile_fields') );
        add_action( 'show_user_profile',                array( $this, 'erp_extra_user_profile_fields') );
        add_action( 'edit_user_profile',                array( $this, 'erp_extra_user_profile_fields') );
        add_action( 'user_register',                    array( $this, 'erp_save_extra_user_profile_fields') );
        add_action( 'profile_update',                   array( $this, 'erp_save_extra_user_profile_fields') );
    }

    public function table_addresses()
    {
        global $wpdb;

        $sql =  "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_addresses_user_erp ( 
            `IdAddres`      INT NOT NULL AUTO_INCREMENT,  
            `IdFilterDir`   VARCHAR(100) UNIQUE,
            `cli_cod`       VARCHAR(100) NULL DEFAULT NULL, 
            `num_dir`       VARCHAR(100) NULL DEFAULT NULL, 
            `name_dir`      VARCHAR(100) NULL DEFAULT NULL, 
            `type_calle`    VARCHAR(100) NULL DEFAULT NULL, 
            `dir_1`         VARCHAR(100) NULL DEFAULT NULL, 
            `num_calle`     VARCHAR(100) NULL DEFAULT NULL, 
            `dir_2`         VARCHAR(100) NULL DEFAULT NULL, 
            `postal_code`   VARCHAR(100) NULL DEFAULT NULL, 
            `poblacion`     VARCHAR(100) NULL DEFAULT NULL, 
            `email_dir`     VARCHAR(100) NULL DEFAULT NULL,
            `tel_dir`       VARCHAR(100) NULL DEFAULT NULL, 
            PRIMARY KEY (`IdAddres`));";

        $wpdb->get_results($sql);
    }

    function validateEmail($email): bool
    {
        try {
            $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z0-9\.]+(\.)+[a-zA-Z]{2,3})$/";
            return preg_match($regex, $email);
        } 
        
        catch (Exception $e) {

            echo $e->getMessage();
            die();
        }
    }

    function client_json(): array
    {    
        try {

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli.txt';

            $porciones     = [];
            $address       = self::address_erp();
            $clients       = self::contacto_erp();

            $archivoID     = fopen( $file_name, "r" );

            while( !feof($archivoID)){

                $linea      = fgets($archivoID, 1024);

                $porcion    = explode( "	", iconv( "ISO-8859-15", "UTF-8", $linea ) );
                
                $cli_agr       = isset( $porcion[0] ) ? $porcion[0] : '';
                $cli_cod       = isset( $porcion[2] ) ? $porcion[2] : '';
                $nom_fis       = isset( $porcion[3] ) ? $porcion[3] : '';
                $num_nif       = isset( $porcion[13] ) ? $porcion[13] : '';
                $obs_com       = isset( $porcion[20] ) ? $porcion[20] : '';

                if( $cli_agr === '12' ){

                    if(!empty($clients[$cli_cod]['Email'])){

                        $porciones[] = array(
                            'Nif'                           => $num_nif,
                            'Nombre'                        => $nom_fis,
                            'Código agrupador cliente'      => $address[$cli_cod]['Código agrupador cliente'],
                            'Código tarifa de precios'      => $address[$cli_cod]['Código tarifa de precios'],
                            'Tipo'                          => $address[$cli_cod]['Tipo'],
                            'Código cliente'                => $cli_cod,
                            'Número dirección'              => $address[$cli_cod]['Número dirección'],
                            'Teléfono 1'                    => $address[$cli_cod]['Teléfono 1'],
                            'Teléfono 2'                    => $address[$cli_cod]['Teléfono 2'],
                            'Email'                         => $clients[$cli_cod]['Email'],
                            'Observaciones comerciales'     => $obs_com,
                            'Tipo de portes'                => $address[$cli_cod]['Tipo de portes'],
                            'Incoterm'                      => $address[$cli_cod]['Incoterm'],
                            'Código agrupador tarifa'       => $address[$cli_cod]['Código agrupador tarifa'],
                            'Código tarifa de descuentos'   => $address[$cli_cod]['Código tarifa de descuentos'],
                            'Código tarifa de precios'      => $address[$cli_cod]['Código tarifa de precios'],
                        );  
                    }else{
						
					}
                }
            }
		  
            return $porciones;
        } 

        catch (Exception $e) {

            echo $e->getMessage();
            die();
        }
    }

    function tarifa_json(): array
    {
        $porciones      = [];

        $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli_div.txt';

        $archivoID     = fopen( $file_name, "r");
        
        while( !feof( $archivoID ) ){

            $linea      = fgets( $archivoID, 1024 );
            $porcion    = explode( "	", $linea );

            $cli_agr    = isset( $porcion[3] ) ? $porcion[3] : '';
            $cli_cod    = isset( $porcion[5] ) ? $porcion[5] : '';
            $tarp_cod   = isset( $porcion[11] ) ? $porcion[11] : '';

            if( $cli_agr === '12' ){

                $porciones[$cli_cod] = array(
                    'tarp_cod' => $tarp_cod,
                );  
            }
        }
        return $porciones;
    }

    function contacto_erp(): array
    {
        try {

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli_con.txt';

            $archivoID     = fopen( $file_name, "r" );

            $porciones      = [];

            $code_tar       = self::tarifa_json();

            while( !feof($archivoID)){

                $linea      = fgets($archivoID, 1024);

                $porcion    = explode( "	", $linea );
                $cli_agr    = isset( $porcion[0] ) ? $porcion[0] : '';
                $cli_cod    = isset( $porcion[2] ) ? $porcion[2] : '';
                $email = explode( ",", $porcion[8] );
                if( $porcion[0]  == '12' ){



                        $porciones[$porcion[2]] = array(
                            'Email'  => $email[0],
                            'cli_cod' => $porcion[2],
                        );    

                }  
            }

            return $porciones;
        }

        catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }


    function address_erp(): array
    {
        try {

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli_adr.txt';

            $archivoID     = fopen( $file_name, "r" );

            $porciones      = [];

            $code_tar       = self::tarifa_json();

            while( !feof($archivoID)){

                $linea      = fgets($archivoID, 1024);

                $porcion    = explode( "	", $linea );

                $cli_agr    = isset( $porcion[0] ) ? $porcion[0] : '';
                $pre_fix    = isset( $porcion[1] ) ? $porcion[1] : '';
                $cli_cod    = isset( $porcion[2] ) ? $porcion[2] : '';
                $num_adr    = isset( $porcion[3] ) ? $porcion[3] : '';
                $num_tlf1   = isset( $porcion[14] ) ? $porcion[14] : '';
                $num_tlf2   = isset( $porcion[15] ) ? $porcion[15] : '';
                $tip_por    = isset( $porcion[23] ) ? $porcion[23] : '';
                $cod_inc    = isset( $porcion[24] ) ? $porcion[24] : '';
                $tarp_cla   = isset( $porcion[46] ) ? $porcion[46] : '';
                $tard_cod   = isset( $porcion[51] ) ? $porcion[51] : '';

                if( $cli_agr  === '12' ){


                    $porciones[$cli_cod] = array(
                        'Código agrupador cliente'      => $cli_agr,
                        'Tipo'                          => $pre_fix,
                        'Número dirección'              => $num_adr,
                        'Teléfono 1'                    => $num_tlf1,
                        'Teléfono 2'                    => $num_tlf2,
                        'Email'                         => $email,
                        'Tipo de portes'                => $tip_por,
                        'Incoterm'                      => $cod_inc,
                        'Código agrupador tarifa'       => $tarp_cla,
                        'Código tarifa de descuentos'   => $tard_cod,
                        'Código tarifa de precios'      => $code_tar[$cli_cod]['tarp_cod'],
                    );    

                }  
            }
            return $porciones;
        }

        catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function import_user_erp()
    {    
        try {

            $files = array( 
                'mbl_out_cli.txt',
                'mbl_out_cli_div.txt',
                'mbl_out_cli_adr.txt',
                'mbl_out_cli_con.txt',
            ); 

            $arch = new SPconnectionErp( $files );

            $token_cron_user    = $_GET['token'];
            $cliente            = self::client_json();
            $contador_clinete   = count($cliente);
          

            $new_user           = 1;
            $update_user        = 1;
            $mensaje_usuario    = '';
            $email_subject = 'Usuarios Importados';
            if( $token_cron_user == 'de2333wcr345/wfqekcmriw4berw231fq23e23frwef3' ){

                $j = 1;
                for($i=0; $i<$contador_clinete; $i++){

                    if(!username_exists($cliente[$i]['Nif'])){

                        if(!email_exists($cliente[$i]['Email'])){


                            $user_data = array(
                                'user_login'    => $cliente[$i]['Nif'],
                                'user_email'    => $cliente[$i]['Email'],
                                'user_pass'     => '',
                                'first_name'    => $cliente[$i]['Nombre'],
                                'last_name'     => '',
                                'nickname'      => '',
                            );

                            $user_id = wp_insert_user( $user_data );



                            if($new_user == 1){
                                $mensaje_usuario .= '<strong> Usuarios importados </strong><br><br><br>';
                            }
                            if(!$user_id->errors){
                                $new_user++;
                            }

                            update_user_meta( $user_id, 'aditional_info_user', $cliente[$i] );

                            $user = get_user_by( 'id', $user_id );

                            $mensaje_usuario .= $new_user. ' ) ID: ' . $user_id . ' -> Nif [' . $cliente[$i]['Nif'] . ' ] -> ' . $cliente[$i]['Nombre'] . '<br><br>';  

                        }else{
							$mensaje_usuario .= 'Nif del usuario '.$cliente[$i]['Nif'].'-> ';
							$mensaje_usuario .= 'correo repetido '.$cliente[$i]['Email'].'<br>';
						}
                    }else{
                        /*
                        $the_user           = get_user_by('login', $cliente[$i]['Nif']);
                        $update_info_user   = get_user_meta( $the_user->ID, 'aditional_info_user', TRUE );

                        $nombre     = strcmp($cliente[$i]['Nombre'],        $update_info_user['Nombre']);
                        $telefono_1 = strcmp($cliente[$i]['Teléfono 1'],    $update_info_user['Teléfono 1']);
                        $telefono_2 = strcmp($cliente[$i]['Teléfono 2'],    $update_info_user['Teléfono 2']);

                        if($nombre !== 0 || $telefono_1 !== 0 || $telefono_2 !== 0){
                            if($update_user == 1){
                                $mensaje_usuario .= '<br> <strong> Usuarios actualizado </strong><br><br><br>';
                            }
                            $user_up = wp_update_user( [ 
                                'ID'            => $the_user->ID, 
                                'first_name'    => $cliente[$i]['Nombre']
                            ] );

                            $mensaje_usuario .= $update_user.' ) ID: ' . $user_up . ' -> Nif ['.$cliente[$i]['Nif'].' ] -> '.$cliente[$i]['Nombre'] . '<br><br>';


                            echo '<br>';

                            update_user_meta( $the_user->ID, 'aditional_info_user', $cliente[$i] );

                            $update_user++;
                        }
                        */
                    }


                }

                $mail = self::send_mail_import_product($mensaje_usuario, $email_subject);
                $dir = self::address_import_erp();
            }
        }

        catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }


    public function address_import_erp()
    {
        try {

            global $wpdb;

            $tabla = "{$wpdb->prefix}sp_addresses_user_erp";

            $file_name     = plugin_dir_path( __DIR__, 1 ) . 'includes/data/mbl_out_cli_adr.txt';

            $archivoID     = fopen( $file_name, "r");
            $subject = 'Direcciones Importadas';
            $mensaje_usuario = '<strong>Direcciones Importadas</strong><br>';
            $ra = 0;
            $rc = 0;
			$clients       = self::contacto_erp();
            while( !feof($archivoID)){

                $linea      = fgets( $archivoID, 1024 );
                $porcion    = explode( "	", iconv( "ISO-8859-15", "UTF-8", $linea ) );

                $cli_agr       = isset( $porcion[0] ) ? $porcion[0] : '';
                $cli_cod       = isset( $porcion[2] ) ? $porcion[2] : '';
                $num_dir       = isset( $porcion[3] ) ? $porcion[3] : '';
                $name_dir      = isset( $porcion[4] ) ? $porcion[4] : '';
                $type_calle    = isset( $porcion[5] ) ? $porcion[5] : '';
                $dir_1         = isset( $porcion[6] ) ? $porcion[6] : '';
                $num_calle     = isset( $porcion[7] ) ? $porcion[7] : '';
                $dir_2         = isset( $porcion[8] ) ? $porcion[8] : '';
                $postal_code   = isset( $porcion[9] ) ? $porcion[9] : '';
                $poblacion     = isset( $porcion[10] ) ? $porcion[10] : '';
                $email_dir     = isset( $porcion[17] ) ? $porcion[17] : '';
                $tel_dir       = isset( $porcion[14] ) ? $porcion[14] : '';
                $clientes      = isset( $clients[$cli_cod]['Email'] ) ? $clients : '';

                if( $cli_agr === '12' ){
        

                        $key = base64_encode( $cli_cod . '-' . $num_dir );

                        $datos = [
                            'cli_cod'       => $cli_cod,
                            'num_dir'       => $num_dir,
                            'name_dir'      => $name_dir,
                            'type_calle'    => $type_calle,
                            'dir_1'         => $dir_1,
                            'num_calle'     => $num_calle,
                            'dir_2'         => $dir_2,
                            'postal_code'   => $postal_code,
                            'poblacion'     => $poblacion,
                            'email_dir'     => $clientes,
                            'tel_dir'       => $tel_dir
                        ];

                        $query = "SELECT * FROM $tabla WHERE IdFilterDir = '$key' LIMIT 1";
                        $prod  = $wpdb->get_results( $query,ARRAY_A );

                        if( count( $prod) === 1 ){

                            $ra +=  $wpdb->update( $tabla, $datos, array( 'IdFilterDir' => $key ) );
                            
                        }else{

                            $datos['IdFilterDir'] = $key;
                            $rc +=  $wpdb->insert( $tabla, $datos );
                        }
                     
                }
            }
            $mensaje_usuario .= $rc > 0 ? $rc . ' Direcciones creadas<br>' : '0 Direcciones creadas<br>';
            $mensaje_usuario .= $ra > 0 ? $ra . ' Direcciones actualizadas<br>' : '0 Direcciones actualizadas<br>';

            $mail = self::send_mail_import_product($mensaje_usuario, $subject);
        } 

        catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    function erp_new_extra_user_profile_fields()
    { 
        ?>
        <h3><?php _e("Informacion adicional", "ss_shiping"); ?></h3>
        
        <table class="form-table">
            <tr>
                <th><label for="aditional_info_user"><?php _e("Informacion adicional", "ss_shiping"); ?></label></th>
                <td><input name="aditional_info_user" value="" style="width:25em;max-width:25em;"></td>
            </tr>
        </table>

        <?php
    }

    function erp_extra_user_profile_fields( $user )
    { 
        ?>
        <h3><?php _e("Informacion adicional", "ss_shiping"); ?></h3>
        
        <table class="form-table">
            <?php $list_info_user = get_the_author_meta( 'aditional_info_user', $user->ID )?>
            <?php foreach($list_info_user as $key => $item ):
                ?>

                <tr>
                    <th><label for="aditional_info_user"><?php echo $key; ?></label></th>
                    <td><p><?php echo $item;?></p></td>
                </tr>
            <?php endforeach;?>

        </table>
        <?php 
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
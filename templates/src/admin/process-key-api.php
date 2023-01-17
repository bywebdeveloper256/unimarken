<?php

global $wpdb;

$tabla = $wpdb->prefix . 'sp_conf_key_erp';

if( isset( $_POST['btnConfApiKey'] ) || isset( $_POST['btnConfApiKeyActualizar'] ) ){

    $email          = sanitize_email( $_POST["email_admin"] );
    $url_key        = esc_url( $_POST['url_key_erp'] );
    $key_client     = sanitize_key( $_POST['cliente_key_erp'] );
    $key_secret     = sanitize_key( $_POST['private_key_erp'] );
    $server_ftp     = sanitize_text_field( $_POST['server_ftp'] );
    $port_ftp       = sanitize_text_field( $_POST['port_ftp'] );
    $user_ftp       = sanitize_user( $_POST['user_ftp'] );
    $file_path_ftp  = sanitize_text_field( $_POST['file_path_ftp'] );
    $pass_ftp       = sanitize_text_field( $_POST['pass_ftp'] );

    $msg[0] = __('Error', 'iperp' );
    $msg[1] = '' ;
    $msg[2] = 'error';

    if ( !$email ) {
        
        $msg[1] = __('El email está vacío o no es correcto.', 'iperp' );
    }elseif( !$url_key ){

        $msg[1] = __('La Url está vacía o no es correcta.', 'iperp' );
    }elseif( !$key_client ){

        $msg[1] = __('La clave de Cliente está vacía o no es correcta.', 'iperp' );
    }elseif( !$key_secret ){

        $msg[1] = __('La clave secreta está vacía o no es correcta.', 'iperp' );
    }elseif( !$server_ftp ){

        $msg[1] = __('El servidor está vacío o no es correcto.', 'iperp' );
    }elseif( !$user_ftp ){

        $msg[1] = __('El Usuario está vacío o no es correcto.', 'iperp' );
    }elseif( !$pass_ftp ){

        $msg[1] = __('La Contraseña está vacía o no es correcta.', 'iperp' );
    }else{

        $datos = [
            'email_admin'   => $email,
            'url_key'       => $url_key,
            'key_client'    => $key_client,
            'key_Secret'    => $key_secret,
            'server_ftp'    => $server_ftp,
            'port_ftp'      => $port_ftp,
            'file_path_ftp' => $file_path_ftp,
            'user_ftp'      => $user_ftp,
            'pass_ftp'      => $pass_ftp,
        ];

        if( $_POST['id_key_erp'] ){

            $result = $wpdb->update( $tabla, $datos, array( 'IdconfKey' => 1 ) );
            
        }else{

            $result = $wpdb->insert( $tabla, $datos );
        }
    }

    if( $result ){

        $msg[0] = __('Exito', 'iperp' );
        $msg[1] = __('Se ha guardado con exito!.', 'iperp' );
        $msg[2] = 'success' ;
    }else{
        
        $msg[0] = __('Atención', 'iperp' );
        $msg[1] = __('No hubo cambios.', 'iperp' );
        $msg[2] = 'info' ;
    }
    
}

$query = "SELECT * FROM $tabla LIMIT 1";
$list  = $wpdb->get_results( $query, ARRAY_A );

if( !empty( $msg[1] ) ){ ?>

    <script>
        let title   = '<?= $msg[0]; ?>';
        let msg     = '<?= $msg[1]; ?>';
        let icon    = '<?= $msg[2]; ?>';

        jQuery(document).ready(function ($) {
            swal( title, msg, icon );
        });
    </script>

<?php }

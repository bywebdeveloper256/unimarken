<?php

namespace EskofrizImport;

class SPconnectionErp{

    private $server_ftp;

    private $port_ftp;

    private $user_ftp;

    private $pass_ftp;

    private $file_path_ftp;
    
    private $protocolo;

    private $connId;

    private $connection;

    private $sftp;

    private $url_path_conection;

    private $msg_error;

    private $path;

    public  $file_path; 

    function __construct( $files = array() )
    {
        $admin  = sp_get_results_table_conf_key_erp();
        // FTP server details
        $this->server_ftp       = $admin[0]["server_ftp"];

        $this->port_ftp         = intval( $admin[0]["port_ftp"] );

        $this->user_ftp         = $admin[0]["user_ftp"];

        $this->pass_ftp         = $admin[0]["pass_ftp"];

        $this->file_path_ftp    = $admin[0]["file_path_ftp"];

        $this->path             = plugin_dir_path( __DIR__, 1 ) . 'includes/data/';

        $this->sp_connection_erp( $files );
    }

    public function sp_connection_erp( $files )
    {
        switch ( $this->port_ftp )
        {
            case 21:
                $this->protocolo            = "ftp://";

                $this->connId               = ftp_connect( $this->server_ftp, $this->port_ftp );

                if( !$this->connId ){

                    $this->msg_error = __( 'Conexión fallida. Verifique la dirección del servidor.', 'iperp' );
                    return;
                }

                $this->connection           = ftp_login(  $this->connId, $this->user_ftp, $this->pass_ftp );

                if( !$this->connection ){

                    $this->msg_error = __( 'Conexión fallida. Verifique el usuario o la contraseña.', 'iperp' );
                    return;
                }

                if( !empty( $files ) ){

                    foreach( $files as $file_name ){
                    
                        $file_src           = $this->protocolo . $this->user_ftp . ":" . $this->pass_ftp . "@" . $this->server_ftp . $this->file_path_ftp . $file_name;
                        $this->file_path    = $this->path . $file_name;

                        $handle = fopen( $this->file_path, 'c+' );

                        ftp_fget( $this->connId, $handle, $file_src, FTP_ASCII, 0 );

                        fclose( $handle );
                    }
                }
                
                break;          

            case 22:
                if( !function_exists( 'ssh2_connect' ) )
                { 
                    die( 'No existe la funcion ssh2_connect.' );
                }
                
                $this->protocolo          = "ssh2.sftp://";

                $this->connId             = ssh2_connect( $this->server_ftp, $this->port_ftp );

                if( !$this->connId ){

                    $this->msg_error      = __( 'Conexión fallida. Verifique la dirección del servidor.', 'iperp' );
                    return;
                }

                $this->connection         = ssh2_auth_password( $this->connId, $this->user_ftp, $this->pass_ftp );

                if( !$this->connection ){
                    $this->msg_error = __( 'Conexión fallida. Verifique el usuario o la contraseña.', 'iperp' );
                    return;
                }

                $this->sftp               = ssh2_sftp( $this->connId );

                if( !$this->sftp ){

                    $this->msg_error = __( 'Ha ocurrido un error.', 'iperp' );
                    return;
                }else{

                    $this->msg_error      = __( 'Conectado', 'iperp' );
                }

                if( !empty( $files ) ){

                    foreach( $files as $file_name ){

                        $file_src           = $this->protocolo . $this->sftp . $this->file_path_ftp . $file_name;
                        $this->file_path    = $this->path . $file_name;

                        $stream = @fopen( $file_src , 'r' );

                        if( $stream ){

                            $contents = stream_get_contents( $stream );

                            $bytes = file_put_contents( $this->file_path, $contents );

                            @fclose($stream);
                        }
                    }
                }
                break;
        }
    }

    public function sp_msg_connection_erp()
    {
        return $this->msg_error;
    }

    public function sp_upload_order_txt_erp( $file_name )
    {
        $bytes = false;
        
        switch ( $this->port_ftp )
        {
            case 21:
                break;          

            case 22:
                if( !function_exists( 'ssh2_connect' ) )
                { 
                    die( 'No existe la funcion ssh2_connect.' );
                }

                if( !empty( $file_name ) ){

                    $file_src   = $this->protocolo . $this->sftp . '/input/' . $file_name;
                    $file_path  = get_stylesheet_directory() . '/orders/' . $file_name;

                    $stream = @fopen( $file_path , 'r' );

                    if( $stream ){

                        $contents = stream_get_contents( $stream );

                        $bytes = file_put_contents( $file_src, $contents );

                        @fclose($stream);
                    }
                }
                break;
        }
        return $bytes;
    }

    public function __destruct()
    {
        switch ( $this->port_ftp ) {
            case 21:
                ftp_close( $this->connId );
                break;
            case 22:
                if( $this->connId ){
                    ssh2_disconnect( $this->connId );
                }
                break;
        }
    }
}

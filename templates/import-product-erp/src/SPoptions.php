<?php

namespace EskofrizImport;

class SPoptions{
    public function init(){
        add_action( 'admin_menu', array( $this, 'sp_erp_create_admin_menu' ));
    }
    
    public function sp_erp_create_admin_menu() {
        add_submenu_page (
            'woocommerce',
            'API KEY ERP', 
            'API KEY ERP', 
            'manage_options', 
            plugin_dir_path(__FILE__).'/admin/add-key-api.php',
        );

    }

   

 }
<?php
/**
 * Plugin Name: Import product ERP
 * Description: Import and update product ERP
 * Version: 1.0.0
 * Author: Agencia SP
 * Text Domain: sp-wp-import
 */

$loader = require 'vendor/autoload.php';
$loader->addPsr4('EskofrizImport\\', 'src');

use EskofrizImport\SPImportData;
use EskofrizImport\SPpriceUpdate;
use EskofrizImport\SPoptions;
use EskofrizImport\SPusersImport;
use EskofrizImport\SPselectAddress;
use EskofrizImport\SPImportOrders;
use EskofrizImport\SPlistOrder;
use EskofrizImport\SPlistRegularShopping;

class EskofrizImportProductPlugin
{
    private SPImportData $createImportData;
    private SPpriceUpdate $priceUpdate;
    private SPoptions $options;
    private SPusersImport $usersImport;
    private SPselectAddress $selectAddress;
    private SPImportOrders $importOrders;
    private SPlistOrder $listOrder;
    private SPlistRegularShopping $listRegularShopping;

    public function __construct()
    {
        $this->usersImport = new SPusersImport();
        $this->usersImport->init();

        $this->listOrder = new SPlistOrder();
        $this->listOrder->init();

        $this->listRegularShopping = new SPlistRegularShopping();
        $this->listRegularShopping->init();

        $this->createImportData = new SPImportData();
        $this->createImportData->init();

        $this->importOrders = new SPImportOrders();
        $this->importOrders->init();

        $this->priceUpdate = new SPpriceUpdate();
        $this->priceUpdate->init();

        $this->selectAddress = new SPselectAddress();
        $this->selectAddress->init();

        $this->options = new SPoptions();
        $this->options->init();
    }
}

$importProduct = new EskofrizImportProductPlugin();

function sp_activate_plugin()
{
    global $wpdb;
	
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_price_discount_erp ( 
      `IdPrice` INT NOT NULL AUTO_INCREMENT, 
      `IdArt` VARCHAR(50) UNIQUE, 
      `cod_art` VARCHAR(50) NULL DEFAULT NULL, 
      `pre_dto` VARCHAR(50) NULL DEFAULT NULL, 
      `cli_cod` VARCHAR(50) NULL DEFAULT NULL, 
      `lin_cod` VARCHAR(50) NULL DEFAULT NULL, 
      `por_dt1` FLOAT(5,2) NULL DEFAULT NULL, 
      PRIMARY KEY (`IdPrice`));";
    $wpdb->get_results($sql);

    $sql_price = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_price_tar_erp ( 
        `IdPriceTar` INT NOT NULL AUTO_INCREMENT, 
        `IdArt` VARCHAR(100) UNIQUE, 
        `pre_dto` VARCHAR(50) NULL DEFAULT NULL, 
        `cod_art` VARCHAR(50) NULL DEFAULT NULL, 
        `tarp_cod` VARCHAR(50) NULL DEFAULT NULL, 
        `val_pre` FLOAT(5,2) NULL DEFAULT NULL, 
        PRIMARY KEY (`IdPriceTar`));";
      $wpdb->get_results($sql_price);
  
	$sql_conf_key = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_conf_key_erp ( 
		`IdconfKey`   	INT NOT NULL AUTO_INCREMENT, 
		`email_admin`	VARCHAR(100) NULL DEFAULT NULL,
		`url_key`     	VARCHAR(100) NULL DEFAULT NULL, 
		`key_client`  	VARCHAR(100) NULL DEFAULT NULL, 
		`key_Secret`  	VARCHAR(100) NULL DEFAULT NULL, 
		`server_ftp`  	VARCHAR(100) NULL DEFAULT NULL, 
		`user_ftp`    	VARCHAR(100) NULL DEFAULT NULL, 
        `file_path_ftp` VARCHAR(250) NULL DEFAULT NULL,
		`pass_ftp`    	VARCHAR(100) NULL DEFAULT NULL, 
	PRIMARY KEY (`IdconfKey`));";

    $wpdb->get_results($sql_conf_key);
}
register_activation_hook( __FILE__, 'sp_activate_plugin' );

function sp_enqueue_library()
{
	wp_enqueue_script( 'sweetalert', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js', array('jquery'), '2.11', true );
    wp_enqueue_script( 'erp-custom-js', plugins_url( 'assets/custom.js', __FILE__ ), array( 'jquery' ), '2.11', true );
}
add_action( 'admin_enqueue_scripts', 'sp_enqueue_library' );

function sp_enqueue_js_erp()
{
    wp_enqueue_script( 'erp-custom-js', plugins_url( 'assets/custom.js', __FILE__ ), array( 'jquery' ), '2.11', true );
}
add_action( 'wp_enqueue_scripts', 'sp_enqueue_js_erp' );
  
function sp_get_results_table_conf_key_erp( $where = '' ){

	global $wpdb;
	$table  = $wpdb->prefix . 'sp_conf_key_erp';
	$query 	= "SELECT * FROM $table $where";
	$result = $wpdb->get_results( $query, ARRAY_A );

	return $result;
}

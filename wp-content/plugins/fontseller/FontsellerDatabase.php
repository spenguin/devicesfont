<?php

include_once 'includes/FonsellerModel.php';
/**
 * Class FontsellerDatabase
 *
 * Wrapper for all sort of database interactions, including:
 *  - Creating database tables on install
 *  - Checking all needed tables exist on run
 *  - CRUD operations on the database
 *  - Migrating database versions base on the WP_OPTION 'fontseller_db_version'
 */
class FontsellerDatabase extends FontsellerModel {

    private $wpdb;
    private $font_families;
    private $fonts;
    private $_formats;
    private $font_format;
    //private $table_settings;
    private $orders;
    private $order_items;
    private $_payment_method;
    private $payment_meta;
    private $customer;
    private $fontsampler;
    private $helpers;
    private $layout;
    //private $settings_data_cols;

    function __construct( $wpdb, $fontsampler ) 
    {
        $this->wpdb = $wpdb;

        $this->families = $this->wpdb->prefix . 'fontseller_families';
        $this->fonts    = $this->wpdb->prefix . 'fontseller_fonts';
        $this->_formats = $this->wpdb->prefix . 'fontseller_formats';
        $this->font_format  = $this->wpdb->prefix . 'fontseller_font_x_formats';
        $this->orders   = $this->wpdb->prefix . 'fontseller_orders';
        $this->order_items  = $this->wpdb->prefix . 'fontseller_order_items';
        $this->_method  = $this->wpdb->prefix . 'fontseller_payment_method';
        $this->payments = $this->wpdb->prefix . 'fontseller_payments';
        $this->customers= $this->wpdb->prefix . 'fontseller_customers';

        //$this->fontsampler = $fontsampler;
        //$this->helpers     = new FontsamplerHelpers( $fontsampler );
        //$this->layout      = new FontsamplerLayout();
    }

	/*
	 * setup fontseller families table
	 */
	function create_families() {
		$sql = "CREATE TABLE " . $this->families . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar( 255 ) NOT NULL DEFAULT '',
                `slug` varchar( 255 ) NOT NULL DEFAULT '',
                `path` varchar( 255 ) NOT NULL DEFAULT '',
                `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL,
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }   
    
	/*
	 * setup fontseller fonts table
	 */
	function create_fonts() {
		$sql = "CREATE TABLE " . $this->fonts . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `familyId` int( 11 ) unsigned NOT NULL,
                `variantId` int( 11 ) unsigned NOT NULL DEFAULT '0',
                `price` float( 6,2 ) NOT NULL DEFAULT '000.00',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }   

	/*
	 * setup fontseller font formats table
	 */
	function create_formats() {
		$sql = "CREATE TABLE " . $this->_formats . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar( 255 ) NOT NULL,
                `ext` varchar( 255 ) NOT NULL,
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    } 
    
	/*
	 * setup fontseller font format join table
	 */
	function create_font_x_format() {
		$sql = "CREATE TABLE " . $this->font_format . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `fontid` int( 11 ) unsigned NOT NULL,
                `formatid` int( 11 ) unsigned NOT NULL
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }   
    
	/*
	 * setup fontseller orders table
	 */
	function create_orders() {
		$sql = "CREATE TABLE " . $this->orders . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `customerid` int( 11 ) unsigned NOT NULL,
                `created_at` TIMESTAMP NOT NULL,
                `status` int( 2 ) unsigned NOT NULL DEFAULT '0',
                `subtotal` float( 8,2 ) NOT NULL DEFAULT '00000.00',
                `discount` float( 8,2 ) NOT NULL DEFAULT '00000.00',
                `total` float( 8,2 ) NOT NULL DEFAULT '00000.00',
                `paymenttoken` varchar( 255 ) NOT NULL DEFAULT '',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }      
    
	/*
	 * setup fontseller order items table
	 */
	function create_items() {
		$sql = "CREATE TABLE " . $this->order_items . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `fontId` int( 11 ) unsigned NOT NULL,
                `formatId` int( 11 ) unsigned NOT NULL,
                `charge` float( 6,2 ) NOT NULL DEFAULT '000.00',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    } 

	/*
	 * setup payment method table
	 */
	function create_methods() {
		$sql = "CREATE TABLE " . $this->_method . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar( 255 ) NOT NULL DEFAULT '',
                `slug` varchar( 255 ) NOT NULL DEFAULT '',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }    

	/*
	 * setup payment table
	 */
	function create_payments() {
		$sql = "CREATE TABLE " . $this->payments . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `methodid` int( 11 ) unsigned NOT NULL,
                `meta_key` varchar( 255 ) NOT NULL DEFAULT '',
                `meta_value` varchar( 255 ) NOT NULL DEFAULT '',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }  
    
	/*
	 * setup fontseller customers table
	 */
	function create_customers() {
		$sql = "CREATE TABLE " . $this->customers . " (
                `id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar( 255 ) NOT NULL DEFAULT '',
                `email` varchar( 255 ) NOT NULL DEFAULT '',
                `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
                `status` int( 1 ) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY ( `id` )
			) DEFAULT CHARSET=utf8";

		$this->wpdb->query( $sql );
    }   
    
	/**
	 * Helper to check if tables exist
	 * TODO: check if tables are in the correct structure
	 */
	function check_table_exists( $table ) {
		$this->wpdb->query( "SHOW TABLES LIKE '" . $table . "'" );

		return 0 == $this->wpdb->num_rows ? false : true;
    } 
    
	/**
	 * Helper that checks for the existance of all required fontsampler tables and where missing creates them
	 */
	function check_and_create_tables() {
		// check the fontsampler tables exist, and if not, create them now
		if ( ! $this->check_table_exists( $this->families ) ) {
			$this->create_families();
		}
		if ( ! $this->check_table_exists( $this->fonts ) ) {
			$this->create_fonts();
		}
		if ( ! $this->check_table_exists( $this->font_format ) ) {
			$this->create_font_x_format();
		}
		if ( ! $this->check_table_exists( $this->_formats ) ) {
			$this->create_formats();
        }
		if ( ! $this->check_table_exists( $this->orders ) ) {
			$this->create_orders();
        }  
		if ( ! $this->check_table_exists( $this->order_items ) ) {
			$this->create_items();
        } 
		if ( ! $this->check_table_exists( $this->_method ) ) {
			$this->create_methods();
        } 
		if ( ! $this->check_table_exists( $this->payments ) ) {
			$this->create_payments();
        } 
		if ( ! $this->check_table_exists( $this->customers ) ) {
			$this->create_customers();
		}                                       
	}    

}    

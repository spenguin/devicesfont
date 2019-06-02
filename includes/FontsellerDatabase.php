<?php

/**
 * Class FontsellerDatabase
 *
 * Wrapper for all sort of database interactions, including:
 *  - Creating database tables on install
 *  - Checking all needed tables exist on run
 *  - CRUD operations on the database
 *  - Migrating database versions base on the WP_OPTION 'fontseller_db_version'
 */
class FontsellerDatabase {

    private $wpdb;
    private $orders;
    private $order_items;
    private $_payment_method;
    private $payment_meta;
    private $customer;
    private $helpers;
	private $layout;
	private $version	= '0.0.1';
    //private $settings_data_cols;

    function __construct() 
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->orders   = $this->wpdb->prefix . 'fontseller_orders';
        $this->order_items  = $this->wpdb->prefix . 'fontseller_order_items';
        $this->_method  = $this->wpdb->prefix . 'fontseller_payment_method';
        $this->payments = $this->wpdb->prefix . 'fontseller_payments';
        $this->customers= $this->wpdb->prefix . 'fontseller_customers';
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
    function check_and_create_tables() 
    {
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

	/**
	 * Check database version in options; run in tables if version not up to current
	 */
	static function run()
	{
        $db = new FontsellerDatabase;
        $db_v   = get_option( 'fs_db_version' );
        if( FALSE == $db_v || $db_v < $db->version )
        {
            $db->check_and_create_tables();
            $db->update_tables( $db->version );
        }
    }

    function update_tables( $version )
    {
        global $wpdb;
        switch( $version )
        {
            case '0.0.1':
                break;
        }
    }

}    

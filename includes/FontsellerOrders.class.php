<?php

/**
 * Class FontsellerOrders
 *
 * Class for taking orders and fulfilling them
 */
class FontsellerOrders {

	//private $fontseller;

	function __construct() {
		//$this->fontseller = $fontseller;
	}
	
	function writeOrder( $fonts )
	{
		global $wpdb;
		if( !isset( $this->_orderId ) ) 
		{

			$tableOrder	= $wpdb->prefix . 'fontseller_orders'; 
			// Create Order
			$wpdb->insert( 
				$tableOrder,
				[
					'created_at'	=> date("Y-m-d H:i:s")
				]
			);
			$this->_orderId	= $wpdb->insert_id;
			// Create Order Items 

			$this->_total	= 0;
			$tableItems	= $wpdb->prefix . 'fontseller_order_items';
			foreach( $fonts as $k => $f )
			{
				$wpdb->insert(
					$tableItems,
					[
						'orderId'	=> $this->_orderId,
						'fontId'	=> $k,
						'charge'	=> $f['price']
					]
				);
				$this->_total	+= $f['price'];
			}
			$wpdb->update( 
				$tableOrder,
				['subtotal' => $this->_total],
				['id'		=> $this->_orderId]
			);
		}
	}

	function generateOrder( $orderId, $paymentToken )
	{
		global $wpdb;

		$table	= $wpdb->prefix . 'fontseller_orders'; 
		$key	= $this->generateKey();

		$wpdb->update( 
			$table,
			[
				'paymenttoken'	=> $paymentToken,
				'orderkey'		=> $key
			],
			['id' =>$orderId]
		);
		return $key;
	}

	function gatherOrder( $orderId )
	{
		global $wpdb;

		$table	= $wpdb->prefix . 'fontseller_order_items';
		$sql	= "SELECT * FROM $table WHERE orderId = $orderId"; 
		$res	= $wpdb->get_results( $sql );
		return $res;
	}

	function emailOrder( $email, $key )
	{

	}

	function getOrderItems( $orderId )
	{
		$fontIds	= $this->gatherOrder( $orderId );
		$o			= [];
		foreach( $fontIds as $f )
		{
			$font	= get_post( $f->fontId );
			$formats= explode( ',', get_post_meta( $f->fontId, 'formats', TRUE ) );
			foreach( $formats as $format )
			{
				$o[]	= FS_UPLOAD . 'recorded/' . $font->post_title . '.' . $format;
			}
		}
		return $o;
	}

	function generateKey()
	{
		global $wpdb;
		$table	= $wpdb->prefix . 'fontseller_orders';
		while( 1 < 2 )
		{
			$key	= wp_generate_uuid4();
			$res	= $wpdb->get_row( "SELECT * FROM $table WHERE orderkey = $key" );
			if( empty( $res ) ) return $key;
		}
	}
	function createZipFile( $orderId, $fonts )
	{
		//Create an object from the ZipArchive class.
		$zipArchive		= new ZipArchive();
		$zipBaseName	= 'Order' . $orderId . '.zip';
		
		//The full path to where we want to save the zip file.
		$zipFilePath = FS_UPLOAD . $zipBaseName;
		
		//Call the open function.
		if( TRUE !== $zipArchive->open( $zipFilePath,  ZipArchive::CREATE ) )
		{
			exit( 'file not created' );
		}; 
		
		
		//Add our files to the archive by using the addFile function.
		foreach( $fonts as $font )
		{	
			$baseName	= basename( $font );
			//Add the file in question using the addFile function.
			$zipArchive->addFile( $font, $baseName );
		}
		
		//Finally, close the active archive.
		$zipArchive->close(); 
		
		//Get the basename of the zip file.
		//$zipBaseName = basename($zipFilePath); exit($zipBaseName);
		
		//Set the Content-Type, Content-Disposition and Content-Length headers.
		header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=" . $zipBaseName ); //exit( $zipBaseName );
		header("Content-Length: " . filesize( $zipFilePath ) );
		
		//Read the file data and exit the script.
		readfile( $zipFilePath );
		exit;
	}
}

add_action( 'wp', 'downloadOrder' );

function downloadOrder()
{	
	global $wpdb;
	
	if( FALSE !== strpos(  $_SERVER['REQUEST_URI'], 'downloadOrder' ) )
	{
		$tmp	= explode( '/', $_SERVER['REQUEST_URI'] );
		$key	= end( $tmp );
		$table	= $wpdb->prefix . 'fontseller_orders';

		// Get $key from orders table
		$sql	= "SELECT * FROM $table WHERE orderkey = '$key'";
		$res 	= $wpdb->get_row( $sql ); 
		if( empty( $res ) )
		{
			echo '<p>Your key is invalid</p>';
		}
		elseif( $res->downloaded )
		{
			echo '<p>This order has already been downloaded';
		}
		else
		{	
			//echo '<p>Download beginning</p>';
	/*		$wpdb->update( 
				$table,
				['downloaded' => date( 'Y-m-d H:i:s' )],
				['id' => $res->id]
			);*/
			$Order	= new FontsellerOrders;
			$fonts	= $Order->getOrderItems( $res->id );
			$Order->createZipFile( $res->id, $fonts );
		}
	}
}
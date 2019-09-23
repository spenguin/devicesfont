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

	function generateOrder( $orderId, $paymentToken, $customerId )
	{
		global $wpdb;

		$table	= $wpdb->prefix . 'fontseller_orders'; 
		$key	= $this->generateKey();

		$wpdb->update( 
			$table,
			[
				'customerid'	=> $customerId,
				'status'		=> 1,
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
		$message	= renderEmail( $key );


		$subject = 'Your Device font order';
		
		$headers = "From: info@soaringpenguin.com\r\n";
		$headers .= "Reply-To: info@soaringpenguin.com\r\n";
		$headers .= "CC: info@soaringpenguin.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		mail($to, $subject, $message, $headers);		
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

function renderEmail( $key )
{
	ob_start(); ?>
		<!doctype html>
		<html>
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>Your Device font order</title>
				<style>
				/* -------------------------------------
					GLOBAL RESETS
				------------------------------------- */
				
				/*All the styling goes here*/
				
				img {
					border: none;
					-ms-interpolation-mode: bicubic;
					max-width: 100%; 
				}
				body {
					background-color: #f6f6f6;
					font-family: sans-serif;
					-webkit-font-smoothing: antialiased;
					font-size: 14px;
					line-height: 1.4;
					margin: 0;
					padding: 0;
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%; 
				}
				table {
					border-collapse: separate;
					mso-table-lspace: 0pt;
					mso-table-rspace: 0pt;
					width: 100%; }
					table td {
					font-family: sans-serif;
					font-size: 14px;
					vertical-align: top; 
				}
				/* -------------------------------------
					BODY & CONTAINER
				------------------------------------- */
				.body {
					background-color: #f6f6f6;
					width: 100%; 
				}
				/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
				.container {
					display: block;
					margin: 0 auto !important;
					/* makes it centered */
					max-width: 580px;
					padding: 10px;
					width: 580px; 
				}
				/* This should also be a block element, so that it will fill 100% of the .container */
				.content {
					box-sizing: border-box;
					display: block;
					margin: 0 auto;
					max-width: 580px;
					padding: 10px; 
				}
				/* -------------------------------------
					HEADER, FOOTER, MAIN
				------------------------------------- */
				.main {
					background: #ffffff;
					border-radius: 3px;
					width: 100%; 
				}
				.wrapper {
					box-sizing: border-box;
					padding: 20px; 
				}
				.content-block {
					padding-bottom: 10px;
					padding-top: 10px;
				}
				.footer {
					clear: both;
					margin-top: 10px;
					text-align: center;
					width: 100%; 
				}
					.footer td,
					.footer p,
					.footer span,
					.footer a {
					color: #999999;
					font-size: 12px;
					text-align: center; 
				}
				/* -------------------------------------
					TYPOGRAPHY
				------------------------------------- */
				h1,
				h2,
				h3,
				h4 {
					color: #000000;
					font-family: sans-serif;
					font-weight: 400;
					line-height: 1.4;
					margin: 0;
					margin-bottom: 30px; 
				}
				h1 {
					font-size: 35px;
					font-weight: 300;
					text-align: center;
					text-transform: capitalize; 
				}
				p,
				ul,
				ol {
					font-family: sans-serif;
					font-size: 14px;
					font-weight: normal;
					margin: 0;
					margin-bottom: 15px; 
				}
					p li,
					ul li,
					ol li {
					list-style-position: inside;
					margin-left: 5px; 
				}
				a {
					color: #3498db;
					text-decoration: underline; 
				}
				/* -------------------------------------
					BUTTONS
				------------------------------------- */
				.btn {
					box-sizing: border-box;
					width: 100%; }
					.btn > tbody > tr > td {
					padding-bottom: 15px; }
					.btn table {
					width: auto; 
				}
					.btn table td {
					background-color: #ffffff;
					border-radius: 5px;
					text-align: center; 
				}
					.btn a {
					background-color: #ffffff;
					border: solid 1px #3498db;
					border-radius: 5px;
					box-sizing: border-box;
					color: #3498db;
					cursor: pointer;
					display: inline-block;
					font-size: 14px;
					font-weight: bold;
					margin: 0;
					padding: 12px 25px;
					text-decoration: none;
					text-transform: capitalize; 
				}
				.btn-primary table td {
					background-color: #3498db; 
				}
				.btn-primary a {
					background-color: #3498db;
					border-color: #3498db;
					color: #ffffff; 
				}
				/* -------------------------------------
					OTHER STYLES THAT MIGHT BE USEFUL
				------------------------------------- */
				.last {
					margin-bottom: 0; 
				}
				.first {
					margin-top: 0; 
				}
				.align-center {
					text-align: center; 
				}
				.align-right {
					text-align: right; 
				}
				.align-left {
					text-align: left; 
				}
				.clear {
					clear: both; 
				}
				.mt0 {
					margin-top: 0; 
				}
				.mb0 {
					margin-bottom: 0; 
				}
				.preheader {
					color: transparent;
					display: none;
					height: 0;
					max-height: 0;
					max-width: 0;
					opacity: 0;
					overflow: hidden;
					mso-hide: all;
					visibility: hidden;
					width: 0; 
				}
				.powered-by a {
					text-decoration: none; 
				}
				hr {
					border: 0;
					border-bottom: 1px solid #f6f6f6;
					margin: 20px 0; 
				}
				/* -------------------------------------
					RESPONSIVE AND MOBILE FRIENDLY STYLES
				------------------------------------- */
				@media only screen and (max-width: 620px) {
					table[class=body] h1 {
					font-size: 28px !important;
					margin-bottom: 10px !important; 
					}
					table[class=body] p,
					table[class=body] ul,
					table[class=body] ol,
					table[class=body] td,
					table[class=body] span,
					table[class=body] a {
					font-size: 16px !important; 
					}
					table[class=body] .wrapper,
					table[class=body] .article {
					padding: 10px !important; 
					}
					table[class=body] .content {
					padding: 0 !important; 
					}
					table[class=body] .container {
					padding: 0 !important;
					width: 100% !important; 
					}
					table[class=body] .main {
					border-left-width: 0 !important;
					border-radius: 0 !important;
					border-right-width: 0 !important; 
					}
					table[class=body] .btn table {
					width: 100% !important; 
					}
					table[class=body] .btn a {
					width: 100% !important; 
					}
					table[class=body] .img-responsive {
					height: auto !important;
					max-width: 100% !important;
					width: auto !important; 
					}
				}
				/* -------------------------------------
					PRESERVE THESE STYLES IN THE HEAD
				------------------------------------- */
				@media all {
					.ExternalClass {
					width: 100%; 
					}
					.ExternalClass,
					.ExternalClass p,
					.ExternalClass span,
					.ExternalClass font,
					.ExternalClass td,
					.ExternalClass div {
					line-height: 100%; 
					}
					.apple-link a {
					color: inherit !important;
					font-family: inherit !important;
					font-size: inherit !important;
					font-weight: inherit !important;
					line-height: inherit !important;
					text-decoration: none !important; 
					}
					#MessageViewBody a {
					color: inherit;
					text-decoration: none;
					font-size: inherit;
					font-family: inherit;
					font-weight: inherit;
					line-height: inherit;
					}
					.btn-primary table td:hover {
					background-color: #34495e !important; 
					}
					.btn-primary a:hover {
					background-color: #34495e !important;
					border-color: #34495e !important; 
					} 
				}
				</style>
			</head>
			<body class="">
				<span class="preheader">Your Device font order.</span>
				<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
				<tr>
					<td>&nbsp;</td>
					<td class="container">
					<div class="content">

						<!-- START CENTERED WHITE CONTAINER -->
						<table role="presentation" class="main">

						<!-- START MAIN CONTENT AREA -->
						<tr>
							<td class="wrapper">
							<table role="presentation" border="0" cellpadding="0" cellspacing="0">
								<tr>
								<td>
									<p>Thank you for your order. </p>
									<p>If you haven't already downloaded your font package, please <a href="<?php echo site_url(); ?>/downloadOrder/<?php echo $key; ?>"><?php echo site_url(); ?>/downloadOrder/<?php echo $key; ?></a></p>
								</td>
								</tr>
							</table>
							</td>
						</tr>

						<!-- END MAIN CONTENT AREA -->
						</table>
						<!-- END CENTERED WHITE CONTAINER -->

						<!-- START FOOTER -->
						<div class="footer">
						<table role="presentation" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td class="content-block">
								<span class="apple-link">Device, 7 Blake Mews Kew Gardens TW9 3GA United Kingdom </span>
							</td>
							</tr>
						</table>
						</div>
						<!-- END FOOTER -->

					</div>
					</td>
					<td>&nbsp;</td>
				</tr>
				</table>
			</body>
		</html>
	<?php return ob_get_clean();
}
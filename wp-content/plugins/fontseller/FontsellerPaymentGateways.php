<?php

/**
 * Class FontsellerPaymentGateway
 *
 * Class for connecting to payment gateways and handling information exchange
 */
class FontsellerPaymentGateway {

	private $fontseller;

	function __construct( $fontseller ) {
		$this->fontseller = $fontseller;
    }
}
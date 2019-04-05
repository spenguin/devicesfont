<?php

/**
 * Class FontsellerOrders
 *
 * Class for taking orders and fulfilling them
 */
class FontsellerOrders {

	private $fontseller;

	function __construct( $fontseller ) {
		$this->fontseller = $fontseller;
    }
}
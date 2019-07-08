<?php
/**
 * Display checkout
 */ //var_dump( $fonts );
$return = site_url(). '/payment-complete';  // For initial testing purposes

?>
<div class="container">
    <div class="row">
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="info@soaringpenguinpress.com">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="item_name" value="Order <?php echo $Order->_orderId; ?>">
            <input type="hidden" name="amount" value="<?php echo $Order->_total; ?>">
            <input type="hidden" name="shipping" value="0">
            <input type="hidden" name="currency_code" value="GBP">
            <input type="hidden" name="custom" value="<?php echo $Order->_orderId; ?>">
            <input type="hidden" name="return" value="<?php echo site_url(); ?>/order-confirmed">   
            <input type="hidden" name="cancel_return" value="<?php echo site_url(); ?>/checkout" />  
            <input type="hidden" name="rm" value="2" />       
            <div class="form-group">
                <p>Upon successful completion of your payment, you will receive an email with a download link to receive your purchase.</p>
            </div>
            <div class="form-group">
                <p>Total Charge: <?php echo number_format( $Order->_total, 2 ); ?></p>
            </div>
            <div class="form-group">
                <label for="name">Your name: </label>
                <input type="text" name="name" class="form-control" />
                <label for="name">Your email: </label>
                <input type="email" name="email" class="form-control" />
            </div>
            <button type="submit" class="button button-primary" name="payment">Make payment</button>
            <p>You will be taken to Paypal to complete the transaction</p>
        </form>
        
    </div>
</div>
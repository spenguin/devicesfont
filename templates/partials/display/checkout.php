<?php
/**
 * Display checkout
 */ //var_dump( $fonts );
$return = site_url(). '/payment-complete';  // For initial testing purposes
// [FIX] - Collect buyer details and offer to create an account for future purposes. Also add newsletter signup 

?>
<div class="container">
    <div class="row">
        <form action="<?php echo $ppUrl; ?>" method="post" class="checkout">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="<?php echo $account; ?>">
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
                <p>Total Charge: &pound;<?php echo number_format( $Order->_total, 2 ); ?></p>
            </div>
            <div class="form-group">
                <label for="name">Your name: </label>
                <input type="text" name="name" class="form-control" />
                <label for="address">Your address: </label>
                <textarea name="address" class="form-control"></textarea>
                <label for="country">Country: </label>
                <select name="country" class="form-control"><?php echo countryList(); ?></select>
                <label for="name">Your email: </label>
                <input type="email" name="email" class="form-control" required/>
            </div>
            <div class="form-group">
                <p>To proceed, you must accept our <a href="#modal-agreement">End User License Agreement</a><span class="note"> (A copy of this Agreement will be bundled with your order.)</span></p>
                <p><input type="checkbox" name="agreement" value="1" class="form-control" required />I have read the End User License Agreement and agree.</p>
            </div>
            <button type="submit" class="button button-primary" name="payment">Make payment</button>
            <p>You will be taken to Paypal to complete the transaction</p>
        </form>
        
    </div>
</div>
<!-- modal content -->
<?php
    $post   = get_page_by_title( 'End User License Agreement' );
?>
<div id="modal-agreement" class="modal-popup">
    <div class="modal-content">
        <div class="header">
            <h2><?php echo $post->post_title; ?></h2>
            <a href="#" class="btn">Close</a>
        </div>
        <div class="copy">
            <?php echo $post->post_content; ?>				
        </div>
        <div class="cf footer">
            <a href="#" class="btn">Close</a>
        </div>
    </div>
    <div class="overlay"></div>
</div>
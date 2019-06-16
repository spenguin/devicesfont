<?php
/**
 * Display checkout
 */ //var_dump( $fonts );
?>
<div class="container">
    <div class="row">
        <form>
            <label for="name">Your name: </label><input type="text" name="name" />
            <label for="name">Your email: </label><input type="email" name="email" />
            <input type="submit" class="button button-primary" name="payment" value="Make Payment">
        </form>
        
        <p>Upon successful completion of your payment, you will receive an email with a download link to receive your purchase.</p>
    </div>
</div>
<?php
/**
 * Display cart
 */ //var_dump( $fonts );
?>
<div class="container">
    <div class="row">
        <table class="cart">
            <thead>
                <tr>
                    <td>Item</td>
                    <td>Charge</td>
                    <td>Remove</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $fonts as $id => $f ): 
                        $total  += $f['price'];
                    ?>
                        <tr>
                            <td><?php echo $f['name']; ?></td>
                            <td><?php echo number_format( $f['price'], 2 ); ?></td>
                            <td><a href="<?php echo site_url( '/cart/?remove=' . $id ); ?>">Remove</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>Total</td>
                        <td>&pound;<?php echo number_format( $total, 2 ); ?></td>
                        <td><a href="<?php echo site_url( '/checkout/' ); ?>" class="button button-primary">Continue to Checkout</td>
                    </tr>
            </tbody>

        </table>

    </div>
</div>
<?php
/**
 * Display cart
 */ //var_dump( $fonts );
?>
<div class="container">
    <div class="row">
        <div class="divTable cart">
            <div class="divTableHeading">
                <div class="divTableRow">
                    <div class="divTableHead cart_item">Item</div>
                    <div class="divTableHead cart_charge">Charge</div>
                    <div class="divTableHead">Remove</div>
                </div>
            </div>
            <div class="divTableBody">
                <?php 
                    $even   = FALSE;
                    foreach( $fonts as $id => $f ):
                        $total  += $f['price'];
                    ?>
                        <div class="divTableRow <?php echo $even ? 'shade' : ''; ?>">
                            <div class="divTableCell cart_item"><?php echo $f['name']; ?></div>
                            <div class="divTableCell cart_charge"><?php echo number_format( $f['price'], 2 ); ?></div>
                            <div class="divTableCell"><a href="<?php echo site_url( '/cart/?remove=' . $id ); ?>"><span class="rm_fullscreen">Remove</span><span class="rm_mobile">X</span></a></div>
                        </div>
                <?php
                        $even   = !$even; 
                    endforeach; ?>
                <div class="divTableRow <?php echo $even ? 'shade' : ''; ?>">
                    <div class="divTableCell cart_item">Total</div>
                    <div class="divTableCell cart_charge">&pound;<?php echo number_format( $total, 2 ); ?></div>
                    <div class="divTableCell"><a href="<?php echo site_url( '/checkout/' ); ?>" class="button button-primary"><span class="ck_fullscreen">Continue to Checkout</a></div>
                </div>     
                <div class="divTableRow <?php echo $even ? 'shade' : ''; ?> ck_mobile">
                    <div class="divTableCell"><a href="<?php echo site_url( '/checkout/' ); ?>" class="button button-primary">Continue to Checkout</a></div>
                </div>                           
            </div>            
        </div>
    </div>
</div>
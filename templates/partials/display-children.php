<?php
/**
 * Display all Fonts of Family
 */ //var_dump( $prices );
?>
<div class="container">
    <div class="row">
        <h1><?php echo $parent->post_title; ?></h1>
        <form action="<?php echo site_url(); ?>/cart" method="post" class="font-buy-all_form">
            <p class="variant-count">A <?php echo $standard; ?> font with <?php echo $c = count( $fonts ); ?> variant <?php echo ( 1 == $c ) ? '' : 's'; ?> | &pound;<?php echo number_format( $prices[strtolower( $standard )] * $c, 2);?>
            <span class="font_buy"><input type="submit" class="button button-primary" name="addToCart" value="Add Font Set to Cart"></p>
        </form>
        
    </div>
    <div class="row">
        <div class="font-child-list">
            <?php foreach( $fonts as $title => $font ): ?>
                <style>
                    @font-face{ 
                        font-family: "<?php echo $title; ?>";
                        src: url( "<?php echo FS_FONTS . str_replace( ' ', '-', $title ); ?>.woff") format('woff')
                    }
                    .font-child-list-entry_sample.<?php echo $font['slug']; ?> {
                        font-family: "<?php echo $title; ?>";
                    }

                </style>
                <div class="font-child-list-entry">
                    <p class="details">
                        <?php echo $title; ?> |
                        <?php echo 'Formats: ' . $font['formats'] . ' |'; ?>
                        <?php echo empty( $font['description'] ) ? '' : $font['description'] . ' |'; ?>
                        &nbsp;&pound;<?php echo number_format( $prices[strtolower( $standard )], 2 ) . ' |'; ?>
                    </p>
                    <div class="font-child-list-entry_sample <?php echo $font['slug']; ?>">
                        ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890,.;:?!%&{()}*$@
                    </div>
                    <div class="font-child-list-entry_buy">
                        <form action="<?php echo site_url(); ?>/cart" method="post">
                            <input type="hidden" name="fontId" value="<?php echo $font['id']; ?>" />
                            <span class="font_buy"><input type="submit" class="button button-primary" name="addToCart" value="Add Font to Cart">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
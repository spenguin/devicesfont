<?php
/**
 * Display all Fonts of Family
 */ //var_dump( $prices );
//var_dump( $fonts );
$fontPath       = FS_RECORDED . $repFont;
$repFontSize    = intval( get_option( 'repFontSize' ) );
$fontAdj        = get_post_meta( $fontId, 'fontAdj', TRUE ); 

?>
<div class="font-child_heading">
    <div class="font-child_heading-hero">
        <h1><?php echo $parent->post_title; ?></h1>
        <?php if( file_exists( $fontPath ) ): ?>
            <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, $repFontSize, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", $fontAdj ) ); ?>" />  
        <?php endif; ?>
    </div>
    <div class="font-child_heading-hero-sample">
        <?php if( file_exists( $fontPath ) ): ?>
            <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, 25, "the quick brown fox jumps over the lazy dog", $fontAdj ) ); ?>" />  
            <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, 25, "THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG", $fontAdj ) ); ?>" />  
        <?php endif; ?>            
    </div>
</div>
<form action="<?php echo site_url(); ?>/cart" method="post" class="font-buy-all_form">
    <input type="hidden" name="fontId" value="<?php echo join( ',', $fontIds ); ?>" />
    <p class="variant-count">A <?php echo $standard; ?> font set with <?php echo $c = count( $fonts ); ?> variant<?php echo ( 1 == $c ) ? '' : 's'; ?> | &pound;<?php echo number_format( $prices[strtolower( $standard )] * $c, 2);?>
    <span class="font_buy"><input type="submit" class="button button-primary" name="addToCart" value="Add Font Set to Cart"></p>
</form>
<div class="font-child-list">
    <?php foreach( $fonts as $title => $font ): 
        // Get rid of WOFF format [FIX]
        $tmp    = explode( ',', $font['formats'] );
        $tmp    = array_diff( $tmp, ['woff'] );
        $font['formats']    = join( ',', $tmp );
        $fontData   = get_post( $font['id'] );
        $fontFile   = $fontData->post_title . '.otf'; 
        $fontPath   = FS_RECORDED . $fontFile;
        ?>
        <div class="font-child-list-entry">
            <div class="font-child-list-entry_buy">
                <form action="<?php echo site_url(); ?>/cart" method="post">
                    <input type="hidden" name="fontId" value="<?php echo $font['id'] . ':' . $prices[strtolower( $standard )]; ?>" />
                    <span class="font_buy"><input type="submit" class="button button-primary" name="addToCart" value="Add Font to Cart">
                </form>
            </div>            
            <div class="details_fullscreen">
                <h2><?php echo $title; ?></h2>&nbsp;|
                <?php echo 'Formats: ' . $font['formats'] . ' |'; ?>
                <?php echo empty( $font['description'] ) ? '' : $font['description'] . ' |'; ?>
                &nbsp;&pound;<?php echo number_format( $prices[strtolower( $standard )], 2 ) . ' |'; ?>
            </div>
            <div class="details_mobile">
                <h4><?php echo $title; ?></h4><br />
                <?php echo 'Formats: ' . $font['formats'] . '<br />'; ?>
                <?php echo empty( $font['description'] ) ? '' : $font['description'] . '<br />'; ?>
                &nbsp;&pound;<?php echo number_format( $prices[strtolower( $standard )], 2 ) . '<br />'; ?>
            </div>                    
            <div class="font-child-list-entry_sample">
                <?php
                    // Need three sample images ?>
                <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, 40, "abcdefghijklmnopqrstuvwxyz", $fontAdj ) ); ?>" />  
                <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, 40, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", $fontAdj ) ); ?>" />  
                <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, 40, "1234567890,.;:?!%&{()}*$@", $fontAdj ) ); ?>" />  
            </div>

        </div>
    <?php endforeach; ?>
</div>
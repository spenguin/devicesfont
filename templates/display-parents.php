<?php
/**
 * Display parent fonts
 */ //var_dump( $fonts );
$repDefaultFontSize    = intval( get_option( 'repFontSize' ) ); //var_dump( $repFontSize );
?>
<!--    <div class="container">
        <div class="row">-->
            <div class="font-list">
                <?php foreach( $fonts as $fontId => $font ):
                    // Need to get repSize for set
                    $fontPath       = FS_RECORDED . $font['repFont']; //'C:\wamp64\www\wp_hughes\wp-content\uploads\fontseller\recorded\/' . $font['repFont'];
                    //$repFontSetSize = get_post_meta( $fontId, 'repFontSize', TRUE );
                    //$repFontSize    = ( !empty( $repFontSetSize ) ) ? $repFontSetSize : $repDefaultFontSize; //var_dump( $repFontSize );
                    $repFontSize    = $repDefaultFontSize;
                    $fontAdj        = get_post_meta( $fontId, 'fontAdj', TRUE ); 
                    ?>
            <!--        <style>
                        @font-face{ 
                            font-family: "<?php echo $font['title']; ?>";
                            src: url( "<?php echo site_url(); ?>/wp-content/uploads/fontseller/recorded/<?php echo $font['repFont']; ?>" ) format('woff')
                        }
                        .font-list-entry_sample.<?php echo $font['slug']; ?> {
                            font-family: "<?php echo $font['title']; ?>";
                        }

                    </style>-->
                    <a href="/fonts?fontFamily=<?php echo $font['slug']; ?>">
                        
                        <div class="font-list-entry">
                            <h3><?php echo $font['title']; ?></h3>
                            <div class="font-list-entry_sample">
                                <?php if( file_exists( $fontPath ) ): ?>
                                    <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $fontPath, $repFontSize, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", $fontAdj ) ); ?>" />  
                                <?php endif; ?>
                            </div>
                            <div class="font-list-entry_standard">
                                <?php echo $font['standard']; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="font-list_pagination">
                <?php renderPagination( $pageNo, $pages  );  ?>
            </div>            
<!--        </div>
    </div>-->
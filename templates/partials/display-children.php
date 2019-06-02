<?php
/**
 * Display all Fonts of Family
 */ 
?>
<div class="container">
    <div class="row">
        <h2><?php echo $parent->title; ?></h2>
        <p><?php count( $fonts ); ?> variants</p>
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
                    <h3><?php echo $title; ?></h3>
                    <div class="font-child-list-entry_sample <?php echo $font['slug']; ?>">
                        ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz
                    </div>
                    <div class="font-child-list-entry_standard">
                        <?php //echo $font['standard']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
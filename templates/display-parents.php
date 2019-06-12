<?php
/**
 * Display parent fonts
 */ //var_dump( $fonts );
?>
    <div class="container">
        <div class="row">
            <div class="font-list">
                <?php foreach( $fonts as $font ): ?>
                    <style>
                        @font-face{ 
                            font-family: "<?php echo $font['title']; ?>";
                            src: url( "C:\wamp64\www\wp_hughes\wp-content\uploads\fontseller\recorded\<?php echo $font['repFont']; ?>" ) format('woff')
                        }
                        .font-list-entry_sample.<?php echo $font['slug']; ?> {
                            font-family: "<?php echo $font['title']; ?>";
                        }

                    </style>
                    <a href="/fonts?fontFamily=<?php echo $font['slug']; ?>">
                        <div class="font-list-entry">
                            <h3><?php echo $font['title']; ?></h3>
                            <div class="font-list-entry_sample <?php echo $font['slug']; ?>">
                                ABCDEFGHIJKLM
                            </div>
                            <div class="font-list-entry_standard">
                                <?php echo $font['standard']; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
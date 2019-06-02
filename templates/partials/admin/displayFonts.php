<?php
/**
 * Display the uploaded font files prior to writing them to the db
 */
?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="parentId" value="<?php echo $parentId; ?>" />
    <p>These are the font files prepared to be accepted.</p>
    <div class="font-table">
        <?php
            foreach( $files as $file ):?>
            <input type="hidden" name="fileName[]" value="<?php echo $file; ?>" />
            <div class="font-table_file"><?php echo $file; ?></div>
            <div class="font-table_charge"><input type="text" name="fileCharge[]" /></div>
        <?php endforeach; ?>
    </div>
    <input type="submit" name="subFonts" value="Submit" />
</form>

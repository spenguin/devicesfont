<?php
/**
 * Display the uploaded font files prior to writing them to the db
 */
?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="parentId" value="<?php echo $parentId; ?>" />
    <p>These are the font files prepared to be accepted.</p>
    <table class="form-table">
        <thead>
            <tr>
                <td>Name</td>
                <td>Variant</td>
                <td>Representative Font</td>
                <td>Accepted</td>
                <td>Formats</td>
            </tr>
        </thead>
        <?php 
            $flag   = TRUE;
            foreach( $files as $slug => $file ): ?>
            <tr class="form-field form-required">
		        <th scope="row"><label><?php echo $file['title']; ?></label></th>
                <td><input name="variant[]" type="text" value="<?php echo $file['variant']; ?>" autocorrect="off" maxlength="30"></td>
                <td><input type="radio" name="rep" value="<?php echo $slug; ?>" 
                <?php if( $flag )
                            {
                                echo 'checked="checked"';
                                $flag   = FALSE;
                            }
                    ?>                
                /></td>
                <td><input type="checkbox" name="accepted[]" value="<?php echo $slug; ?>" /></td>
                <td><?php echo join( ', ', $file['formats'] ); ?></td>
	        </tr>
        <?php endforeach; ?>
        <tr>
            <th scope="row"><label>Accept all</label></th>
            <td colspan="2">&nbsp;</td>
            <td><input type="checkbox" name="acceptAll" value="1" /></td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <input type="submit" class="button button-primary" name="recordFonts" value="Record Fonts" />
</form>
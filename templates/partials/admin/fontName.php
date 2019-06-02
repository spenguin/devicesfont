<?php
/**
 * Name the Font Family
 */
?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <label for="family">Suggested Font Family Name</label>
    <input type="text" name="family" value=<?php echo $guess; ?> />
    <fieldset>
        <h3>Font Classification</h3>
        <ul>
            <?php
                foreach( $classes as $k => $c ): ?>
                    <li><input type="checkbox" name="class" value="<?php echo $k; ?>" /><label><?php echo $c; ?></label></li>
            <?php endforeach; ?>
            <li><label>New Classification</label><input type="text" name="newClass" /></li>
        </ul>
    </fieldset>
    <fieldset>
        <h3>Font Standard</h3>
        <ul>
            <?php
                foreach( $standards as $k => $s ): ?>
                    <li><input type="radio" name="standard" value="<?php echo $k; ?>" /><label><?php echo $s; ?></label></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <?php if( !empty( $exists ) ): ?>
        <p>Suggested name is similar to names already existing. If this upload is part of an existing Family, either as an addition or a replacement, select the Font Family name from below:</p>
        <?php
            foreach( $exists as $k => $e ): ?>
                <input type="radio" name="familyId" value="<?php echo $k; ?>" /><?php echo $e['title']; ?>
                <?php 
                    $o  = [];
                    if( !empty( $e['classifications'] ) )
                    {
                        foreach( $e->classifications as $c )
                        {
                            $o[]    = $c->name;
                        }
                    }
                ?>
                <span><?php echo join( ' | ', $o ); ?></span>
                <br />
        <?php endforeach; endif; ?>
    <input type="submit" name="subFontName" value="Submit" />

</form>
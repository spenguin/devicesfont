<?php
/**
 * Name the Font Family
 */
?>
<h2>Name the Font Set</h2>
<?php echo(  $steps ); ?>

<?php echo( join( "<br />", $errors ) ); ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <fieldset>
        <label for="family">Suggested Font Set Name</label>
        <input type="text" name="family" value=<?php echo $guess; ?> />
        <h3>Font Set Type</h3>
        <p>Is this set a <input type="radio" name="set-type" value="1"> family or a <input type="radio" name="set-type" value="0" checked="checked">collection.</p>
        <p class="note">A family is a set of related fonts, like bold, italic, etc whereas a collection is a set of fonts that have been gathered together.</p>
    </fieldset>
    <fieldset>
        <h3>Font Classification</h3>
        <ul>
            <?php
                foreach( $classes as $k => $c ): ?>
                    <li><input type="checkbox" name="classification" value="<?php echo $k; ?>" /><label><?php echo $c; ?></label></li>
            <?php endforeach; ?>
            <li><label>New Classification</label><input type="text" name="newClassification" /></li>
        </ul>
    </fieldset>
    <fieldset>
        <h3>Font Standard</h3>
        <ul>
            <?php
                foreach( $standards as $k => $s ): ?>
                    <li><input type="radio" name="standard" value="<?php echo $k; ?>" <?php echo ( 0 == $k ) ? 'checked="checked"' : ''; ?>/><label><?php echo $s; ?></label></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <fieldset>
        <h3>Web font conversion</h3>
        <input type="checkbox" name="convert" value="1"> The fonts in this set will have WOFF formats automatically generated. Tick this box if you will be providing your own conversions.
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
    <input type="submit" class="button button-primary" name="subFontName" value="Create Font Set">

</form>
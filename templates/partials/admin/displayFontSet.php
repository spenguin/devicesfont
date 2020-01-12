<?php
/**
 * Display the Font Set
 */
?>
<?php 
    $url  = getThisUrl(); 
    $setOptions = setOptionsStr( $standards, $standard ); 
    $repFontArray   = array_flip( extractValues( $fonts, 'key', 'id' ) ); 
    $repOptions = setOptionsStr( $repFontArray, $repFont );
    $classIds   = array_keys( $fontClasses );
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Font Set <?php echo $parent->post_title; ?></h2>
    <form method="post" action="admin.php?page=fontseller-options&fontFamily=<?php echo $_GET['fontFamily']; ?>">
        <input type='hidden' name='option_page' value='general' /><input type="hidden" name="action" value="update" /><input type="hidden" id="_wpnonce" name="_wpnonce" value="08d7a0dc20" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php" />
        
        <fieldset>
            <legend>Basic Settings</legend>
            <table class="form-table" role="presentation">                
                <tr>
                    <th scope="row"><label for="blogname">Set Title</label></th>
                    <td><input name="setTitle" type="text" id="setTitle" value="<?php echo $parent->post_title; ?>" class="regular-text" /></td>
                </tr>

                <tr>
                    <th scope="row"><label for="setStandard">Standard</label></th>
                    <td>
                        <select name="setStandard">
                            <?php echo $setOptions; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="setRepFont">Representative Font</label></th>
                    <td>
                        <select name="setRepFont">
                            <?php echo $repOptions; ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <th scope="row"><label for="setRepFontSize">Representative Font Size</label></th>
                    <td>
                        <input name="setRepFontSize" type="text" id="setsetRepFontSizeTitle" value="<?php echo isset( $setRepFontSize ) ? $setRepFontSize : ''; ?>" class="regular-text" />
                        <br />Note: Use this field only to override the base setting
                    </td>
                </tr>   
                <tr>
                    <th scope="row"><label for="setFontAdj">Font Image Adjustment</label></th>
                    <td>
                        <input name="setFontAdj" type="text" id="setFontAdj" value="<?php echo isset( $fontAdj ) ? $fontAdj : ''; ?>" class="regular-text" />
                        <br />Note: Use this field if the font is cut off in the generated image
                    </td>
                </tr>   
                <tr>
                    <th scope="row"><label for="setFontStr">Font Image String</label></th>
                    <td>
                        <input name="setFontStr" type="text" id="setFontStr" value="<?php echo isset( $fontStr ) ? $fontStr : ''; ?>" class="regular-text" />
                        <br />Note: Use this field to override the default presentational string
                    </td>
                </tr>                              
    <!--            <tr>
                    <th scope="row"><label for="setFontFormatOffered">Font Formats Offered</label></th>
                    <td>
                        <input name="setFontFormatOffered" type="radio" value="2" />All Formats
                        <input name="setFontFormatOffered" type="radio" value="1" />Print only
                        <input name="setFontFormatOffered" type="radio" value="0" />Web only
                    </td>
                </tr>  -->
                <tr>
                    <th scope="row"><label for="setFontClass">Font Class</label></th>
                    <td>
                        <?php foreach( $classes as $k => $c ): ?>
                            <input name="setFontClass[]" type="checkbox" value="<?php echo $k; ?>" <?php echo in_array( $k, $classIds ) ? 'checked' : ''; ?>/><?php echo $c; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>                                             
            </table>
        </fieldset>         

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>
    </form>

<!--
    <form>
        <fieldset>
            <legend>The Basics</legend>
            <label for="setTitle">Set Title</label><input type="text" name="setTitle" value="<?php echo $parent->post_title; ?>" />
            <label for="setStandard">Standard</label>

        </fieldset>

        <input type="submit" name="submit" value="Submit" />
    </form>-->
    
</div>
<?php 
/**
 * Display Fontseller Settings
 */
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br>
    </div>
    <h2>Fontseller Settings</h2>
    <form method="post" action="admin.php?page=fs-op-settings">
<!--        <input type='hidden' name='option_page' value='general' /><input type="hidden" name="action" value="update" /><input type="hidden" id="_wpnonce" name="_wpnonce" value="08d7a0dc20" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php" />-->
        
        <fieldset>
            <legend>Basic Settings</legend>
            <table class="form-table" role="presentation">                
                <tr>
                    <th scope="row"><label for="setStandard">Current Standards</label></th>
                    <td>
                        <?php echo join( ', ', array_reverse( $standards ) ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="setStandard">Current Standard Pricing</label></th>
                    <td>
                        <?php echo 'UKP' . join( ', UKP', $prices ); ?>
                    </td>
                </tr>                
                <tr>
                    <th scope="row"><label for="setRepFontSize">Representative Font Size</label></th>
                    <td>
                        <input name="setRepFontSize" type="text" id="setsetRepFontSizeTitle" value="<?php echo isset( $repFontSize ) ? $repFontSize : ''; ?>" class="regular-text" />
                        <br />Note: this is the default font size
                    </td>
                </tr>   
                <tr>
                    <th scope="row"><label for="setFontFormatOffered">Font Formats Offered</label></th>
                    <td>
                        <input name="setFontFormatOffered" type="radio" value="2" <?php echo ( 2 == $fontFormatOffered ) ? 'checked': ''; ?> />All Formats
                        <input name="setFontFormatOffered" type="radio" value="1" <?php echo ( 1 == $fontFormatOffered ) ? 'checked': ''; ?> />Print only
                        <input name="setFontFormatOffered" type="radio" value="0" <?php echo ( 0 == $fontFormatOffered ) ? 'checked': ''; ?> />Web only
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="showClasses">Current Classes</label></th>
                    <td>
                        <?php echo join( ', ', $classes ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="showFontSets">Number of Font Sets to show per page</label></th>
                    <td>
                        <input name="showFontSets" type="text" id="setShowFontSets" value="<?php echo isset( $showFontSets ) ? $showFontSets : ''; ?>" class="regular-text" />
                        <br />Defaults to 20 sets per page
                    </td>
                </tr> 
                <!-- need pricing -->                                           
            </table>
        </fieldset>    

        <fieldset>
            <legend>Paypal Settings</legend>
            <table class="form-table" role="presentation">                
                <tr>
                    <th scope="row"><label for="setStatus">Paypal Status</label></th>
                    <td>
                        <input name="setStatus" type="radio" value="0" <?php echo ( 0 == $status ) ? 'checked': ''; ?> />Test
                        <input name="setStatus" type="radio" value="1" <?php echo ( 1 == $status ) ? 'checked': ''; ?> />Live
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="setTestAccount">Test Account</label></th>
                    <td>
                        <input name="setTestAccount" type="text" value="<?php echo isset( $testAccount ) ? $testAccount : ''; ?>" class="regular-text" />
                    </td>
                </tr>  
                <tr>
                    <th scope="row"><label for="setLiveAccount">Live Account</label></th>
                    <td>
                        <input name="setLiveAccount" type="text" value="<?php echo isset( $liveAccount ) ? $liveAccount : ''; ?>" class="regular-text" />
                    </td>
                </tr> 
            </table>                         
        </fieldset>     

        <fieldset>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>
        </fieldset>
    </form>    
</div> 
<?php

/**
 * Various functions
 */

function renderPagination( $pageNo, $ofPages )
{
    if( 1 < $pageNo ): ?>
        <div class="font-list_pagination_prev">
            <a href="/fonts?pageNo=<?php echo $pageNo-1; ?>&ofPages=<?php echo $ofPages; ?>">Previous</a>
        </div>
    <?php endif;
    for( $i = 1; $i <= $ofPages; $i++ ): ?>
        <div class="font-list_pagination_p <?php echo $i == $pageNo ? 'active' : ''; ?>">
            <a href="/fonts?pageNo=<?php echo $i; ?>&ofPages=<?php echo $ofPages; ?>"><?php echo $i; ?></a>
        </div>
    <?php endfor;
    if( $pageNo < $ofPages ): ?>
        <div class="font-list_pagination_next">
            <a href="/fonts?pageNo=<?php echo $pageNo+1; ?>&ofPages=<?php echo $ofPages;?>">Next</a>
        </div> 
<?php endif;    
}

function countryList()
{
    ob_start(); ?>
        <option value="">Select a country</option>
        <option value="AF">Afghanistan</option>
        <option value="AX">Åland Islands</option>
        <option value="AL">Albania</option>
        <option value="DZ">Algeria</option>
        <option value="AS">American Samoa</option>
        <option value="AD">Andorra</option>
        <option value="AO">Angola</option>
        <option value="AI">Anguilla</option>
        <option value="AQ">Antarctica</option>
        <option value="AG">Antigua and Barbuda</option>
        <option value="AR">Argentina</option>
        <option value="AM">Armenia</option>
        <option value="AW">Aruba</option>
        <option value="AU">Australia</option>
        <option value="AT">Austria</option>
        <option value="AZ">Azerbaijan</option>
        <option value="BS">Bahamas</option>
        <option value="BH">Bahrain</option>
        <option value="BD">Bangladesh</option>
        <option value="BB">Barbados</option>
        <option value="BY">Belarus</option>
        <option value="BE">Belgium</option>
        <option value="BZ">Belize</option>
        <option value="BJ">Benin</option>
        <option value="BM">Bermuda</option>
        <option value="BT">Bhutan</option>
        <option value="BO">Bolivia, Plurinational State of</option>
        <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
        <option value="BA">Bosnia and Herzegovina</option>
        <option value="BW">Botswana</option>
        <option value="BV">Bouvet Island</option>
        <option value="BR">Brazil</option>
        <option value="IO">British Indian Ocean Territory</option>
        <option value="BN">Brunei Darussalam</option>
        <option value="BG">Bulgaria</option>
        <option value="BF">Burkina Faso</option>
        <option value="BI">Burundi</option>
        <option value="KH">Cambodia</option>
        <option value="CM">Cameroon</option>
        <option value="CA">Canada</option>
        <option value="CV">Cape Verde</option>
        <option value="KY">Cayman Islands</option>
        <option value="CF">Central African Republic</option>
        <option value="TD">Chad</option>
        <option value="CL">Chile</option>
        <option value="CN">China</option>
        <option value="CX">Christmas Island</option>
        <option value="CC">Cocos (Keeling) Islands</option>
        <option value="CO">Colombia</option>
        <option value="KM">Comoros</option>
        <option value="CG">Congo</option>
        <option value="CD">Congo, the Democratic Republic of the</option>
        <option value="CK">Cook Islands</option>
        <option value="CR">Costa Rica</option>
        <option value="CI">Côte d'Ivoire</option>
        <option value="HR">Croatia</option>
        <option value="CU">Cuba</option>
        <option value="CW">Curaçao</option>
        <option value="CY">Cyprus</option>
        <option value="CZ">Czech Republic</option>
        <option value="DK">Denmark</option>
        <option value="DJ">Djibouti</option>
        <option value="DM">Dominica</option>
        <option value="DO">Dominican Republic</option>
        <option value="EC">Ecuador</option>
        <option value="EG">Egypt</option>
        <option value="SV">El Salvador</option>
        <option value="GQ">Equatorial Guinea</option>
        <option value="ER">Eritrea</option>
        <option value="EE">Estonia</option>
        <option value="ET">Ethiopia</option>
        <option value="FK">Falkland Islands (Malvinas)</option>
        <option value="FO">Faroe Islands</option>
        <option value="FJ">Fiji</option>
        <option value="FI">Finland</option>
        <option value="FR">France</option>
        <option value="GF">French Guiana</option>
        <option value="PF">French Polynesia</option>
        <option value="TF">French Southern Territories</option>
        <option value="GA">Gabon</option>
        <option value="GM">Gambia</option>
        <option value="GE">Georgia</option>
        <option value="DE">Germany</option>
        <option value="GH">Ghana</option>
        <option value="GI">Gibraltar</option>
        <option value="GR">Greece</option>
        <option value="GL">Greenland</option>
        <option value="GD">Grenada</option>
        <option value="GP">Guadeloupe</option>
        <option value="GU">Guam</option>
        <option value="GT">Guatemala</option>
        <option value="GG">Guernsey</option>
        <option value="GN">Guinea</option>
        <option value="GW">Guinea-Bissau</option>
        <option value="GY">Guyana</option>
        <option value="HT">Haiti</option>
        <option value="HM">Heard Island and McDonald Islands</option>
        <option value="VA">Holy See (Vatican City State)</option>
        <option value="HN">Honduras</option>
        <option value="HK">Hong Kong</option>
        <option value="HU">Hungary</option>
        <option value="IS">Iceland</option>
        <option value="IN">India</option>
        <option value="ID">Indonesia</option>
        <option value="IR">Iran, Islamic Republic of</option>
        <option value="IQ">Iraq</option>
        <option value="IE">Ireland</option>
        <option value="IM">Isle of Man</option>
        <option value="IL">Israel</option>
        <option value="IT">Italy</option>
        <option value="JM">Jamaica</option>
        <option value="JP">Japan</option>
        <option value="JE">Jersey</option>
        <option value="JO">Jordan</option>
        <option value="KZ">Kazakhstan</option>
        <option value="KE">Kenya</option>
        <option value="KI">Kiribati</option>
        <option value="KP">Korea, Democratic People's Republic of</option>
        <option value="KR">Korea, Republic of</option>
        <option value="KW">Kuwait</option>
        <option value="KG">Kyrgyzstan</option>
        <option value="LA">Lao People's Democratic Republic</option>
        <option value="LV">Latvia</option>
        <option value="LB">Lebanon</option>
        <option value="LS">Lesotho</option>
        <option value="LR">Liberia</option>
        <option value="LY">Libya</option>
        <option value="LI">Liechtenstein</option>
        <option value="LT">Lithuania</option>
        <option value="LU">Luxembourg</option>
        <option value="MO">Macao</option>
        <option value="MK">Macedonia, the former Yugoslav Republic of</option>
        <option value="MG">Madagascar</option>
        <option value="MW">Malawi</option>
        <option value="MY">Malaysia</option>
        <option value="MV">Maldives</option>
        <option value="ML">Mali</option>
        <option value="MT">Malta</option>
        <option value="MH">Marshall Islands</option>
        <option value="MQ">Martinique</option>
        <option value="MR">Mauritania</option>
        <option value="MU">Mauritius</option>
        <option value="YT">Mayotte</option>
        <option value="MX">Mexico</option>
        <option value="FM">Micronesia, Federated States of</option>
        <option value="MD">Moldova, Republic of</option>
        <option value="MC">Monaco</option>
        <option value="MN">Mongolia</option>
        <option value="ME">Montenegro</option>
        <option value="MS">Montserrat</option>
        <option value="MA">Morocco</option>
        <option value="MZ">Mozambique</option>
        <option value="MM">Myanmar</option>
        <option value="NA">Namibia</option>
        <option value="NR">Nauru</option>
        <option value="NP">Nepal</option>
        <option value="NL">Netherlands</option>
        <option value="NC">New Caledonia</option>
        <option value="NZ">New Zealand</option>
        <option value="NI">Nicaragua</option>
        <option value="NE">Niger</option>
        <option value="NG">Nigeria</option>
        <option value="NU">Niue</option>
        <option value="NF">Norfolk Island</option>
        <option value="MP">Northern Mariana Islands</option>
        <option value="NO">Norway</option>
        <option value="OM">Oman</option>
        <option value="PK">Pakistan</option>
        <option value="PW">Palau</option>
        <option value="PS">Palestinian Territory, Occupied</option>
        <option value="PA">Panama</option>
        <option value="PG">Papua New Guinea</option>
        <option value="PY">Paraguay</option>
        <option value="PE">Peru</option>
        <option value="PH">Philippines</option>
        <option value="PN">Pitcairn</option>
        <option value="PL">Poland</option>
        <option value="PT">Portugal</option>
        <option value="PR">Puerto Rico</option>
        <option value="QA">Qatar</option>
        <option value="RE">Réunion</option>
        <option value="RO">Romania</option>
        <option value="RU">Russian Federation</option>
        <option value="RW">Rwanda</option>
        <option value="BL">Saint Barthélemy</option>
        <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
        <option value="KN">Saint Kitts and Nevis</option>
        <option value="LC">Saint Lucia</option>
        <option value="MF">Saint Martin (French part)</option>
        <option value="PM">Saint Pierre and Miquelon</option>
        <option value="VC">Saint Vincent and the Grenadines</option>
        <option value="WS">Samoa</option>
        <option value="SM">San Marino</option>
        <option value="ST">Sao Tome and Principe</option>
        <option value="SA">Saudi Arabia</option>
        <option value="SN">Senegal</option>
        <option value="RS">Serbia</option>
        <option value="SC">Seychelles</option>
        <option value="SL">Sierra Leone</option>
        <option value="SG">Singapore</option>
        <option value="SX">Sint Maarten (Dutch part)</option>
        <option value="SK">Slovakia</option>
        <option value="SI">Slovenia</option>
        <option value="SB">Solomon Islands</option>
        <option value="SO">Somalia</option>
        <option value="ZA">South Africa</option>
        <option value="GS">South Georgia and the South Sandwich Islands</option>
        <option value="SS">South Sudan</option>
        <option value="ES">Spain</option>
        <option value="LK">Sri Lanka</option>
        <option value="SD">Sudan</option>
        <option value="SR">Suriname</option>
        <option value="SJ">Svalbard and Jan Mayen</option>
        <option value="SZ">Swaziland</option>
        <option value="SE">Sweden</option>
        <option value="CH">Switzerland</option>
        <option value="SY">Syrian Arab Republic</option>
        <option value="TW">Taiwan, Province of China</option>
        <option value="TJ">Tajikistan</option>
        <option value="TZ">Tanzania, United Republic of</option>
        <option value="TH">Thailand</option>
        <option value="TL">Timor-Leste</option>
        <option value="TG">Togo</option>
        <option value="TK">Tokelau</option>
        <option value="TO">Tonga</option>
        <option value="TT">Trinidad and Tobago</option>
        <option value="TN">Tunisia</option>
        <option value="TR">Turkey</option>
        <option value="TM">Turkmenistan</option>
        <option value="TC">Turks and Caicos Islands</option>
        <option value="TV">Tuvalu</option>
        <option value="UG">Uganda</option>
        <option value="UA">Ukraine</option>
        <option value="AE">United Arab Emirates</option>
        <option value="GB">United Kingdom</option>
        <option value="US">United States</option>
        <option value="UM">United States Minor Outlying Islands</option>
        <option value="UY">Uruguay</option>
        <option value="UZ">Uzbekistan</option>
        <option value="VU">Vanuatu</option>
        <option value="VE">Venezuela, Bolivarian Republic of</option>
        <option value="VN">Viet Nam</option>
        <option value="VG">Virgin Islands, British</option>
        <option value="VI">Virgin Islands, U.S.</option>
        <option value="WF">Wallis and Futuna</option>
        <option value="EH">Western Sahara</option>
        <option value="YE">Yemen</option>
        <option value="ZM">Zambia</option>
        <option value="ZW">Zimbabwe</option>
    <?php return ob_get_clean();
}

/**
 * Get the current URL and separate the $_GET parameters
 */
function getThisUrl()
{
    return "http://wphughes.loc/wp-admin/admin.php?page=fontseller-options";
}

/**
 * Render an image from a string and a font file
 */
function renderText( $font, $fontSize = 100, $text = "This is a test", $adj = 0 )
{   
    //$font = "C:/wamp64/www/wp_hughes/wp-content/uploads/fontseller/recorded/" . $fontName;

    // Calculate the required width to hold this text
    $enclosingBox = imagettfbbox($fontSize, 0, $font, $text);
    $width = abs($enclosingBox[4] - $enclosingBox[0]);
    $height = abs($enclosingBox[5] - $enclosingBox[1]);

    // Create the image and define colours
    $im     = imagecreatetruecolor($width, $height);
    // Transparent Background
    imagealphablending($im, false);
    $transparency = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $transparency);
    imagesavealpha($im, true);


    $white  = imagecolorallocate($im, 255, 255, 255);
    //$grey   = imagecolorallocate($im, 37, 37, 37);
    $black  = imagecolorallocate( $im, 0, 0, 0 );

    // Fill the background
    //imagefilledrectangle($im, 0, 0, $width, $height, $white);
    imagefilledrectangle($im, 0, 0, $width, $height, $white);


    // Render the text
    imagettftext($im, $fontSize, 0, -1 * $enclosingBox[0], $height-$adj, $black, $font, $text);

    // Output and cleanup
        ob_start();
            imagepng( $im, NULL );
        return ob_get_clean();
}

/**
 * Render an image from a string and a font file, with a transparent background
 */
function renderTransparentText( $font, $fontSize = 100, $text = "This is a test", $adj = 0  )
{   

    // Calculate the required width to hold this text
    $enclosingBox = imagettfbbox($fontSize, 0, $font, $text);
    $width = abs($enclosingBox[4] - $enclosingBox[0]);
    //$text = $width;
    $height = abs($enclosingBox[5] - $enclosingBox[1]);
    //$text = join( ',', $enclosingBox );

    // Create the image and define colours
    $im = imagecreatetruecolor($width, $height);
    
    // Transparent Background
    imagealphablending($im, false);
    $transparency = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $transparency);
    imagesavealpha($im, true);

    // Drawing over
    $white  = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);

    imagefilledrectangle($im, 0, 0, $width, $height, $transparency);

    // Render the text
    imagettftext($im, $fontSize, 0, -1 * $enclosingBox[0], $height-intval( $adj ), $black, $font, $text);    

    // Output and cleanup
        //$name   = 'image/test.png'; //return $name;
        ob_start();
            //header('Content-Type: image/png');
            imagepng( $im, NULL );
        return ob_get_clean();
        //return $name;
}

function setOptionsStr( $array, $selected=NULL )
{   
    $o  = [];
    foreach( $array as $key => $value )
    {
        $o[]    = '<option value="' . $key . '"' . ( ( $selected == $key || $selected == $value ) ? ' selected' : '' ) . '>' . $value . '</option>';
    }
    return join( '', $o );
}

/**
 * Get Terms based on taxonomy
 */
function getTerms( $slug )
{   
    $terms = get_terms( [
        'taxonomy' => $slug,
        'hide_empty' => false,
    ] );
    $o  = [];
    foreach( $terms as $t )
    {
        $o[$t->term_id]  = $t->name;
    }
    return $o;
}

function extractValues( $array, $keyElement, $element )
{
    $o  = [];
    foreach( $array as $key => $value )
    {
        $keyValue   = 'key' == $keyElement ? $key : $value[$keyElement];
        $o[$keyValue]   = $value[$element];
    }
    return $o;
}

/**
 * Accumulate the parameters for the presentation of the Font Set Representative Font
 */
function getRepFontData( $id )
{
    $o      = [
        'title' => getRepFontTitle( $id ),
        'size'  => getRepFontSize( $id ),
        'adj'   => getRepFontAdj( $id ),
        'str'   => getRepFontString( $id )
    ];

    return $o;
}

/**
 * Get the Rep Font Title string from the parent Id
 */
function getRepFontTitle( $id )
{
    $repFontStr    = get_post_meta( $id, 'repFont', TRUE ); //var_dump( $repFontStr );
    if( is_numeric( $repFontStr ) )
    {
        $repFont    = get_post( $repFontStr ); //var_dump( $repFont );
        return $repFont->post_title . ".otf"; //[FIX - need to determine what font format]
    }

    if( is_string( $repFontStr ) )
    {
        $the_slug = reset( explode( '.', $repFontStr ) );
        $args = array(
        'name'        => $the_slug,
        'post_type'   => 'font',
        'post_status' => 'publish',
        'numberposts' => 1
        );
        $my_posts = get_posts($args);
        return $my_posts[0]->post_title . '.otf'; //[FIX - need to determine what font format]
    }    
}

/**
 * Get the presentational Font Size for the Rep Font
 * Default to the size in Fontseller Settings
 */
function getRepFontSize( $id )
{
    $size   = get_post_meta( $id, 'repFontSize', TRUE );
    if( empty( $size ) ) 
    {
        $size   = get_option( 'repFontSize' );
    }

    return $size;
}

/**
 * Get the presentational Font adjustment value
 */
function getRepFontAdj( $id )
{
    $adj    = get_post_meta( $id, 'fontAdj', TRUE );
    if( empty( $adj ) ) return 0;

    return $adj;
}

/**
 * Get the string for the presentation of the Rep Font
 * Default to the Fontseller Settings
 */
function getRepFontString( $id )
{
    $str    = get_post_meta( $id, 'fontStr', TRUE );
    if( empty( $str ) )
    {
        $str    = get_option( 'repFontStr' );
    }
    
    return $str;
}

/**
 *  Get number of pages of fonts
 */
function getFontPages( $perPage )
{
    $args = [
        'post_type'       => 'font',
        'post_parent'     => 0,
        'posts_per_page'  => -1, // [FIX]
    ];

    $query  = new WP_Query( $args ); 
    $count  = 0;
    if( $query->have_posts() )
    {
        $count  = count( $query->posts );
    } 
    return ceil( $count / $perPage );

}
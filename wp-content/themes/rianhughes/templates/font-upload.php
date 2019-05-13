<?php
/**
* Template Name: Font Upload Page
*
* @package WordPress
* @subpackage Rian_Hughes
* @since Rian Hughes 1.0
*/

if( isset( $_POST['upload'] ) )
{
//    include_once( CORE_INC . 'font-functions.php' );
//    include_once( CORE_VENDOR . '')
    
    if( count( $_FILES['font-files'] ) > 0 )
    {
        $fontFiles  = $_FILES['font-files']; //var_dump( $_FILES['font-files'] );
        $allowableExtensions    = ['woff', 'woff2', 'otf', 'ttf', 'application/x-zip-compressed'];
        $uploadDir  = './wp-content/uploads/fontseller';
        $errorStr   = '';
        //var_dump( wp_get_upload_dir() );
        foreach( $fontFiles['name'] as $key => $fontFile )
        {    
            $errors = [];
            // Check for errors
            if( !empty( $fontFiles['errors'][$key] ) )
            {
                $errors[]   = "File " . $fontFile  . "has error";
            }

            // Check if empty
            if( 0 == $fontFiles['size'][$key] )
            {
    //            $errors[]   = "File " . $fontFile  . "is empty";
            }
            
            // Test extension
            if( !in_array( $fontFiles['type'][$key], $allowableExtensions ) )
            {
                $errors[]   = "File " . $fontFile  . "may be the wrong format";
            }
            if( empty( count( $errors) ) )
            {
                // Extract the information
                if( 'application/x-zip-compressed' == $fontFiles['type'][$key] )
                {
                    $zip    = new ZipArchive;
                    $res    = $zip->open( $fontFiles['tmp_name'][$key] ); // Move the file
                    if( $res === TRUE ) 
                    {
                        //echo 'ok';
                        $zip->extractTo( "$uploadDir/" );
                        $zip->close();
                    } else {
                        echo 'failed, code:' . $res;
                    }
                }
                else
                {
                    move_uploaded_file( $fontFiles['tmp_name'][$key], "$uploadDir/". $fontFiles['name'][$key] ); // Move the file
                }

                // Populate the database
                write_fonts($uploadDir);
            }
            else
            {
                $errorStr   .= join( '<br />', $errors );
            }
        }
    }
}


get_header(); 

?>

    <div class="container">
        <div class="row">
            <?php if( !empty( $errorStr ) ): ?>
                <p class="errors"><?php echo $errorStr; ?></p>
                <p></p>
            <?php endif; ?>
            <div class="font-upload-form">
                <form method="post" action="<?php echo site_url(); ?>/font-upload" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Select Files</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="font-files[]" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary" name="upload">Upload</button> 
                </form>

            </div>
        </div>
    </div>
	
<?php get_footer(); ?>

<?php
    function write_fonts( $uploadDir )
    {
        // Get files from directory
        $files  = scandir( $uploadDir );
        $files  = array_diff( $files, ['.', '..'] );
        $familyName = readFamilyName( $uploadDir, reset( $files ) );
        $familyId   = writeFamilyName( $uploadDir, $familyName );
        foreach( $files as $file )
        {
            $name   = explode( '-', $file );
            $name   = substr( $name[1], 0, -4 );
            $fontId = recordFontVariant( $name, $familyId );

        }
    }

    function readFamilyName( $uploadDir, $file )
    {
        // Get meta data from file
        $title  = explode( '-', $file );
        return reset( $title );
    }

    function writeFamilyName( $uploadDir, $familyName )
    {
        global $wpdb;

        //$sql    = "SELECT * FROM {$wpdb->prefix}fontseller_families WHERE name = '{$familyName}'"; 
        //$res    = $wpdb->query( $sql ); var_dump( $res );
        $res    = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}fontseller_families WHERE name = '{$familyName}'", ARRAY_A ); 
        if( empty( $res ) )
        {
            // write to table
            $insert = [
                'name'  => $familyName,
                'slug'  => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $familyName))),
                'path'  => $uploadDir . '/recorded',
            ];
            $wpdb->insert( 'df_fontseller_families', $insert );
            return $wpdb->insert_id;
        }
        else
        {   
            return $res['id'];
        }
    }

    function recordFontVariant( $name, $familyId )
    {
        global $wpdb;

        // Should check if the font variant already exists
        //$sql    = "SELECT * FROM {$wpdb->prefix}fontseller_variants WHERE name = '{$name}'";
        //$res    = $wpdb->query( $sql ); 
        $res    = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}fontseller_variants WHERE name = '{$name}'", ARRAY_A ); 

        if( empty( $res ) )
        {
            $insert = [ 
                'name'  => $name,
                'slug'  => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)))
            ];
            $wpdb->insert( 'df_fontseller_variants', $insert );
            $variantId  =  $wpdb->insert_id;            
        }
        else
        {
            $variantId  = $res['id'];
        }

        $insert = [
            'familyId'  => $familyId,
            'variantId' => $variantId
        ]; 
        $wpdb->insert( 'df_fontseller_fonts', $insert );

        return $wpdb->insert_id;
    }
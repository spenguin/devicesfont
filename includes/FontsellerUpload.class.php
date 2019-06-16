<?php

/**
 * Controller for Fontseller Upload page in Admin
 */

class FontsellerUpload {

    function __construct()
    {
        $this->_allowableExtensions = $this->setAllowableExtensions();
        $this->files    = [];
    }
    
    function display()
    { 
        //$upload = new FontsellerUpload;
        
        $errors = [];
        if( isset( $_POST['UploadFiles'] ) )        // There are files being uploaded
        { 
            $step   = 1;
            // Upload files to Fontseller upload directory
            $errors = $this->uploadFonts( $this->files ); 
            if( empty( $errors ) )
            {
                // Get files from directory
//                $files  = scandir( FS_UPLOAD . 'upload/' ); 
//                $files  = array_diff( $files, ['.', '..'] );
                $guess  = $this->guessFamilyName( reset( $this->files ) );
                $exists     = $this->checkFamilyName( $guess );
                $classes    = $this->getTerms( 'classification' ); 
                $standards  = $this->getTerms( 'standard' );             
                if( empty( $errors ) ) $step    = 2;
            }
        }

        if( isset( $_POST['subFontName'] ) )
        {  
            if( empty( $_POST['convert'] ) )
            {   
                $this->convertFonts();
            }
            if( empty( $_POST['family'] ) && empty( $_POST['familyId'] ) )
            {
                $errors[]   = "If a previous Font Family name has not been selected, a Font Family name is must be provided";
            }
            else
            {
                if( !empty( $_POST['familyId'] ) )
                {
                    $this->setId  = $_POST['familyId']; // Need to filter
                }
                else
                {
                    $this->setId  = wp_insert_post( ['post_title'=>$_POST['family'], 'post_type'=>'font', 'post_status' => 'publish'] ); // Need to filter
                    $this->setTerms( 'classification' );
                    $this->setTerms( 'standard' );
                    if( !empty( $_POST['newClassification'] ) )
                    {
                        $this->setNewClassification();
                    }
                    add_post_meta( $this->setId, 'setType', $_POST['set-type'] );
                }
            }
            $files  = $this->organiseFontFiles();

            if( empty( $errors ) ) $step    = 3;
        }

        if( isset( $_POST['recordFonts'] ) )
        {   
            if( empty( $_POST['accepted'] ) && empty( $_POST['acceptAll'] ) )
            {
                $errors[]   = "No fonts were accepted";
            }
            if( empty( $_POST['acceptAll'] ) && !in_array( $_POST['rep'], $_POST['accepted'] ) )
            {
                $errors[]   = "Representative font has not been accepted";
            }
            if( empty( $errors ) )
            {   
                $files  = $this->organiseFontFiles();
                $parentId   = $_POST['parentId'];
                if( empty( $_POST['acceptAll'] ) )
                {   
                    $acceptedFiles  = array_intersect_key( $files, array_flip( $_POST['accepted'] ) );
                }
                else
                {   
                    $acceptedFiles  = $files;
                } 
                foreach( $acceptedFiles as $slug => $file )
                {
                    $args   = [
                        'post_title'    => $file['title'],
                        'post_type'     => 'font',
                        'post_parent'   => $parentId,
                        'tags_input'    => ['slug' => $slug],
                        'post_status'   => 'publish',
                        'tax_input'     => [
                                'variant'   => [$file['variant']]
                        ]
                    ];
                    $fontId = wp_insert_post( $args );
                    add_post_meta( $fontId, 'formats', join( ',', $file['formats'] ) );
                    foreach( $file['formats'] as $format )
                    {   
                        rename( FS_UPLOAD . 'upload/' . $file['title'] . '.' . $format, FS_UPLOAD . 'recorded/' . $file['title'] . '.' . $format ); // Move the file
                    }
                } //var_dump( $parentId ); var_dump( $_POST['rep'] );
                add_post_meta( $parentId, 'repFont', $_POST['rep'] );
                
                // Delete unaccepted fonts
                $file   = $this->cleanUploadDirectory( TRUE );



                $message    = "Font Set has been recorded";
                $step    = 1;
            }
        }


        switch( $step )
        {
            case 2:
                $steps  = $this->renderSteps(2);
                include_once( FS_TEMPLATES . '/partials/admin/setFontSetName.php' );
                break;
            case 3: 
                $steps  = $this->renderSteps(3);
                include_once( FS_TEMPLATES . '/partials/admin/recordSetFonts.php' );
                break;
            case 4:
                $steps  = $this->renderSteps(4);
                break;
            default:
                $steps  = $this->renderSteps();
                include_once( FS_TEMPLATES . '/partials/admin/uploadFontFiles.php' );
        }

         
    }

    /**
     * Upload Font files
     */
    function uploadFonts( &$files )
    {
        if( empty( $_FILES['font-files'] ) )
        {
            return ['No file selected'];
        }
        $fontFiles  = $_FILES['font-files']; //var_dump( $_FILES['font-files'] );
        
        
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
            if( !in_array( $fontFiles['type'][$key], $this->_allowableExtensions ) )
            {
                $errors[]   = "File " . $fontFile  . "may be the wrong format";
            }
            if( empty( count( $errors ) ) )
            {   
                // Extract the information
                if( 'application/x-zip-compressed' == $fontFiles['type'][$key] )
                {   
                    $zip    = new \ZipArchive; 
                    $res    = $zip->open( $fontFiles['tmp_name'][$key] ); // Move the file
                    if( $res === TRUE ) 
                    {   
                        //echo 'ok';
                        $zip->extractTo( FS_UPLOAD . 'upload/' );
                        $zip->close();
                    } else {
                        echo 'failed, code:' . $res;
                    }
                }
                else
                {
                    move_uploaded_file( $fontFiles['tmp_name'][$key], FS_UPLOAD . 'upload/' . $fontFiles['name'][$key] ); // Move the file
                }
                // Delete non-files and write a list of the files that are there
                $files = $this->cleanUploadDirectory();
            }
        }
        return $errors;
    }  

    /**
     * Guess the font title. (Needs to be improved.)
     * @param (string) font path
     * @param (string) font file name
     * @return (string) guessed name
     */
    function guessFamilyName( $file )
    {
        // Get meta data from file
        if( FALSE !== strpos( $file, '-' ) )    // If there's a - in the file name, assume the first part is the font family name
        {
            $title  = explode( '-', $file );
            return reset( $title );
        }
        else
        {
            $title  = explode( '.', $file );    // Otherwise, break the filename at the . and take the first part
            return reset( $title );
        }
    }

    /**
     * Find Font Family names that are similar to the guessed Font Family name
     * @param (string) guessedName
     * @return (array) possible matches with associated Classifications
     *
     */
    function checkFamilyName( $guess )
    {
        global $wpdb;

        $sql    = "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'font' AND post_title LIKE '%" . $guess . "%' AND post_parent = 0"; //var_dump( $sql );
        $res    = $wpdb->get_results( $sql );
        $o      = []; 
        if( !empty( $res ) )
        {
            $parent = get_term_by( 'slug', 'classification', 'variant' );
            
            foreach( $res as $r )
            {
                // Get corresponding classifications
                $o[$r->ID]  = [
                    'title'             => $r->post_title,
                    'classifications'   => $this->getPostClassifications( $r->ID, $parent->term_id )
                ];
            }
            
        } 
        return $o;
    }   

    /**
     * Get Classifications post where Parent Id is known
     * 
     */
    function getPostClassifications( $postId, $parentId )
    {
        $terms  = get_the_terms( $postId, 'classification' );
        $o      = [];
        if( !empty( $terms ) )
        {
            foreach( $terms as $t )
            {
                if( $parentId <> $t->parent ) continue;
                $o[]    = $t;
            }
        }
        return $o;
    }    
    
    /**
     * Secondary methods
     */

    /**
     * Render the steps
     */
    function renderSteps()
    {
        $steps  = [
            'Upload the font file',
            'Name the Font Family',
            'Set the variants (where applicable)',
            'Complete the upload'
        ];
        
        ob_start(); ?>
            <div class="steps">
                <?php foreach( $steps as $k => $step ): ?>
                    <div class="<?php echo ( 0 == $k ) ? 'active' : ''; ?>"><?php echo $step; ?></div>
                <?php endforeach; ?>
            </div>
        <?php
        return ob_get_clean();
    }

    function setAllowableExtensions()
    {
        return ['woff', 'woff2', 'application/octet-stream', 'ttf', 'application/x-zip-compressed'];
    }   

    /**
     * Set the Classification taxonomy terms for the Set
     */
    function setTerms( $taxonomy )
    {   
        if( !empty( $_POST[$taxonomy] ) )
        {   
            wp_set_post_terms( $this->setId, $_POST[$taxonomy], $taxonomy );
        }
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

    /**
     * Appends the specified taxonomy term to the incoming post object. If 
    * the term doesn't already exist in the database, it will be created.
    *
    * @param    WP_Post    $post        The post to which we're adding the taxonomy term.
    * @param    string     $value       The name of the taxonomy term
    * @param    string     $taxonomy    The name of the taxonomy.
    * @access   private
    * @since    1.0.0
    */
    private function setNewClassification() 
    {
        $term = term_exists( $_POST['newClassification'], 'classification' );
        // If the taxonomy doesn't exist, then we create it
        if ( 0 === $term || null === $term ) {
            $term = wp_insert_term(
                $_POST['newClassification'],
                'classification',
                array(
                    'slug' => strtolower( str_ireplace( ' ', '-', $value ) )
                )
            );
        }
        // Then we can set the taxonomy
        wp_set_post_terms( $this->setId, $_POST['newClassification'], 'classification', true );
    }

    /**
     * Convert fonts in the upload file to WOFF
     */
    function convertFonts()
    {   
        $files  = $this->readDirectory(); 
        $cd = 'cd ' . FS_UPLOAD . 'upload/ && '; 
        foreach( $files as $f )
        {
            $name   = reset( explode( '.', $f ) );
            $curl   = $cd . 'curl -F "file=@' . $f . '" -F "format=woff" http://webfontomatic.com/convert >' . $name . '.woff'; 
            $res    = shell_exec( $curl ); 
        }
    }

    /**
     * Clean the upload directory
     */
    function cleanUploadDirectory( $all = FALSE )
    {
        $files  = scandir( FS_UPLOAD . 'upload/' ); 
        $files  = array_diff( $files, ['.', '..'] );
        $o      = [];
        foreach( $files as $f )
        {  
            if( is_dir( FS_UPLOAD . 'upload/' . $f ) )
            {
                $this->deleteDirectory( FS_UPLOAD . 'upload/' . $f );
            }
            else
            {
                if( $all )
                {
                    unlink(  FS_UPLOAD . 'upload/' . $f );
                }
                else
                {
                    $o[]    = $f;
                }
                
            }
        }
        return $o;        
    }
    /**
     * Delete directory
     */
    function deleteDirectory( $dir ) 
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) 
        {
            if ($item == '.' || $item == '..') continue;
            if ( !$this->deleteDirectory( $dir . DIRECTORY_SEPARATOR . $item ) ) return false;
        }
        return rmdir($dir);
    }    

    /**
     * Read the files in the upload directory
     */
    function readDirectory()
    {
        $files  = scandir( FS_UPLOAD . 'upload/' ); 
        $files  = array_diff( $files, ['.', '..'] ); 
        return $files;       
    }

    /**
     * Organise the font files for presentation
     */
    function organiseFontFiles()
    {
        $files  = $this->readDirectory();
        $o      = [];
        foreach( $files as $file )
        {
            $title  = explode( '.', $file );
            $slug   = strtolower( str_ireplace( ' ', '-', $title[0] ) );
            $variant= '';
            if( FALSE !== strpos( $title[0], '-' ) )
            {
                $variant    = explode( '-', $title[0] );
                $variant    = $variant[1];
            }
            if( !array_key_exists( $slug, $o ) )
            {
                $o[$slug]   = [
                    'title' => $title[0],
                    'formats'   => [],
                    'variant'   => $variant
                ];
            } 
            $o[$slug]['formats'][]    = strtolower( $title[1] );
            
        }
        return $o;
    }
}


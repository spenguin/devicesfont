<?php
/**
 * Initialise settings for the plugin
 */

class FontsellerPlugin {

    function test()
    {
        // Check if minimum requirements are installed and available
        return TRUE;
    }

    /**
     * Generate folders and establish settings
     */
    function initialise()
    {
        // Create upload folders
        $fp = new FontsellerPlugin();
        $fp->createFolders();
        
    }

    /**
     * Create Upload folders
     */
    function createFolders()
    {   
        if ( !file_exists( FS_UPLOAD ) )
        {
            mkdir( FS_UPLOAD, 0777 );
        }
        if ( !file_exists( FS_UPLOAD . 'upload/' ) )
        {
            mkdir( FS_UPLOAD . '/upload/' , 0777 );
        }   
        if ( !file_exists( FS_UPLOAD . 'recorded/' ) )
        {
            mkdir( FS_UPLOAD . 'recorded/' , 0777 );
        }  
    }
}
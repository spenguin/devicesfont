<?php
/**
 * Create Actions
 */
/**
 * Font Sets by Page
 */
add_action( 'fontseller_display_fontsets', 'fontseller_display_before_fontsets', 10 );
add_action( 'fontseller_display_fontsets', 'fontseller_display_fontsets', 20 );
add_action( 'fontseller_display_fontsets', 'fontseller_display_after_fontsets', 30 );


function fontseller_display_before_fontsets()
{
    if( !isset( $_GET['pageNo'] ) || 1 == $_GET['pageNo'] )
    {
        //add_action( 'fontlist_header', 'fontlist_bestsellers', 5, 2 );
        //add_action( 'fontlist_header', 'fontlist_newfonts', 10, 2 );
    }
    else
    {
        remove_action( 'fontlist_header', 'fontlist_bestsellers', 5 );
        remove_action( 'fontlist_header', 'fontlist_newfonts', 10 );    
    }
    //do_action( 'fontlist_header' );
}

function fontseller_display_fontsets()
{
    $perPage    = get_option( 'showFontSets', 20 );
    if( empty( $perPage ) ) $perPage    = 20;
    $pageNo = ( isset( $_GET['pageNo'] ) ) ? $_GET['pageNo'] : 1;
    
    $args = [
        'post_type'         => 'font',
        'post_parent'       => 0, //$parentId,
        'posts_per_page'    => $perPage, // [FIX]
        'offset'            => $perPage * ($pageNo - 1 ),
        'orderby'           => 'title',
        'order'             => 'ASC'
    ]; 
    
    $fonts  = get_font_display_array( $args );
    
    add_action( 'display_fontsets', 'display_fontlist', 10, 3 );
    do_action( 'display_fontsets', '', $fonts, 'Fonts' );
}

function fontseller_display_after_fontsets()
{

    $pageNo = ( isset( $_GET['pageNo'] ) ) ? $_GET['pageNo'] : 1;
    if( isset( $_GET['ofPages'] ) )
    {
        $ofPages    = $_GET['ofPages'];
    }
    else
    {
        $perPage    = get_option( 'showFontSets', 20 );
        $ofPages  = getFontPages( $perPage );
    }
    
    add_action( 'display_after_fontsets', 'display_fontlist_footer', 10, 2 );
    do_action( 'display_after_fontsets', $pageNo, $ofPages );
}

/**
 * Get Bestsellers based on sales
 * 
 */
function fontlist_bestsellers( $class="", $fontSetArray = [] )
{ 
    // Retrieve Bestseller Fontsets 

    // Arrange data
    add_action( 'display_bestsellers', 'display_fontlist', 10, 3 );
    do_action( 'display_bestsellers', 'bestsellers', [], 'Bestsellers' );
}
/**
 * Get newest Fonts
 */
function fontlist_newfonts()
{
    // Retrieve Newest Fontsets 
    $args   = [
        'post_type'         => 'font',
        'posts_per_page'    => 4,
        'post_parent'       => 0
    ];

    $fonts  = get_font_display_array( $args );

    // Arrange data
    add_action( 'display_newfonts', 'display_fontlist', 10, 3 );
    do_action( 'display_newfonts', 'newfonts', $fonts, 'Newest Fonts' );    
}

function display_fontlist( $class="", $fontSetArray = [], $title = NULL )
{
    if( !empty( $title ) ) echo '<h2>' . $title . '</h2>'; ?>
    <div class="font-list <?php echo $class; ?>">
        
        <?php foreach( $fontSetArray as $fId => $fSet ):
            $path   = FS_UPLOAD . 'recorded/' . $fSet['repFont']['title'];
            $size   = $fSet['repFont']['size'];
            $str    = $fSet['repFont']['str'];
            $adj    = $fSet['repFont']['adj'];            
            ?>
            <a href="/fonts?fontFamily=<?php echo $fSet['slug']; ?>">
                        
                <div class="font-list-entry">
                    <h3><?php echo $fSet['title']; ?></h3>
                    <div class="font-list-entry_sample">
                        <?php if( file_exists( $path ) ): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode( renderTransparentText( $path, $size, $str, $adj ) ); ?>" />  
                        <?php endif; ?>
                    </div>
                    <div class="font-list-entry_standard">
                        <?php //echo $fSet['standard']; [FIX: Should be do_action] ?>
                    </div>
                </div>
            </a>
        <?php endforeach;?>
    </div>

    <?php
}

function display_fontlist_footer( $pageNo, $ofPages )
{   ?>
    <div class="font-list_pagination">
        <?php renderPagination( $pageNo, $ofPages );  ?>
    </div> 
    <?php
}

/**
 * Retrieve fonts for provided parameters
 * Return array of necessary values
 */
function get_font_display_array( $args )
{
    $o  = [];
    $query  = new WP_Query( $args );

    if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();
        $id         = get_the_ID(); 

        $o[$id] = [
            'title' => get_the_title(),
            'slug'  => get_post_field( 'post_name', $id ),
            'repFont'   => getRepFontData( $id ),
            'standard'  => reset( getSetTerms( $id, 'standard' ) )
        ];
    endwhile; endif; wp_reset_postdata();

    return $o;
}







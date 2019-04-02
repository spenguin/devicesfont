<?php
/**
 *
 * @package WordPress
 * @subpackage Rian Hughes
 * @since Rian Hughes 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo site_url(); ?>/wp-content/themes/rianhughes/css/style.css" >
    <!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="wrapper">
        <nav class="nav">
            <div class="container-fluid">
                <div class="container">
                    <ul class="main">
                        <li><a class="nav-link" href="/fonts">Buy Fonts</a></li>
                        <li><a class="nav-link" href="/work">View Portfolio</a></li>
                    </ul>
                    <ul class="secondary">
                        <li><a class="nav-link active" href="/">Home</a></li>
                        <li><a class="nav-link" href="/fonts">Fonts</a></li>
                        <li><a class="nav-link" href="#">Link</a></li>
                        <li><a class="nav-link disabled" href="#">Disabled</a></li>
                    </ul>                
                </div>
            </div>
        </nav>
        <header>
            <div class="container">
                <ul class="subtitle">
                    <li><span>Device</span>Type by Rian Hughes</li>
        <!--            <li><span>Device</span>Design by Rian Hughes</li>
                    <li><span>Device</span>Imagery by Rian Hughes</li>-->
                </ul>
            </div>
        </header>
        <main>
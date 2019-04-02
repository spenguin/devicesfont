<?php
/**
 *
 * @package WordPress
 * @subpackage Rian Hughes
 * @since Rian Hughes 1.0
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <h3>Fonts</h3>
            <a href="<?php echo site_url(); ?>/fonts"><img src="<?php echo site_url(); ?>/wp-content/uploads/2019/03/recent1.jpg" alt="<?php echo $m[2]; ?>"/></a>
        </div>
        <div class="col">
            <h3>Illustration</h3>
            <a href="<?php echo site_url(); ?>/illustration"><img src="<?php echo site_url(); ?>/wp-content/uploads/2019/03/recent2.jpg" alt="<?php echo $m[2]; ?>"/></a>
        </div>
        <div class="col">
            <h3>Design</h3>
            <a href="<?php echo site_url(); ?>/design"><img src="<?php echo site_url(); ?>/wp-content/uploads/2019/03/recent3.jpg" alt="<?php echo $m[2]; ?>"/></a>
        </div>
        <div class="col">
            <h3>Logos</h3>
            <a href="<?php echo site_url(); ?>/logos"><img src="<?php echo site_url(); ?>/wp-content/uploads/2019/03/recent4.jpg" alt="<?php echo $m[2]; ?>"/></a>
        </div>                        
    </div>
</div>
<?php get_footer(); ?>
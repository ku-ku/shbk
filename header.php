<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Shabashka
 * @since Shabashka 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!--meta name="viewport" content="width=1280, initial-scale=1"-->
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
        <script>
            var _l = "<?php echo get_stylesheet_directory_uri();?>";
            dojoConfig = {
                    parseOnLoad: false,
                    async: true,
                    tlmSiblingOfDojo: false,
                    homeUrl: _l,
                    baseUrl: _l+"/js/",
                    dojoBlankHtmlUrl: _l + '/blank.html',
                    wpAjaxUrl:"<?php echo admin_url('admin-ajax.php');?>",
                    wpNonce:"<?php echo wp_create_nonce('ajax-nonce');?>",
                    packages:[
                                {name: 'shbk', location: _l + '/js', main: 'app' }
                             ]
            };
        </script>
        
	<?php wp_head();?>
        
</head>

<body <?php body_class("soria".(is_front_page()?" front" : ""));?> data-spy="scroll">
<div id="page" class="site">

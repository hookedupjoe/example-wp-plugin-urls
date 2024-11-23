<?php
/**
 * Create page outline and include template for this page
 * 
 * The header and footer can be updated based on page name
 *  as well as sidebar added where needed, etc.
 * 
 * Other code needed for all pages in this plugin can be
 *  updated here as well. 
 */

$pagename = $post->post_name;

if ( ! empty( $_POST ) ) {
	echo("Submit not allowed");
	die();
}
get_header();	
?>

<div id="content" class="site-content" role="main">
<?php
	if( $pagename == 'dashboard'){
		include 'part-dashboard.php'; 
	} else if( $pagename == 'welcome'){
		include 'part-welcome.php'; 
	}
?>
</div>

<?php get_footer();	?>
<?php
header("Content-type: text/css", true);

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
$url = $parse_uri[0];

require_once($url . 'wp-load.php' );

if(!function_exists("get_field")) exit;

if(1==0){
?>
<style>
<?php
}

$base = "background: -moz-linear-gradient(left, rgba(79,206,41,1) 0%, rgba(79,206,41,1) 58%, rgba(79,206,41,0.3) 100%);
background: -webkit-linear-gradient(left, rgba(79,206,41,1) 0%,rgba(79,206,41,1) 58%,rgba(79,206,41,0.3) 100%);
background: linear-gradient(to right, rgba(79,206,41,1) 0%,rgba(79,206,41,1) 58%,rgba(79,206,41,0.3) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4fce29', endColorstr='#4d4fce29',GradientType=1 );";

$terms = get_terms( 'programme', array(
    'hide_empty' => false,
) );

foreach($terms as $term){
	$colour = get_field("base_colour",'term_'.$term->term_id);
	$bg = str_replace("#4fce29",$colour,$base);
	list($r, $g, $b) = sscanf($colour, "#%02x%02x%02x");
	$bg = str_replace("79,206,41","$r,$g,$b",$base);	
	?>
	#whatson #listings .listing.programme-<?php echo $term->slug; ?>:before {<?php echo $bg; ?>}
	<?php
}
?>
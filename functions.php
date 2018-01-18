<?php
add_action( 'after_setup_theme', 'hodephinitely_setup' );
add_action( 'wp_enqueue_scripts', 'hodephinitely_load_scripts' );

function hodephinitely_setup(){
	add_theme_support( 'post-thumbnails' );
	$args = array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'widgets'
	);
	add_theme_support( 'title-tag' );
	
	add_image_size( 'full-width', 1920 );
}
add_filter('show_admin_bar', '__return_false');

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function add_query_vars($vars) {
	$vars[] = "term";
	return $vars;
}
 
add_filter('query_vars', 'add_query_vars');

function hodephinitely_load_scripts(){
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script('iframe-resizer', get_template_directory_uri() . '/iframe-resizer.min.js', array(), null);
	wp_enqueue_script('lightbox','https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.5/js/lightgallery.min.js',array(),null);
	wp_enqueue_style('typekit','https://use.typekit.net/dfb1hpf.css',array(),null);
	wp_enqueue_style('spektrix-common',get_template_directory_uri() . '/common.css',array(),null);
	wp_enqueue_style('main',get_stylesheet_uri(),array(),null);
	wp_enqueue_style('responsive',get_template_directory_uri() . '/responsive.css',array(),null);
	wp_enqueue_style('programme',get_template_directory_uri()."/programme.css.php",array(),null);
	wp_enqueue_style('lightbox','https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.5/css/lightgallery.min.css',array(),null);
}

function register_menu() {
	register_nav_menu('main-menu',__( 'Main Menu' ));
}
add_action( 'init', 'register_menu' );

add_action( 'template_redirect', 'holding_page' );

function holding_page() {
	
	return;

	if ( !is_front_page() && !is_user_logged_in() ) {
		if(!is_page("resources") && !is_page("new-writers-award") && !is_page("jobs") && !is_page("festival-assistants")){
			wp_redirect( home_url() ); 
			exit;
		}
    } elseif(!is_user_logged_in() ) {
		include("holding.php");
		exit;
	}
}

function vault_social($large=false){
	?>
	<div class="social<?php echo $large ? " large-social" : ""; ?>">
		<a target="_blank" href="https://facebook.com/vaultfestival"><?php getSVG('facebook'); ?></a>
		<a target="_blank" href="https://twitter.com/vaultfestival"><?php getSVG('twitter'); ?></a>
		<a target="_blank" href="https://youtube.com/vaultfestival"><?php getSVG('youtube'); ?></a>
		<a target="_blank" href="https://instagram.com/vaultfestival"><?php getSVG('instagram'); ?></a>
    </div>
	<?php
}

function hodephinitely_allowed_status(){
	return array("publish");	
}

function getSVG($name){
	$path = get_template_directory() . "/images/" . $name . ".svg";
	if(is_file($path)){
		echo str_replace(array("\r","\n"),"",file_get_contents($path));
	} else {
		return;	
	}
}

function spektrix_render_listing($id){
	$image = get_the_post_thumbnail_url($id,"thumbnail");
	$programme = get_the_terms( $id, 'programme' );
	$price = (float)get_post_meta($id,"price",true);;
	$price += 1.5;
	
	$dates = spektrix_format_dates($id);	
	?>
    <div class="listing half marged <?php echo "programme-".$programme[0]->slug; ?>">
        <div class="image" style="background-image:url(<?php echo $image; ?>);">
        	<a href="<?php echo get_post_permalink($id); ?>"></a>
        </div>
        <div class="details">
            <div class="genre upper"><?php echo $programme[0]->name; ?></div>
            <h6><?php echo get_the_title($id); ?></h6>
            <div class="listing-date flex">
                <span class="price">£<?php echo !empty($price) ? number_format($price,2) : "TBC"; ?></span>
                <span class="date"><?php echo $dates; ?></span>
            </div>
            <p><?php echo get_the_excerpt($id); ?></p>
            <a class="ucta" href="<?php echo get_post_permalink($id); ?>">More Details</a>
        </div>
    </div>
    <?php	
}

function spektrix_format_dates($id){
	$s = strtotime(get_post_meta($id,"starts",true));
	$e = strtotime(get_post_meta($id,"ends",true));
	
	$sm = date("M",$s);
	$sd = date("d",$s);
	$em = date("M",$e);
	$ed = date("d",$e);
	
	$dates = ($sd!=$ed ? $sd : "") . ($sm!=$em ? " " . $sm : "") . ($sd!=$ed ? " &mdash;" : "") . " $ed $em";
	
	return $dates;	
}

function spektrix_render_hero($image){
	?>
    <div class="hero<?php if(empty($image)) { echo " no-image"; } else { ?>" style="background-image:url(<?php echo $image; ?>);<?php } ?>"></div>
    <?php	
}

function spektrix_render_highlight($id){
	$image = get_the_post_thumbnail_url($id,"medium");
	$programme = get_the_terms( $id, 'programme' );
	?>
    <div class="third">
        <div class="image<?php if(empty($image)) { echo " no-image"; } else { ?>" style="background-image:url(<?php echo $image; ?>);<?php } ?>">
        	<a href="<?php echo get_post_permalink($id); ?>"></a>
        </div>
        <span class="cta black fake"><?php echo $programme[0]->name; ?></span>
        <h5><?php echo get_the_title($id); ?></h5>
        <?php echo apply_filters('the_excerpt', get_post_field('post_excerpt', $id)); ?>
        <a class="ucta" href="<?php echo get_post_permalink($id); ?>">More Details</a>
    </div>
    <?php	
}

function hodephinitely_convert_time($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
add_filter( 'manage_edit-spektrix_event_columns', 'spektrix_event_columns' );

function spektrix_event_columns( $columns ) {
	$columns['programme'] = "Programme";
	$columns['genre'] = "Genre";
	$columns['week'] = "Week";
	//$columns['interest'] = "Interest Tags";
	$columns['starts'] = "Starts";
	$columns['ends'] = "Ends";
	return $columns;
}
add_action( 'manage_spektrix_event_posts_custom_column', 'manage_spektrix_event_columns', 10, 2 );

function manage_spektrix_event_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'programme' :
		case 'genre' :
		case 'week' :
		case 'interest' :
			$value = get_the_terms( $post_id, $column );
			if ( empty( $value ) ) echo "";
			else {
				$value = array_column(array_map(function($o){return (array)$o;},$value),'name');	
				echo implode(", ",$value);
			}
			break;
			
		case 'starts':
		case 'ends':
		
			$value = get_post_meta( $post_id, $column, true );
			echo !empty($value) ? date("d/m/Y",strtotime($value)) : "";
		
			break;
		default :
			break;
	}
}
add_filter( 'manage_edit-spektrix_event_sortable_columns', 'spektrix_event_sortable_columns' );

function spektrix_event_sortable_columns( $columns ) {
	//$columns['programme'] = 'programme';
	//$columns['week'] = 'programme';
	$columns['starts'] = 'starts';
	$columns['ends'] = 'ends';
	return $columns;
}
add_action( 'pre_get_posts', 'spektrix_event_orderby' );

function spektrix_event_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'starts' == $orderby ) {
        $query->set('meta_key','starts');
        $query->set('orderby','meta_value');
    }
	
	if( 'ends' == $orderby ) {
        $query->set('meta_key','ends');
        $query->set('orderby','meta_value');
    }
}
add_action('admin_head', 'hidey_admin_head');

function hidey_admin_head() {
	global $post_type;
    if ( 'spektrix_event' == $post_type ) {
		echo '<style type="text/css">';
		echo '.column-programme { width:6.5em; }';
		echo '.column-week { width:12em;}';
		echo '.column-starts, .column-ends { width:6.5em; white-space:nowrap; }';
		echo '.column-genre { width:9em; }';
		echo '</style>';
	}
}

function search_title_filter( $where, &$wp_query )
{
    global $wpdb;
    if ( $search_term = $wp_query->get( 'term' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}

add_action( 'wp_ajax_nopriv_load_listings', 'spektrix_load_listings' );
add_action( 'wp_ajax_load_listings', 'spektrix_load_listings' );

function spektrix_load_listings($params = array()){
	$params = empty($params) ? $_GET['params'] : $params;
	
	//ob_start();
	
	$date = $params['date'];
	unset($params['date']);
	
	$term = $params['term'];
	unset($params['term']);
	
	$taxonomy = array();
	foreach($params as $tax => $slug){
		if(!empty($slug)){
			$slug = explode(",",$slug);
			$taxonomy[] = array(
				'taxonomy' => $tax,
				'field'    => 'slug',
				'terms'    => $slug,
			);
		}
	}
	
	$args = array(
	  'post_type' => 'spektrix_event',
	  'post_status' => hodephinitely_allowed_status(),
	  'posts_per_page' => -1,
	  'order' => 'ASC',
	  'meta_key' => 'starts',
	  'orderby' => 'meta_value',
	);
	
	if(!empty($taxonomy)){
		$args['tax_query'] = $taxonomy;	
	}
	
	if(!empty($date)){
		$args['meta_query'] = array(
			array(
				'key' => 'times',
				'value' => (string)$date,
				'compare' => 'LIKE',
				//'type' => 'DATE',
			),
			/*array(
				'key' => 'starts',
				'value' => $date,
				'compare' => '<=',
				'type' => 'DATE',
			),*/
		);
	}	
	
	if(!empty($term)){
		$args["term"] = $term;	
		add_filter( 'posts_where', 'search_title_filter', 10, 2 );
	}
		
	$listings = new WP_Query($args);
	remove_filter( 'posts_where', 'search_title_filter', 10, 2 );
	
	//print_r($listings);
	//print_r($args);
		
	if( $listings->have_posts() ) {
		
		while ($listings->have_posts()) : $listings->the_post();
			spektrix_render_listing(get_the_ID());						
		endwhile;
		
	} else {
		if(empty($date) && empty($params)){
		?>
        <div class="no-events center">
        	<h3>Oh no!</h3>
            <p>It looks like there aren't any events that match your search.</p>
        </div>
        <?php
		} else {
		?>
        <div class="no-events center">
        	<h3>Can't see anything?</h3>
            <p>There's more to be announced in January - stay tuned.</p>
        </div>
        <?php
		}
	}
	
	wp_reset_query();
	
	//$html = ob_get_clean();
	
	if (defined('DOING_AJAX') && DOING_AJAX) {
		//echo json_encode(array("events"=>$listings->post_count,"html"=>$html));
		exit;	
	} else {
		//echo $html;	
	}
}

function spektrix_related_events($pid,$programme,$interests){
	$related = array();
	
	$basic_args = array(
		'post_type' => 'spektrix_event',
		'post_status' => hodephinitely_allowed_status(),
		'post__not_in' => array($pid),
		'meta_key' => 'starts',
		'orderby' => 'rand',
		//'order' => 'ASC',
		'posts_per_page' => 3,
		'meta_query' => array(
			array(
				'key' => 'ends',
				'value' => date("Y-m-d"),
				'compare' => '>=',
				'type' => 'DATE',
			),
		),
	);
	
	
	if(is_array($interests)){
		$int_slugs = array_column(array_map(function($o){return (array)$o;},$interests),'slug');
	} else {
		$int_slugs = array();
	}
		
	$args = $basic_args;
	$args['tax_query'] = array(
		'relation' => "AND",
		array(
			'taxonomy' => 'programme',
			'field'    => 'slug',
			'terms'    => $programme->slug,
		),
		array(
			'taxonomy' => 'interest',
			'field'    => 'slug',
			'terms'    => $int_slugs,
		),
	);
	
	$events = new WP_Query($args);
	if ($events->have_posts() ) :
		while($events->have_posts() ){
			$events->the_post();
			$related[] = get_the_ID();
		}
	endif;
	wp_reset_query();
	
	if(sizeof($related)<3){
		$args = $basic_args;
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'interest',
				'field'    => 'slug',
				'terms'    => $int_slugs,
			),
		);
		
		$events = new WP_Query($args);
		if ($events->have_posts() ) :
			while($events->have_posts() ){
				$events->the_post();
				$related[] = get_the_ID();
			}
		endif;
		wp_reset_query();
	}
	
	if(sizeof($related)<3){
		$args = $basic_args;
		$args['tax_query'] = array(
			'relation' => "OR",
			array(
				'taxonomy' => 'programme',
				'field'    => 'slug',
				'terms'    => $programme->slug,
			),
			array(
				'taxonomy' => 'interest',
				'field'    => 'slug',
				'terms'    => $int_slugs,
			),
		);
		
		$events = new WP_Query($args);
		if ($events->have_posts() ) :
			while($events->have_posts() ){
				$events->the_post();
				$related[] = get_the_ID();
			}
		endif;
		wp_reset_query();
	}
	
	$args = $basic_args;	
	if(sizeof($related)<3){
		$events = new WP_Query($args);
		if ($events->have_posts() ) :
			while($events->have_posts() ){
				$events->the_post();
				$related[] = get_the_ID();
			}
		endif;
		wp_reset_query();
	}
	
	return $related;
}
add_action( 'admin_menu', 'hodephinitely_admin_menu' );

function hodephinitely_admin_menu() {
	add_menu_page( 'Test Area', 'Test Area', 'manage_options', 'test-area.php', 'hodephinitely_test_area', 'dashicons-lock', 80  );
}

function hodephinitely_test_area(){
	?>
	<pre>
    <?php
	$endpoint = "price-lists";
	$call = spektrix_call_api_v2($endpoint,array("event_id"=>"39244"));
	print_r($call);
	?>
	</pre>
    <?php
}

add_filter( 'the_content', 'the_content_filter', 20 );
add_filter('acf_the_content', 'the_content_filter');
function the_content_filter( $content ) {
    $content = preg_replace('#<p.*?>(.*?)</p>#i', '<p>\1</p>', $content);
    $content = preg_replace('#<span.*?>(.*?)</span>#i', '\1', $content);
    $content = preg_replace('#<ol.*?>(.*?)</ol>#i', '<ol>\1</ol>', $content);
    $content = preg_replace('#<ul.*?>(.*?)</ul>#i', '<ul>\1</ul>', $content);
    $content = preg_replace('#<li.*?>(.*?)</li>#i', '<li>\1</li>', $content);
	
	$nbsp = html_entity_decode("&nbsp;");
	$content = html_entity_decode($content);
	$content = str_replace($nbsp, " ", $content);
	//$content = str_replace("&nbsp;", ' ', $content);
	
    return $content;
}

function get_mc_list(){
	return "4cf804779a";
}

add_action( 'wp_ajax_nopriv_subscribe_to_mailchimp', 'subscribe_to_mailchimp' );
add_action( 'wp_ajax_subscribe_to_mailchimp', 'subscribe_to_mailchimp' );

function subscribe_to_mailchimp(){
	check_ajax_referer( 'subscribe_to_mailchimp', 'security' );
	
	$email = strtolower($_POST['email']);
	$hash = md5($email);
	
	include_once "mailchimp-api-api-v3/src/MailChimp.php";

	$MailChimp = new MailChimp('f0dfba34f1b04e66d6dae3f3697eb611-us4');
	
	$chimp = $MailChimp->get('lists/'.get_mc_list().'/members/'.$hash);		
	$status = $chimp['status']=="404" ? "pending" : $chimp['status'];	
	
	$result = $MailChimp->put('lists/'.get_mc_list().'/members/'.$hash, array(
		'email_address'     => $email,
		'status'            => $status,
	));
	
	echo json_encode(array(
		"success"=>$result['status']=="subscribed"||$result['status']=="pending",
		"exists"=>$chimp['status']!=404,
		"unsubscribed"=>$chimp['status']=="unsubscribed"
	));
	
	exit;
}
?>
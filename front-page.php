<?php
get_header();
$image = get_the_post_thumbnail_url(null,"full-width");
spektrix_render_hero($image);
?>
<section id="home-intro">
    <div class="container flex">
    	<div class="half">
        	<?php
            $image = get_field("intro_image");
            ?>
    		<div class="image" <?php echo !empty($image) ? ' style="background-image:url('.$image['sizes']['large'].');"' : ""; ?>></div>
        </div>
        <?php
		$subtitle = get_field("intro_subtitle");
		$text = get_field("intro_text");
		$link = get_field("intro_link");
		?>
        <div class="half">
            <h1><span>Vault</span> <span>Festival 2018</span></h1>
            <h4>
			<?php echo $subtitle; ?>
            <div class="wiggle"></div>
            </h4>
            <?php echo $text; ?>
            <?php
			if(!empty($link)){
			?>
            <a class="ucta" href="<?php echo $link['url']; ?>"><?php echo !empty($link['title']) ? $link['title'] : "More Details"; ?></a>
            <?php
			}
			?>
        </div>
    </div>
</section>
<div class="fade">
	<?php
	$args = array(
	  'post_type' => 'spektrix_event',
	  'post_status' => hodephinitely_allowed_status(),
	  'posts_per_page' => 3,
	  'order' => 'ASC',
	  'orderby' => 'rand',  
	);
		
	if(time() >= strtotime("2018-01-20")){
		$args['meta_query'] = array(
			array(
				'key' => 'ends',
				'value' => date("Y-m-d"),
				'compare' => '>=',
				'type' => 'DATE',
			),
			array(
				'key' => 'starts',
				'value' => date("Y-m-d",strtotime("+7 days")),
				'compare' => '<=',
				'type' => 'DATE',
			),
		);
	}	
	
	$listings = new WP_Query($args);
	if( $listings->have_posts() ) {
	?>
    <section id="home-whats-on" class="featured-events relative">
        <div class="pgrad"></div>
        <div class="container">
            <h2 class="h1">What's On?</h2>
            <h4>Upcoming shows</h4>
            <div class="highlights-wrapper">
            	<div class="dots dots-pink top-right"></div>
                <div class="highlights flex">
                    <?php
						while ($listings->have_posts()) : $listings->the_post();
							spektrix_render_highlight(get_the_ID());
						endwhile;
					?>
                </div>
            </div>
        </div>
    </section>
    <?php
	}
	wp_reset_query();
	?>
    <section id="home-video">
    	<div class="container no-padding">
            <div class="video-wrapper relative">
            	<div class="xo xo-cream"></div>
            	<div class="dots dots-cream"></div>
                <iframe type="text/html" width="100%" height="100%"
                src="https://www.youtube.com/embed/BDWRIQI4LeE?rel=0&showinfo=0&color=white&iv_load_policy=3"
                frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </section>
    <section id="home-ftk">
    	<div class="container relative">
        	<div class="relative flex cream-section">   
            	<?php
				$title = get_field("outro_title");
                $subtitle = get_field("outro_subtitle");
                $text = get_field("outro_text");
                $link = get_field("outro_link");
                ?>         
                <div class="half">
                    <h2 class="h1"><?php echo $title; ?></h2>
                    <div class="wrapper">
                        <h4>
						<?php echo $subtitle; ?>
                        <div class="wiggle"></div>
                        </h4>
                        <?php echo $text; ?>
						<form method="post" class="mailchimp_signup">
                        	<input type="email" name="email_address" placeholder="Email address" />
                            <input type="submit" value="Sign Up" />
                            <p class="signup-status"></p>
                        </form>
                        <?php
						vault_social(true);
						?>
                    </div>
                </div>   
                <div class="half">
					<?php
                    $image = get_field("outro_image");
                    ?>
                    <div class="image" <?php echo !empty($image) ? ' style="background-image:url('.$image['sizes']['large'].');"' : ""; ?>></div>
                </div>
            </div>        
        </div>
    </section>
</div>
<?php
get_footer();
?>
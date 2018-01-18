<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
$pid = get_the_ID();
//$image = get_the_post_thumbnail_url(null,'large');
$image = get_field('banner_image');
@$image = $image['sizes']['full-width'];
$spektrix_id = get_post_meta($pid,"spektrix_id",true);
$programme = get_the_terms( $pid, 'programme' );
$programme = $programme[0];
$interests = get_the_terms( $pid, 'interest' );
$news = get_field("news_articles");
$s = strtotime(get_post_meta($pid,"starts",true));
$e = strtotime(get_post_meta($pid,"ends",true));
spektrix_render_hero($image);
$matinee = "";
$times = get_post_meta($pid,"times",true);
ob_start();		
if(empty($times)){
	?>
	<p>There are currently no dates for this event.</p>
	<?php	
} else {
	?>
	<table id="event-dates">
	<?php
	foreach($times as $time){
		$date = strtotime($time['time']);
		if($time['on_sale'] && $date>=time()){
		$attributes = $time['attributes'];
		if($attributes['Matinee']==1){
			$matinee = $date;
		}
		
		$applicable = array_filter($attributes,function($a){
			return $a==1;
		});
		
		$classes = array_map(function($a){ return sanitize_title($a); },array_keys($applicable));
		?>
		<tr data-ref="<?php echo $time['time_spektrix_id'];?>"<?php echo $date<time() ? ' class="expired"' : ""; ?> class="<?php echo implode(" ",$classes); ?>">
			<td class="desktop"><?php echo date("l j F",$date); ?></td>
			<td class="mobile"><?php echo date("D j M",$date); ?></td>
			<td><?php echo date("H:i",$date); ?></td>
            <td><?php
			foreach($attributes as $label => $set){
				echo $set ? '<span class="date-meta matinee">'.$label.'</span>' : "";
			}
			/*echo !empty($attributes['Matinee']) ? '<span class="date-meta matinee">Matinee</span>' : "";
			echo !empty($attributes['Included in 2 for 1 Offer']) ? '<span class="date-meta two-for-one">Included in 2 for 1</span>' : "";*/
			?></td>
			<td><?php echo $date<time() ? "" : ($time['seats_available']>0 ? '<a class="cta" href="#tickets" data-ref="'.$time['time_spektrix_id'].'">Book<i> Now</i></a>' : 'Sold Out'); ?></td>
		</tr>
		<?php
		}
	}
	?>
	</table>
	<?php
}
$times = ob_get_clean();
?>
<div id="event-details">
    <div class="container thin"> 
        <div class="event-intro">
        	<nav>
            	<ul class="upper">
                    <li class="active" data-ref="details"><a><span>Key Info</span></a></li>
                    <li data-ref="dates"><a><span>Dates<i> &amp; Times</i></span></a></li>
                    <li data-ref="cast"><a><span><i>About </i>the Company</span></a></li>
                	<li data-ref="news"<?php echo !empty($news) ? "" : ' class="disabled"'; ?>><a><span>Press<i> &amp; Reviews</i></span></a></li>
                    <li data-ref="tickets"><a><span>Book Now</span></a></li>
                </ul>
            </nav> 
            <div class="clear"></div>
            
            <div class="pgrad"></div>
            <div class="dots dots-pink"></div>
                        
            <div id="event-variable">
            	<div data-ref="details">
                    <h1><?php the_title(); ?></h1>
                    
                    <?php
					$attributes = get_post_meta($pid,"attributes",true);
					?>
                    
                    <div class="wrapper">
                    	<h4>
						<?php echo get_field("event_subtitle"); ?>
                        <div class="wiggle"></div>
                        </h4>
                    	<div class="flex">
                            <div class="two-thirds">
                                <?php
                                the_content();
								$social = get_field("links");
								if(!empty($social)){
									?>
                                    <div class="social">
                                    <?php
									foreach($social as $link){
										//echo "<!-- print_r($link); -->";
										//if($link['type']!="website"){
										?>
                                        <a target="_blank" href="<?php echo $link['url']; ?>"><?php getSVG($link['type']); ?></a>
                                        <?php	
										//}
									}
									?>
                                    </div>
                                    <?php
								}
								
								$gallery = get_field("gallery_images");
								if(!empty($gallery)){
									?>
                                    <div id="lightgallery" class="flex">
                                    	<?php
										foreach($gallery as $image){
										?>
										<a href="<?php echo $image['sizes']['large']; ?>" 
                                        style="background-image:url(<?php echo $image['sizes']['thumbnail']; ?>);"></a>
										<?php
										}
										?>
                                    </div>
                                    <?php	
								}
                                ?>                            
                            </div>
                            
                            <div class="third">
                                <div id="main-details">
                                	<div class="relative grouper">
                                        <div class="event-labels flex">
                                            <?php										
                                            $dates = spektrix_format_dates($pid);
                                            $price = (float)get_post_meta($pid,"price",true);
											$price += 1.5;
                                            
                                            $atts = array(
                                                "Genre" => $programme->name,
                                                "Duration" => hodephinitely_convert_time(get_post_meta($pid,'duration',true),'%2dhr %02dm'),
                                                "Location" => !empty($attributes['Location']) ? $attributes['Location'] : "-",
                                                "Dates" => $dates,
                                                "Pricing" => "£" . number_format($price,2),
                                                "Suitability" => $attributes['Suitability'],
                                                //"Accessibility" => "",
                                                "Time" => date("H:i",$s),
                                            );
											
											if(!empty($accessibility)){
												$atts["Accessibility"] = "";
											}
                                            
                                            if(!empty($matinee)){
                                                $atts['Matinee'] = date("d M",$matinee);
                                            }
                                            
                                            foreach($atts as $label=>$value){
                                            ?>
                                            <div class="event-label">
                                                <label><?php echo $label; ?></label>
                                                <span class="value"><?php echo $value; ?></span>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <a class="cta" href="#tickets">Book Now</a>
                                        
                                        <div class="xo xo-pink"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div data-ref="dates">
                	<h1>Dates</h1>
                	<?php
					echo $times;
					?>
                </div>
                <div data-ref="cast" id="cast-creatives">
                    <h1>About the company</h1>
                    <div class="wrapper">                        
                        <?php
						$subtitle = get_field("company_name");
                        $about = get_field("about_the_company");
                        ?>
                        <h4><?php echo $subtitle; ?></h4>
                        <?php
						echo $about;
						?>
                    </div>
                    
                    <?php 
					$team = $cast = array();
					$crew = get_field("cast_crew");
					
					if(!empty($crew)){
					?>                    
                    <div id="cast">
                    	<div class="wrapper"> 
                        	<div class="flex">       
							<?php
                            foreach($crew as $member){
                                if($member['type']=="artistic") $team[] = $member; else $cast[] = $member;
                            }
							
							if(!empty($team)){
								?>
                                <div class="cast-list">
                                <h5>Artistic Team</h5>
                                <?php
								foreach($team as $member){
									?>
									<div class="cast-member">
										<div class="position"><?php echo $member['role']; ?></div>
										<div class="name"><?php echo $member['name']; ?></div>
									</div>
									<?php	
								}
								?>
                                </div>
                                <?php
							}
							
							if(!empty($cast)){
								?>
                                <div class="cast-list">
                                <h5>Cast</h5>
                                <?php
								foreach($cast as $member){
									?>
									<div class="cast-member">
										<div class="position"><?php echo $member['role']; ?></div>
										<div class="name"><?php echo $member['name']; ?></div>
									</div>
									<?php	
								}
								?>
                                </div>
                                <?php
							}
                            ?>
                            </div>
                        </div>
                    </div>
                    <?php
					}
					?>
                </div>
                    
                <?php								
				if(!empty($news)){
					?>
                    <div data-ref="news">
                    	<h1>Press &amp; Reviews</h1>
						<?php
                        usort($news, function ($item1, $item2) {
                            if ($item1['date'] == $item2['date']) return 0;
                            return $item1['date'] > $item2['date'] ? -1 : 1;
                        });
                        
                        foreach($news as $article){
                            ?>
                            <article class="event-news">
                                <h5><?php echo $article['title']; ?></h5>
                                <div class="wrapper">
                                	<?php
									if(!empty($article['date'])){
										?>
										<div class="date">Posted on <?php echo date("j F Y",strtotime($article['date'])); ?></div>
										<?php 
									}
									
                                    echo $article['excerpt']; 
                                    if(!empty($article['link'])){
                                        echo '<a target="_blank" class="ucta" href="'.$article['link'].'">Read the full article</a>';	
                                    }
                                    ?>
                                </div>
                            </article>
                            <?php
                        }
                        ?>
                    </div>
                <?php
				}
				?>
                <div data-ref="tickets">
                	<iframe name="SpektrixIFrame" id="SpektrixIFrame" frameborder="0" scrolling="no"
                    src="https://system.spektrix.com/<?php echo spektrix_get_client(); ?>/website/EventDetails.aspx?resize=true&EventId=<?php echo $spektrix_id; ?>"></iframe>
                </div>
                
        	</div>
        </div>
                
    </div>
    
    <?php
	$related = spektrix_related_events($pid,$programme,$interests);
	
	if (!empty($related)) : 
	?>
	<div class="fade">
        <section id="related-events" class="featured-events relative">
            <div class="pgrad"></div>
            <div class="container">
                <h2 class="h1">Recommended Shows</h2>
                <div class="highlights-wrapper">
                    <div class="highlights flex">
                    	<?php
						foreach($related as $id){
							spektrix_render_highlight($id);
						}
						?>
                    </div>
                </div>
            </div>
        </section>
	</div>
	<?php
	endif;
	?>
    
    <div class="clear"></div>
</div>
<?php
endwhile;
endif;
get_footer();
?>
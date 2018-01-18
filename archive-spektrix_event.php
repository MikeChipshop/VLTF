<?php
get_header();
$image = get_the_post_thumbnail_url(66,"full-width");
spektrix_render_hero($image);
?>
<section>
    <div class="container">        
        <div id="whatson" class="relative">
        
        	<div class="dots dots-pink"></div>
            <div class="xo xo-pink"></div>
            
        	<nav class="center">
            	<menu class="flex upper date">
                    <span class="current"><a>Week</a></span>
                    <span><a>Day</a></span>
                    <span><a><label>Pick a date<input type="text" id="datepicker" /></label></a></span>
            	</menu>
            
            	<?php
				$start = "2018-01-24";
				$j = $use = "";
				$day = array("One","Two","Three","Four","Five","Six","Seven","Eight");
				$alldates = array();
				?>
                <script>
				var weeks = ['<?php echo implode("','",$day); ?>'];
				</script>
                <?php
				ob_start();
				
				for($i=1;$i<=8;$i++){
					$current = $start . " + ".($i-1)." weeks";	
					$e = $current . " + 4 days";
					$sm = date("F",strtotime($current));
					$em = date("F",strtotime($e));
					$dates = date("d",strtotime($current)) . ($sm!=$em ? " " . $sm : "") . " &mdash; " . date("d F Y",strtotime($e));
					?>
                    <div class="date
                    <?php					
					if(time() < strtotime($current) && empty($j)){
						$j = $i;
						$use = $dates;
						$earliest = strtotime($current) > time() ? date("Y-m-d",strtotime($current)) : date("Y-m-d");
						echo " current";
					}
					?>
                    "><?php echo $dates; ?></div>
                    <?php
				}
				
				$alldates = ob_get_clean();
				?>
            
            	<h2>
                	<span>
                    	<a class="previous<?php echo $j<=1 ? " disabled" : ""; ?>"></a>
                    	<b>Week <?php echo $day[$j-1]; ?></b>
                        <a class="next"></a>
                    </span>
                </h2>
                
                <div class="dates">
					<?php echo $alldates; ?>
                </div>
            
            	<menu class="flex upper genre">
                	<span class="current"><a>All</a></span>
                    <span><a>Theatre</a></span>
                    <span><a>Comedy</a></span>
                    <span><a>Lates</a></span>
                    <span><a>Film</a></span>
                    <!--<span><a>Search</a></span>-->
            	</menu>
                <?php
				if(is_user_logged_in()){
				?>
                <select id="interests">
                	<option value="">Interest</option>
                	<?php
					$terms = get_terms( array(
						'taxonomy' => 'interest',
						'hide_empty' => true,
					) );
					
					foreach($terms as $term){
						?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                        <?php	
					}
					?>
                </select>
                <?php
				}
				?>
            </nav>
            
            <div id="listings" class="flex loading">
				<?php
				//spektrix_load_listings(array("week"=>'week-'.$j));
				?>
                <script>
                var week_ref = <?php echo $j; ?>;
				var week_max = <?php echo sizeof($day); ?>;
				var earliest_date = new Date("<?php echo $earliest; ?>");
				</script>
            </div>
        </div>
	</div>
</section>
<?php
get_footer();
?>
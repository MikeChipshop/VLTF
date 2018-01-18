<?php 
get_header(); 
$term = get_query_var( 'term' );
?>
<section>
    <div class="container">        
        <div id="whatson" class="relative">
        	<div class="center">
                <h2>Search Results</h2>
                <div class="dates">
                	<?php
                    if(!empty($term)){
					?>
                    You searched for "<?php echo $term; ?>"
                    <?php
					} else {
					?>
                    You didn't search for anything
                    <?php
					}
					?>
                </div>
            </div>
            
			<div id="listings" class="flex bordered">
				<?php
                if(!empty($term)){
                    spektrix_load_listings(array("term"=>$term));
                } else {
                ?>
                <div class="no-events center">
                    <h3>Oh no!</h3>
                    <p>You need to enter a search term</p>
                </div>
                <?php
                }	
                ?>
            </div>
        </div>
    </div>
</section>
<?php 
get_footer(); 
?>
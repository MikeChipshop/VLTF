<?php
/**
 * Template Name: Split Page
 */

get_header();
?>
<div class="container text">
	<?php
	$sections = get_field("section");
	foreach($sections as $section){
	?>
	<div class="flex">
		
		<div class="third marged">
			<h3><?php echo $section['section_title']; ?></h3>
			<?php
			echo $section['left_channel'];
			?>
		</div>

		<div class="two-thirds marged">
			<?php
			echo $section['right_channel'];
			?>
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php
get_footer();
?>
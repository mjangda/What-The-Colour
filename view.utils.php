<?php 

function print_palette( $colours, $match_all = true ) {
	?>
	<div class="clPalette">
		<?php print_colour_palette( $colours ); ?>
		<div class="clPaletteActions">
			match: 
			<a href="#" class="button">all</a>
			<a href="#" class="button">individual</a>
		</div>
	</div>
	<?php
}

function print_colour_palette( $colours ) {
	?>
	<div class="clColorPalette">
		<?php foreach( $colours as $colour ) : ?>
			<div class="clColorSingle" rel="<?php echo $colour; ?>" style="<?php print_bg( $colour ); ?>">
				<span>#<?php echo $colour; ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

function print_image_palette( $colours, $match_all = true ) {
	?>
	<div class="clImagePalette">
		<?php 
		if( $match_all ) {
			$images = get_images( $colours );
		} else {
			$images = array();
			foreach( $colours as $colour ) {
				$images[] = get_images( $colour );
			}
		}
		?>
		
		<?php $img_count = 0; ?>
		<?php if( ! empty( $images ) ) : ?>
			<?php foreach( $images as $image ) : ?>
				<?php if( !$match_all && $img_count >= MAX_IMAGES ) break; ?>
				<?php print_image_single( $image, $colours[ (($img_count >= MAX_COLOURS) ? 0 : $img_count) ] ); ?>
				<?php $img_count++; ?>
			<?php endforeach; ?>
		<?php else : ?>
			<p>Aww, we didn't find anything :(</p>
		<?php endif; ?>
	</div>
	<?php 
}

function print_image_single( $images, $colour ) {
	$img_count = 0;
	?>
	<div class="clImageSingle">
		<?php if( !is_array( $images ) ) $images = array( $images ); ?>
		<?php foreach( $images as $image ) : ?>
			<?php if( $img_count >= MAX_IMAGES ) break; ?>
			<?php $img_url = get_image_url( $image->filepath ); ?>
			<?php print_img( $img_url, $colour ); ?>
			<?php $img_count++; ?>
		<?php endforeach; ?>
	</div>
	<?php
}

function get_bg( $colour ) {
	return 'background:#' . $colour;
}
function print_bg( $colour ) {
	if( $colour )
		echo get_bg( $colour );
}

function print_img( $url, $colour = '', $class ='' ) {
	echo '<img src="'. $url .'" style="'. get_bg( $colour ) .'" class="'. $class .'" />';
}
?>
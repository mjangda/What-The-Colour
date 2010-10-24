<?php if( !$palettes ) : ?>
	<div class="error">
		<h1>ERROR!</h1>
		<p>
			YOU BROKE SOMETHING! HAPPY?!<br />
			<small>I'm going to go cry now!</small>
		</p>
	</div>
<?php else : ?>
	
	<?php $palette_count = 0; ?>
	
	<?php foreach( $palettes as $palette ) : ?>
		<?php if( $palette_count >= MAX_PALETTES ) break; ?>
		<?php out('Palette pre-loop: ', $palette); ?>
		<?php $colours = $palette['colors']['hex']; ?>
		
		<?php print_palette( $colours, false ); ?>
		<?php $palette_count++; ?>
	<?php endforeach; ?>
	
<?php endif; ?>
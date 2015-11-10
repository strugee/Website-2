<?php

/**
 * Markup for a list of items with associated images.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

$list_item_count = count( get_sub_field( 'image_list' ) );
$halfway = ceil( $list_item_count / 2 ); // When to break into the second column
$i = 0;

?>
<div class="two-columns">
	<div class="column-1">
		<?php while ( has_sub_field( 'image_list' ) ): ?>

			<?php // Break into the second column if we're at the halfway point
			if ( $i == $halfway ): ?>
				</div> <!-- .column-1 -->
				<div class="column-2">
			<?php endif; ?>

			<div class="profile">
				<?php if ( get_sub_field( 'image' ) ): ?>
					<?php $image = get_sub_field( 'image' ); ?>

					<figure class="profile-image">
						<img src="<?php echo $image['url']; ?>" />
					</figure>
				<?php endif; ?>

				<div class="profile-body">
					<h3>
						<?php if ( get_sub_field( 'url' ) ): ?>
							<a href="<?php the_sub_field( 'url' ); ?>">
						<?php endif; ?>
				
						<?php the_sub_field( 'header' ); ?>
							
						<?php if ( get_sub_field( 'url' ) ): ?>
							</a>
						<?php endif; ?>
					</h3>

					<?php if ( get_sub_field( 'text' ) ): ?>
						<?php the_sub_field( 'text' ); ?>
					<?php endif; ?>
				</div> <!-- .profile-body -->
			</div> <!-- .profile -->

			<?php $i++; ?>

		<?php endwhile; ?>
	</div> <!-- .column-2 -->
</div> <!-- .two-columns -->

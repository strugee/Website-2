<?php

/**
 * Displays the status of the printers in the lab.
 *
 * This file can be used for a page in WordPress by setting the
 * page template to "Lab Printer Status".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Template Name: Lab Printer Status

$json = file_get_contents('http://xana.cif.rochester.edu/printer_status/');
$obj = json_decode($json);

$list_item_count = count( $obj->printers );
$halfway = ceil( $list_item_count / 2 ); // When to break into the second column
$i = 0;

get_header();

while ( have_posts() ):
	the_post(); ?>
<div class="content">
	<h1><?php the_title(); ?></h1>
	<div class="two-columns">
		<div class="column-1">
			<?php while ( $i < $list_item_count ): ?>

				<?php $printer = $obj->printers[$i]; ?>
				<?php // Break into the second column if we're at the halfway point
				if ( $i == $halfway ): ?>
					</div> <!-- .column-1 -->
					<div class="column-2">
				<?php endif; ?>

				<div class="profile">
					<div class="profile-body">
						<h3><?php echo $printer->model; ?></h3>
						<h6><?php echo $printer->description; ?></h6>

						<?php if ($printer->status != 'Offline'): ?>
							<div class="four-columns">
								<div class="column">
									<strong>Toner Level</strong>
									<?php if (is_numeric($printer->toner)): ?>
										<progress max="100" value="<?php echo $printer->toner; ?>">
											<span class="progress-bar-fallback" style="width: <?php echo $printer->toner; ?>%"><?php echo $printer->toner; ?>%</span>
										</progress>
									<?php else: ?>
										 <p><?php echo $printer->toner; ?></p>
									<?php endif ?>
								</div>

								<div class="last column">
									<h4>Paper Trays</h4>
									<p><?php echo implode(', ', $printer->paper); ?></p>
								</div>
							</div>
						<?php endif ?>

						<h4>Status</h4>
						<p><?php echo $printer->status; ?></p>
					</div>
				</div>

				<?php $i++; ?>

			<?php endwhile; ?>
		</div> <!-- .column-2 -->
	</div> <!-- .two-columns -->
</div>
<?php

endwhile;

get_footer();

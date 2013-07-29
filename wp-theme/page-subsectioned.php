<?php

/**
 * Contains the markup for displaying a document with
 * multiple sections and subsections.
 *
 * This file can be used for a page in WordPress by setting the
 * page template to "Subsectioned Document".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

// Template Name: Subsectioned Document

get_header();

while ( have_posts() ): ?>
	<?php the_post(); ?>

	<div class="content">
		<h1><?php the_title(); ?></h1>

		<div class="sidebar">
			<h3>Contents</h3>
			<ul class="secondary-menu">
				<?php $section_int = 0; ?>
				<?php while( has_sub_field( 'sections' ) ): ?>
					<?php

					$section_int += 1;
					$section_number = $section_int . '.';
					$title = $section_number . ' ' . get_sub_field( 'section_title' );
					
					?>
					<li>
						<a href="#<?php echo urlencode( $title ); ?>">
							<?php echo $title; ?>
						</a>
						<?php if ( get_sub_field( 'subsections' ) ): ?>
							<ul>
								<?php $subsection_int = 0; ?>
								<?php while ( has_sub_field( 'subsections' ) ): ?>
									<?php
									
									$subsection_int += 1;
									$subsection_number = $section_number . $subsection_int . '.';
									$title = $subsection_number . ' ' . get_sub_field( 'subsection_title' );
									
									?>
									<li>
										<a href="#<?php echo urlencode( $title ); ?>">
											<?php echo $title; ?>
										</a>
									</li>
								<?php endwhile; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div> <!-- .sidebar -->

		<div class="fluid two-columns sidebar-content">
			<div class="column">
				<?php $section_int = 0; ?>
				<?php while( has_sub_field( 'sections' ) ): ?>
					<?php
					
					$section_int += 1;
					$section_number = $section_int . '.';
					$title = $section_number . ' ' . get_sub_field( 'section_title' );
					
					?>
					<h2 id="<?php echo urlencode( $title ); ?>"><?php echo $title; ?></h2>

					<?php the_sub_field( 'section_contents' ); ?>

					<?php $subsection_int = 0; ?>
					<?php while ( has_sub_field( 'subsections' ) ): ?>
						<?php
						
						$subsection_int += 1;
						$subsection_number = $section_number . $subsection_int . '.';
						$title = $subsection_number . ' ' . get_sub_field( 'subsection_title' );
						
						?>
						<h3 id="<?php echo urlencode( $title ); ?>"><?php echo $title; ?></h3>
						
						<?php the_sub_field( 'subsection_contents' ); ?>
					<?php endwhile; ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
<?php endwhile; ?>
<?php get_footer();

<?php

/**
 * Template for the home page.
 * This includes a hero block with a large, randomized graphic
 * masked with the CIF logo, blurbs for CIF services,
 * an upcoming events sidebar, and our latest announcements.
 *
 * This file can be used for a page in WordPress by setting the
 * page template to "Home Page".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Template Name: Home Page

get_header(); ?>

<div class="content" role="main">
	<?php while ( have_posts() ): ?>
		<?php

		the_post();

		$hero_graphics = get_field( 'hero_graphics' );
		$hero_graphic = $hero_graphics[ array_rand( $hero_graphics ) ];
		$hero_src = $hero_graphic['sizes']['cif-hero-size'];
		$hero_alt = $hero_graphic['alt'];

		?>
		<section class="hero">
			<div class="hero-graphic-logo-mask">
				<img src="<?php echo $hero_src; ?>" alt="<?php echo $hero_alt; ?>" />
			</div>

			<div class="hero-body">
				<h1><?php the_title(); ?></h1>
				<?php the_field( 'welcome_paragraph' ); ?>
			</div>
		</section>
	<?php endwhile; ?>

	<div class="sidebar align-right">
		<h2>Upcoming Events</h2>
		<?php echo do_shortcode( '[google-calendar-events id="1" type="list" max="3"]' ); ?>
	</div>

	<h2>
		Announcements
		<a class="icon-rss" href="<?php bloginfo( 'rss2_url' ); ?>?post_type=announcements">
			<span class="screen-reader-text">RSS feed</span>
		</a>
	</h2>

	<section class="articles">

		<?php 

		// Get the latest 3 announcements and display them
		$announcements_query = new WP_Query( array(
			'post_type'      => 'announcements',
			'posts_per_page' => 3,
		) );

		if ( $announcements_query->have_posts() ) {
			while ( $announcements_query->have_posts() ) {
				$announcements_query->the_post();

				get_template_part( 'templates/content', 'announcements' );
			}

			echo '<a class="button" href="announcements">See older announcements</a>';
		} else {
			get_template_part( 'templates/no-content', 'announcements' );
		}
		
		wp_reset_query(); // Switch back from the announcement query

		?>
	</section> <!-- .articles -->

	<div class="fluid four-columns">
		<?php

		$blurb_count = count( get_field( 'blurbs' ) );
		$i = 0;

		?>
		<?php while ( has_sub_field( 'blurbs' ) ): ?>
			<?php $i++;?>

			<?php if ( $i === $blurb_count ): ?>
				<div class="last column">
			<?php else: ?>
				<div class="column">
			<?php endif; ?>

				<?php $image = get_sub_field( 'blurb_image' ); ?>
				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

				<h2><?php the_sub_field( 'blurb_title' ); ?></h2>

				<?php the_sub_field( 'blurb_body' ); ?>

				<a class="button align-right" href="<?php the_sub_field( 'blurb_link' ); ?>">
					<?php the_sub_field( 'blurb_link_text' ); ?>
				</a>
			</div> <!-- .column -->
		<?php endwhile; ?>
	</div> <!-- .fluid.four-columns -->
</div>
<?php get_footer();

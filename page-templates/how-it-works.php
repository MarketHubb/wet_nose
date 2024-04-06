<?php
/* Template Name: How it Works */
get_header(); ?>

<?php
$text_hero = get_field('text_hero');
get_template_part('template-parts/hero/content', 'center-text', $text_hero);
?>

<?php if (have_rows('section')) : ?>
	<?php while (have_rows('section')) : the_row();  ?>

		<?php $column_order = (get_row_index() % 2 !== 0) ? 'order-last' : 'order-first'; ?>

		<div class="container mx-auto mb-8">
			<div class="bg-white py-24">
				<div class="mx-auto max-w-8xl px-6 lg:px-8">
					<div class="mx-auto grid max-w-2xl grid-cols-12 items-start gap-x-8 gap-y-16 sm:gap-y-24 lg:mx-0 lg:max-w-none">
						<div class="lg:pr-4 col-span-12 md:col-span-6">
							<img class="w-full h-auto shadow-2xl rounded-3xl " src="<?php echo get_sub_field('image'); ?>" alt="">
							
								<?php if (get_sub_field('include_testimonial')) { ?>
									<blockquote class="mt-12 text-lg text-center px-4 md:pl-0 md:pr-12 font-semibold leading-7 italic">
										<p>"<?php echo get_sub_field('testimonial'); ?>"</p>
									</blockquote>
									<figcaption class="mt-6 text-sm leading-6 text-center text-gray-500">
										<strong class="font-semibold"><?php echo get_sub_field('author'); ?></strong> - Happy Wet Nose Customer
									</figcaption>
								<?php } ?>
						</div>
						<div class="col-span-12 md:col-span-6 <?php echo $column_order; ?>">
							<div class="text-regular text-gray-700 lg:max-w-2xl">
								<p class="text-lg font-bold stylized text-primary"><?php echo get_sub_field('callout'); ?></p>
								<h2 class="mt-2 text-3xl  text-gray-900"><?php echo get_sub_field('heading'); ?></h2>
								<div class=" mt-8 content-simple">
									<?php echo get_sub_field('content'); ?>
								</div>
							</div>
							<dl class="hidden mt-10 grid grid-cols-2 gap-8 border-t border-gray-900/10 pt-10 sm:grid-cols-4">
								<div>
									<dt class="text-sm font-semibold leading-6 text-gray-600">Founded</dt>
									<dd class="mt-2 text-3xl font-bold leading-10 tracking-tight text-gray-900">2021</dd>
								</div>
								<div>
									<dt class="text-sm font-semibold leading-6 text-gray-600">Employees</dt>
									<dd class="mt-2 text-3xl font-bold leading-10 tracking-tight text-gray-900">37</dd>
								</div>
								<div>
									<dt class="text-sm font-semibold leading-6 text-gray-600">Countries</dt>
									<dd class="mt-2 text-3xl font-bold leading-10 tracking-tight text-gray-900">12</dd>
								</div>
								<div>
									<dt class="text-sm font-semibold leading-6 text-gray-600">Raised</dt>
									<dd class="mt-2 text-3xl font-bold leading-10 tracking-tight text-gray-900">$25M</dd>
								</div>
							</dl>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
<?php endif;  ?>


<?php get_footer(); ?>
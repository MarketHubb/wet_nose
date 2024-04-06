<?php get_header(); ?>

<div id="recipes" class="bg-main py-10" data-postid="<?php echo get_the_ID(); ?>">

    <?php get_template_part('template-parts/shared/content', 'hero-text-centered'); ?>

    <?php get_template_part('template-parts/internal/content', 'recipes'); ?>

</div>

<?php get_footer(); ?>

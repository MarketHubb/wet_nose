<?php /* Template Name: Recipes */
get_header(); 
?>

<div class="bg-main py-10">

<?php //get_template_part('template-parts/internal/content', 'ingredients'); ?>
<?php //get_template_part('template-parts/tailwind/content', 'product-page'); ?>

<?php 
$recipes = get_posts(array(
	'post_type' => 'recipe',
	'posts_per_page' => -1,
	'meta_key' => 'active',
	'meta_value' => true
));

foreach ($recipes as $recipe) {
	$recipe_inputs = getIngredientsforRecipes($recipe->ID, 1);
	get_template_part( 'template-parts/recipes/content', 'main', $recipe_inputs);
}


 ?>

</div>

<?php get_footer(); ?>
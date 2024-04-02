<?php
$recipes = get_posts(array(
   'post_type' => 'recipe',
   'posts_per_page' => -1,
   'meta_key' => 'active',
   'meta_value' => true
));

$output = '';
$i = 1;

foreach ($recipes as $recipe) {
   $output .= recipe_details_for_doggo_profile($recipe->ID, $i);
   $i++;
}

echo $output;


?>
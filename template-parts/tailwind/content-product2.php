<?php //$recipe = get_post(198); 
?>
<?php
$recipes = get_posts(array(
   'post_type' => 'recipe',
   'posts_per_page' => -1,
   'post__in' => []
));
?>

<?php if (isset($recipes) && count($recipes) > 0) { ?>

   <?php foreach ($recipes as $recipe) { ?>

      <div class="container mx-auto">
         <section class="profile-recipe-accordion" aria-labelledby="details-heading" class="mt-12">
            <h2 id="details-heading" class="sr-only">Additional details</h2>

            <div class="divide-y divide-gray-200">

               <?php $recipe_inputs = getIngredientsforRecipes($recipe->ID, 1); ?>

               <!-- Container (Description) -->
               <div>
                  <!-- Expand/collapse button -->
                  <h3>
                     <button type="button" class="group relative flex w-full items-center justify-between py-6 text-left" aria-controls="disclosure-1" aria-expanded="false">
                        <!-- Open: "text-indigo-600", Closed: "text-gray-900" -->
                        <span class="text-gray-900 text-sm font-medium">Description</span>
                        <span class="ml-6 flex items-center">
                           <!-- Open: "hidden", Closed: "block" -->
                           <svg class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                           </svg>
                           <!-- Open: "block", Closed: "hidden" -->
                           <svg class="hidden h-6 w-6 text-indigo-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                           </svg>
                        </span>
                     </button>
                  </h3>
                  <!-- Content -->
                  <div class="prose prose-sm pb-6 hidden" id="disclosure-1">
                     <p><?php echo get_field('recipe_description', $recipe->ID); ?></p>
                  </div>
               </div>

               <!-- Container (Ingredients) -->
               <div>
                  <!-- Expand/collapse button -->
                  <h3>
                     <button type="button" class="group relative flex w-full items-center justify-between py-6 text-left" aria-controls="disclosure-2" aria-expanded="false">
                        <!-- Open: "text-indigo-600", Closed: "text-gray-900" -->
                        <span class="text-gray-900 text-sm font-medium">Ingredients</span>
                        <span class="ml-6 flex items-center">
                           <!-- Open: "hidden", Closed: "block" -->
                           <svg class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                           </svg>
                           <!-- Open: "block", Closed: "hidden" -->
                           <svg class="hidden h-6 w-6 text-indigo-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                           </svg>
                        </span>
                     </button>
                  </h3>
                  <!-- Content -->
                  <div class="prose prose-sm pb-6 hidden" id="disclosure-2">
                     <?php $ingredient_count = count($recipe_inputs[0]['ingredients']); ?>
                     <p class="text-base">
                        Only <span class="emphasis whitespace-nowrap font-bold"><?php echo $ingredient_count; ?> simple ingredients</span>
                     </p>

                     <dl class="grid grid-cols-1 text-base leading-7 text-gray-600">
                        <?php
                        $ingredient_list = '';
                        foreach ($recipe_inputs[0]['ingredients'] as $ingredient) {
                           $ingredient_list .= '<div class="relative pl-0 py-0">';
                           $ingredient_list .= '<dt class="font-semibold text-gray-900">';
                           $ingredient_list .= '<img class="inline-block rounded-full max-h-8 mr-3 my-0" src="' . get_field('ingredient_image_square', $ingredient['post_id'])['url'] . '" />';
                           $ingredient_list .= '<span class="text-base inline-block">' . $ingredient['ingredient'] . '</span>';
                           $ingredient_list .= '</dt><dd class="">';
                           $ingredient_list .= '<span></span>';
                           $ingredient_list .= '</dd></div>';
                        }
                        echo $ingredient_list;
                        ?>
                     </dl>
                  </div>
               </div>

               <!-- Container (Macros) -->
               <div>
                  <!-- Expand/collapse button -->
                  <h3>
                     <button type="button" class="group relative flex w-full items-center justify-between py-6 text-left" aria-controls="disclosure-3" aria-expanded="false">
                        <!-- Open: "text-indigo-600", Closed: "text-gray-900" -->
                        <span class="text-gray-900 text-sm font-medium">Macros</span>
                        <span class="ml-6 flex items-center">
                           <!-- Open: "hidden", Closed: "block" -->
                           <svg class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                           </svg>
                           <!-- Open: "block", Closed: "hidden" -->
                           <svg class="hidden h-6 w-6 text-indigo-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                           </svg>
                        </span>
                     </button>
                  </h3>
                  <!-- Content -->
                  <div class="prose prose-sm pb-6 hidden" id="disclosure-3">
                     <dl class="grid grid-cols-2 gap-y-3 content-center items-center md:grid-cols-2 text-base">
                        <dt class="mt-0">
                           <span class="stylized  inline-block w-full">Protein:</span>
                        </dt>
                        <dd class="mt-0 border-transparent">
                           <span class="stylized font-bold ps-0 mt-0"><?php echo $recipe_inputs[0]['totals']['protein']['percent']; ?>%</span>
                        </dd>
                        <dt class="mt-0">
                           <span class="stylized  inline-block w-full">Fat:</span>
                        </dt>
                        <dd class="mt-0">
                           <span class="stylized font-bold ps-0 mt-0"><?php echo $recipe_inputs[0]['totals']['fat']['percent']; ?>%</span>
                        </dd>
                        <dt class="mt-0">
                           <span class="stylized  inline-block w-full">Carbs:</span>
                        </dt>
                        <dd class="mt-0">
                           <span class="stylized font-bold ps-0 mt-0"><?php echo $recipe_inputs[0]['totals']['carbs']['percent']; ?>%</span>
                        </dd>
                     </dl>
                  </div>

               </div>
         </section>
      </div>
   <?php } ?>


<?php } ?>
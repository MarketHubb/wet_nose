<div class="mx-auto max-w-7xl">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8" id="recipe-tables-container">

                    <?php
                    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $recipes = getIngredientsforRecipes(get_the_ID(), 1);
                    highlight_string("<?php\n\$recipes =\n" . var_export($recipes, true) . ";\n?>");
                    $output = '';

                    foreach ($recipes as $recipe) {
                        echo get_recipe_table($recipe);
                    }

                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($args)) { ?>

    <section id="recipes-list">
        
        <?php foreach ($args as $recipe) { ?>

            <div class="bg-white py-28">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto items-center grid max-w-2xl grid-cols-1 gap-x-12 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">

                        <div class="flex items-start lg:order-first col-span-1 overflow-hidden">
                            <div class="relative">
                                <?php
                                $recipe_title = get_the_title($recipe['id']);
                                $recipe_description = get_field('recipe_description', $recipe['id']);
                                $recipe_image = get_field('recipe_image', $recipe['id']);
                                $recipe_icon = get_field('recipe_icon', $recipe['id']);

                                if ($recipe_image) {
                                    echo '<img src="' . $recipe_image['url'] . '" alt="" class="mt-16 rounded-xl shadow-xl ring-1 ring-gray-400/10 max-w-full overflow-hidden" width="" height="">';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="lg:ml-auto lg:pl-4 lg:pt-4 col-span-1">

                            <div class="grid grid-cols-2 items-center py-6">

                                <?php
                                if ($recipe_title) { ?>
                                    <div class="inline-block">
                                        <?php echo section_heading($recipe_title, "mt-0 md:text-6xl"); ?>
                                    </div>
                                <?php } ?>

                                <?php if ($recipe_icon) { ?>
                                    <div class="inline-block ml-auto md:pr-10 lg:pr-20">
                                        <img src="<?php echo $recipe_icon['url']; ?>" alt="" class="max-h-8 overflow-hidden" width="" height="">
                                    </div>
                                <?php } ?>

                            </div>

                            <?php
                            if ($recipe_description) {
                                echo section_description($recipe_description);
                            }
                            ?>

                            <div class="my-10 md:mt-20 md:mb-0">
                                <p class="mb-2 italic text-center">Macro-nutrient breakdown:</p>
                                <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xl  p-6 bg-tan">
                                    <dt class="md:col-span-2">
                                        <span class="stylized text-right inline-block w-full">Protein:</span>
                                    </dt>
                                    <dd class="md:col-span-2">
                                        <span class="stylized font-bold"><?php echo $recipe['totals']['protein']['percent']; ?>%</span>
                                    </dd>
                                    <dt class="md:col-span-2">
                                        <span class="stylized text-right inline-block w-full">Fat:</span>
                                    </dt>
                                    <dd class="md:col-span-2">
                                        <span class="stylized font-bold"><?php echo $recipe['totals']['fat']['percent']; ?>%</span>
                                    </dd>
                                    <dt class="md:col-span-2">
                                        <span class="stylized text-right inline-block w-full">Carbs:</span>
                                    </dt>
                                    <dd class="md:col-span-2">
                                        <span class="stylized font-bold"><?php echo $recipe['totals']['carbs']['percent']; ?>%</span>
                                    </dd>
                                </dl>
                            </div>


                        </div>

                    </div>

                    <div class="mx-auto max-w-7xl mt-28 mb-16">
                        <?php $ingredient_count = count($args[0]['ingredients']); ?>
                        <h4 class="text-3xl text-center">
                            Only <span class="emphasis whitespace-nowrap font-bold"><?php echo $ingredient_count; ?> ingredients </span> in our <span class="lowercase font-heading"><?php echo get_the_title($recipe['id']); ?> recipe</span>

                        </h4>
                    </div>

                    <dl class="grid grid-cols-2 lg:grid-cols-5 gap-8 text-base leading-7 text-gray-600">

                        <?php
                        $ingredient_list = '';
                        foreach ($args[0]['ingredients'] as $ingredient) {
                            $ingredient_list .= '<div class="relative pl-9 py-3">';
                            $ingredient_list .= '<dt class="font-semibold text-gray-900">';
                            $ingredient_list .= '<img class="rounded-lg max-h-28" src="' . get_field('ingredient_image_square', $ingredient['post_id'])['url'] . '" />';
                            $ingredient_list .= '<span class="text-xl pt-3 inline-block">' . $ingredient['ingredient'] . '</span>';
                            $ingredient_list .= '</dt><dd class="">';
                            $ingredient_list .= '<span class="line-clamp-4 rounded-lg pt-3 hover:text-black hover:px-3 hover:pb-3 hover:bg-tan hover:z-10 hover:shadow-xl hover:line-clamp-none hover:absolute">' . get_field('ingredient_description', $ingredient['post_id']) . '</span>';
                            $ingredient_list .= '</dd></div>';
                        }
                        echo $ingredient_list;
                        ?>

                    </dl>

                </div>
            </div>


        <?php } ?>
    </section>
<?php } ?>
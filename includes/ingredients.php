<?php
// region Shared
function ingredient_input_field_key($ingredient_post)
{
    return get_field('measure_type', $ingredient_post) === 'Weight (Dry)' ? 'dry' : 'wet';
}

function ingredient_type_terms()
{
    return get_terms(array(
        'taxonomy' => 'ingredient_type',
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC'
    ));
}

function all_ingredients_by_type()
{
    $ingredient_terms = ingredient_type_terms();
    $ingredients = [];

    foreach ($ingredient_terms as $ingredient_term) {

        $query_args = array(
            'post_type' => 'ingredient',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'ingredient_type',
                    'field' => 'term_id',
                    'terms' => $ingredient_term->term_id
                ),
            ),
        );

        $query = new WP_Query($query_args);

        if ($query->have_posts()) :

            while ($query->have_posts()) : $query->the_post();
                $ingredient_post = get_post();
                $macros_field = get_field('macro_nutrients');
                $input_field_key = (get_field('measure_type') === 'Weight (Dry)') ? 'dry' : 'wet';
                $input_field = get_field($input_field_key);
                $cost = $input_field[$input_field_key . '_cost'];
                $quantity = $input_field[$input_field_key . '_quantity'];
                $units = $input_field[$input_field_key . '_unit_type'];
                $weight_in_lbs = convertWeightToPounds($ingredient_post, $quantity, $units);
                $cost_per_lb = round($cost / $weight_in_lbs, 2);
                $macros_per_lb = macros_per_lb($ingredient_post);

                $cost_formatted = new \NumberFormatter(
                    'en_US',
                    \NumberFormatter::CURRENCY
                );

                $ingredients[] = [
                    'ingredient' => get_the_title(get_the_id()),
                    'type' => $ingredient_term->name,
                    'protein%' => intval($macros_field['protein_%']) . '%',
                    'carb%' => intval($macros_field['carb_%']) . '%',
                    'fat%' => intval($macros_field['fat_%']) . '%',
                    'protein_cals' => $macros_per_lb['protein'],
                    'carb_cals' => $macros_per_lb['carb'],
                    'fat_cals' => $macros_per_lb['fat'],
                    'total_cals' => $macros_per_lb['total'],
                    'cost_per_lb' => $cost_formatted->format($cost_per_lb)
                ];

            endwhile;

        endif;

        wp_reset_postdata();
    }

    return $ingredients;
}

function macros_per_lb($ingredient_post)
{
    $macros_percentage = get_field('macro_nutrients', $ingredient_post);
    $base_cals = getIngredientBaseCals($ingredient_post);
    $macro_types = ['protein', 'carb', 'fat'];

    if (ingredient_input_field_key($ingredient_post) === 'dry') {
        $cals_per_pound = $base_cals['lb'];
    } else {
        $pounds_in_one_cup = poundsToCups(1, $ingredient_post);
        $cals_per_pound  = $pounds_in_one_cup * $base_cals['cup'];
    }

    $cals_per_macro = [];

    foreach ($macro_types as $type) {
        $percentage = intval($macros_percentage[$type . '_%']);

        if ($percentage !== 0) {
            $cals_per_macro[$type] = intval(round($cals_per_pound * ($percentage * .01)));
        } else {
            $cals_per_macro[$type] = 0;
        }
    }

    $cals_per_macro['total'] = array_sum($cals_per_macro);

    return $cals_per_macro;
}

function getMeasurementType($ingredient)
{
    $measure_type = trim(get_field('measure_type', $ingredient->ID));

    return ($measure_type === "Weight (Dry)") ? "dry" : "wet";
}

function getIngredientPosts()
{
    return get_posts(
        array(
            'post_type' => 'ingredient',
            'posts_per_page' => -1
        )
    );
}
// endregion

function getUnitPriceDenominator($quantity, $measurement_type)
{
    $measurement_type = trim(strtolower($measurement_type));
    $denominator = null;

    switch ($measurement_type) {
        case "oz":
            $denominator = ($quantity);
            break;
        case "lbs":
            $denominator = ($quantity * 16);
            break;
        case "gallons":
            $denominator = ($quantity * 128);
            break;
    }

    return $denominator ?: null;
}

function getIngredientUnitPrice($ingredient)
{
    $price = get_field('price', $ingredient->ID);
    $quantity = get_field('quantity', $ingredient->ID);
    $measurement_type = get_field('measurement_type', $ingredient->ID);
    $denominator = getUnitPriceDenominator($quantity, $measurement_type);

    if ($denominator) {
        return round(($price / $denominator), 2);
    }
}

// region Dry Ingredients
function getDryUnitCosts($post_id)
{
    $inputs_array['cost'] =  get_field('dry', $post_id);
    $unit_type = $inputs_array['cost']['dry_unit_type'];
    $cost = $inputs_array['cost']['dry_cost'];
    $quantity = $inputs_array['cost']['dry_quantity'];
    $unit_costs = [];

    switch ($unit_type) {
        case "Lbs":
            $unit_costs['Lbs'] = ($cost / $quantity);
            $unit_costs['Oz'] = ($cost / ($quantity * 16));
            $unit_costs['Grams'] = ($cost / ($quantity * 453.592));
            break;
        case "Oz":
            $unit_costs['Lbs'] = ($cost / ($quantity / 16));
            $unit_costs['Oz'] = ($cost / $quantity);
            $unit_costs['Grams'] = ($cost / ($quantity * 28.3495));
            break;
        case "Grams":
            $unit_costs['Lbs'] = ($cost / ($quantity / 453.592));
            $unit_costs['Oz'] = ($cost / ($quantity / 28.3495));
            $unit_costs['Grams'] = ($cost / $quantity);
            break;
    }

    $inputs_array['unit'] = $unit_costs;

    return $inputs_array;
}

function populateDryUnitCosts($post_id, $unit_costs)
{
    $field_key = 'field_64def92250dfa';
    $values = [
        'dry_cost' => get_field('dry_cost', $post_id),
        'dry_quantity' => get_field('dry_quantity', $post_id),
        'dry_unit_type' => get_field('dry_unit_type', $post_id),
        'unit_cost_gram' => round($unit_costs['gram'], 4),
        'unit_cost_oz' => round($unit_costs['oz'], 2),
        'unit_cost_lb' => round($unit_costs['lb'], 2)

    ];

    update_field($field_key, $values, $post_id);
}

// endregion

// region Wet Ingredients
function getWetUnitCosts($post_id)
{
    $inputs_array['cost'] =  get_field('wet', $post_id);
    $unit_type = $inputs_array['cost']['wet_unit_type'];
    $cost = $inputs_array['cost']['wet_cost'];
    $quantity = $inputs_array['cost']['wet_quantity'];
    $unit_costs = [];

    switch ($unit_type) {
        case "Gallons":
            $unit_costs['Gallons'] = ($cost / $quantity);
            $unit_costs['Cups'] = ($cost / ($quantity * 16));
            $unit_costs['Fluid Oz'] = ($cost / ($quantity * 128));
            break;
        case "Cups":
            $unit_costs['Gallons'] = ($cost / ($quantity / 16));
            $unit_costs['Cups'] = ($cost / $quantity);
            $unit_costs['Fluid Oz'] = ($cost / ($quantity * 128));
            break;
        case "Fluid Oz":
            $unit_costs['Gallons'] = ($cost / ($quantity / 128));
            $unit_costs['Cups'] = ($cost / ($quantity / 16));
            $unit_costs['Fluid Oz'] = ($cost / $quantity);
            break;
    }

    $inputs_array['unit'] = $unit_costs;

    return $inputs_array;
}

function populateWetUnitCosts($post_id, $unit_costs)
{
    $field_key = 'field_64def74450df3';
    $values = [
        'wet_cost' => get_field('wet_cost', $post_id),
        'wet_quantity' => get_field('wet_quantity', $post_id),
        'wet_unit_type' => get_field('wet_unit_type', $post_id),
        'unit_cost_fluid_oz' => round($unit_costs['fluid_oz'], 4),
        'unit_cost_cup' => round($unit_costs['cup'], 2),
        'unit_cost_gallon' => round($unit_costs['gallon'], 2)

    ];

    update_field($field_key, $values, $post_id);
}


// endregion

function calcUnitPrices()
{
    $ingredients = getIngredientPosts();
    $ingredient_units = [];

    foreach ($ingredients as $ingredient) {
        $id = $ingredient->ID;
        $title = get_the_title($ingredient->ID);
        $measure_type = trim(get_field('measure_type', $id));


        if ($measure_type === "Weight (Dry)") {
            $ingredient_units[$title] = getDryUnitCosts($id);
        } else {
            $ingredient_units[$title] = getWetUnitCosts($id);
        }
    }

    return $ingredient_units;
}

function calculateIngredientAdjustment()
{
}

function calculateIngredientWeight($ingredient, $adjustment = null)
{
}

function get_recipe_table($recipe, $container = true)
{
    $output = '';

    if ($container) {
        $output .= '<div class="container recipe-container mx-auto bg-white rounded shadow my-8 p-8 " ';
        $output .= 'data-post="' . get_the_ID() . '" data-name="' . $recipe['name'] . '" ';
        $output .= 'data-baseWeight="' . $recipe['totals']['weight'] . '" data-baseCals="' . $recipe['totals']['calories'] . '">';
    }

    $blink_class = (!$container) ? "blink-class" : "";

    $output .= '<div class="recipe-table ' . $blink_class . '">';
    $output .= outputRecipeHeading($recipe);
    $output .= outputUpdateInputs($recipe['totals']);
    $output .= outputRecipeTableHead();
    $output .= outputRecipeTableBody($recipe);
    $output .= outputRecipeTableFoot($recipe['totals'], $recipe['price']);
    $output .= '</div>';

    if ($container) {
        $output .= '</div>';
    }

    return $output;
}

function outputRecipeHeading($recipe)
{

    if (!empty(trim($recipe['name']))) {
        return '<p class="text-center md:text-xl lg:text-xxl font-normal italic leading-6 font-base mt-6 mb-10">' . $recipe['name'] . '</p>';
    }
}

function outputUpdateInputs($totals)
{
    $input  = '<div class="columns-2 mb-8">';

    $input .= '<div class="flex mx-auto max-w-fit mb-8 recipe-update" id="recipe-weight-input">';
    $input .= '<div class="relative rounded-md shadow-sm">';
    $input .= '<input type="number" name="weight" id="weight" class="inline-block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 font-semibold shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="" value="' . round($totals['weight'], 1) . '">';
    $input .= '<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">';
    $input .= '<span class="inline-flex items-center rounded-l-md px-3 text-gray-500 sm:text-sm">Lbs</span></div></div>';
    $input .= '<button type="button" class="update-btn rounded-md bg-indigo-600 px-3.5 py-2 ml-5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" id="weight-recipe-update" data-type="weight" data-base="' . $totals['weight'] . '">Update for Weight</button>';
    $input .= '</div>';

    $input .= '<div class="flex mx-auto max-w-fit mb-8 recipe-update" id="recipe-cals-input">';
    $input .= '<div class="relative rounded-md shadow-sm">';
    $input .= '<input type="number" name="cals" id="cals" class="inline-block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 font-semibold shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="" value="' . round($totals['calories'], 0) . '">';
    $input .= '<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">';
    $input .= '<span class="inline-flex items-center rounded-l-md px-3 text-gray-500 sm:text-sm">Cals</span></div></div>';
    $input .= '<button type="button" class="update-btn rounded-md bg-indigo-600 px-3.5 py-2 ml-5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" id="cals-recipe-update" data-type="cals" data-base="' . $totals['calories'] . '">Update for Cals</button>';
    $input .= '</div>';

    $input .= '</div>';


    return $input;
}

function outputRecipeTableHead()
{
    return '<table class="min-w-full mb-16">
                <thead class="border-b border-gray-300 text-gray-900">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Ingredient</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Amount</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Cost</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Protein</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Carbs</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Fat</th>
                        <th scope="col" class="py-3.5 pl-3 pr-4 text-left text-sm font-semibold text-gray-900 sm:pr-0">Total Cals</th>
                    </tr>
                </thead>';
}

function outputRecipeTableBody($recipe = [])
{

    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    $body = '<tbody>';

    foreach ($recipe['ingredients'] as $ingredient) {

        // Omit empty (no weight) ingredients
        if ($ingredient['weight']['pounds']) {

            // Ingredient inputs
            $p_cals = round($ingredient['macros']['protein']['calories'], 0);
            $p_grams = round($ingredient['macros']['protein']['grams'], 0);
            $c_cals = round($ingredient['macros']['carbs']['calories'], 0);
            $c_grams = round($ingredient['macros']['carbs']['grams'], 0);
            $f_cals = round($ingredient['macros']['fat']['calories'], 0);
            $f_grams = round($ingredient['macros']['fat']['grams'], 0);

            $cals = round($p_cals + $c_cals + $f_cals, 0);

            // Output
            $body .= '<tr class="border-b border-gray-200">';
            $body .= '<td class="max-w-0 py-5 pl-4 pr-3 text-sm sm:pl-0">' . $ingredient['ingredient'] . '</td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">' . remove_trailing_zero($ingredient['weight']['amount']) . ' ';
            $body .= '<span class="lowercase">' . $ingredient['weight']['unit'] . '</span></td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">' . $formatter->formatCurrency($ingredient['unit_cost'], 'USD') . '</td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
            $body .= '<span class="ingredient-cals">' . $p_cals . ' cals </span>';
            $body .= '<span class="text-right">(' . $p_grams . ' grams)</span></td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
            $body .= '<span class="ingredient-cals">' . $c_cals . ' cals </span>';
            $body .= '<span class="text-right">(' . $c_grams . ' grams)</span></td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
            $body .= '<span class="ingredient-cals">' . $f_cals . ' cals </span>';
            $body .= '<span class="text-right">(' . $f_grams . ' grams)</span></td>';
            $body .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
            $body .= '<span class="ingredient-cals">' . $cals . ' cals </span></td>';
            $body .= '</tr>';
        }

        $body .= '</tbody>';
    }

    return $body;
}

function outputRecipeTableFoot($totals = [], $price = [])
{

    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

    $foot  = '<tfoot>';

    // Amounts
    $foot .= '<tr>';
    $foot .= '<th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0"><strong>Amounts:</strong></th>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0 font-bold">' . round($totals['weight'], 1)  . ' lbs</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0 font-bold">$' . round($totals['cost'], 2)  . '</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0 hidden"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . $formatter->formatCurrency($totals['cost'], 'USD')  . '</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($totals['protein']['calories']) . ' cals <span class=" ">(' . number_format(round($totals['protein']['grams'], 0)) . ' grams)</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($totals['carbs']['calories']) . ' cals <span class=" ">(' . number_format(round($totals['carbs']['grams'], 0)) . ' grams)</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($totals['fat']['calories']) . ' cals <span class=" ">(' .  number_format(round($totals['fat']['grams'], 0)) . ' grams)</span></td>';
    $foot .= '</tr>';


    // Macros
    $foot .= '<tr>';
    $foot .= '<th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0"><strong>Macros:</strong></th>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . round($totals['protein']['percent'], 2) . '% Protein</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . round($totals['carbs']['percent'], 2) . '% Carb</span></td>';
    $foot .= '<td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . round($totals['fat']['percent'], 2) . '% Fat</span></td>';
    $foot .= '</tr>';

    // Totals
    $foot .= '<tr class="bg-gray-50 totals-row">';
    $foot .= '<th scope="row" colspan="" class="px-3 py-3 text-left text-sm font-normal text-gray-500 sm:table-cell rounded-tl-lg rounded-bl-lg"><strong>Totals:</strong></th>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-right text-sm text-gray-500 sm:pr-0 italic">Price</td>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . $formatter->formatCurrency($price['price'], 'USD')  . '</span></td>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-left text-sm text-gray-500 sm:pr-0"></td>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-left text-sm text-gray-500 sm:pr-0"></td>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-right text-sm text-gray-500 sm:pr-0 italic">Calories</td>';
    $foot .= '<td class="pl-3 pr-4 py-3 text-left text-sm text-gray-500 font-bold sm:pr-0 rounded-tr-lg rounded-br-lg"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . number_format($totals['calories']) . ' cals</span></td>';
    $foot .= '</tr>';

    $foot .= '</tfoot></table>';

    return $foot;
}



function updateIngredientInputs()
{
    calcUnitPrices();
}

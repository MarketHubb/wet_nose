<?php
function getIngredientCalsPerPound($ingredient)
{
    $weight_units = str_replace("1 ", "", trimAndLowerString(get_field('weight', $ingredient->ID)));
    $kcals = get_field('calories', $ingredient->ID);
    $multiplier = null;

    switch ($weight_units) {
        case "lb":
            $multiplier = 1;
            break;
        case "oz":
            $multiplier = intval(16);
            break;
        case "gram":
            $multiplier = floatval(453.592);
            break;
    }

    return ($multiplier && $kcals) ? ($multiplier * floatval($kcals)) : null;
}

function getCalsPerMeasurementTypes($ingredient)
{
    $measurement_type = getMeasurementType($ingredient);

    if ($measurement_type === "wet") {
        return getWetIngredientCals($ingredient);
    } else {
        return getDryIngredientCals($ingredient);
    }
}

function getGramsAndCalsPerMacro($ingredient, $ingredient_cals, $adjustment = 1)
{
    $macros_inputs = get_field('macro_nutrients', $ingredient->ID);

    $ingredient_macros_array = [
        'protein' => $macros_inputs['protein_%'],
        'carbs' => $macros_inputs['carb_%'],
        'fat' => $macros_inputs['fat_%'],
    ];

    $ingredient_cals_array = [];
    $ingredient_total_cals = 0;

    foreach ($ingredient_macros_array as $macro => $percent_of_cals) {
        $cals_per_gram = ($macro === "fat") ? 9 : 4;
        $cals = ($ingredient_cals * ($percent_of_cals / 100)) * $adjustment;
        $grams = floatval($cals / $cals_per_gram);

        $ingredient_cals_array[$macro] = [
            'calories' => $cals,
            'grams' => $grams
        ];
    }

    return $ingredient_cals_array;
}

function getDryIngredientCals($total_cals, $base_type)
{
    $cals = [];

    switch ($base_type) {
        case "1 Gram":
            $cals = [
                'gram' => $total_cals,
                'oz' => ($total_cals * 28.3495),
                'lb' => ($total_cals * 453.592)
            ];
            break;
        case "1 Oz":
            $cals = [
                'gram' => ($total_cals / 28.3495),
                'oz' => $total_cals,
                'lb' => ($total_cals * 16)
            ];
            break;
        case "1 Lb":
            $cals = [
                'gram' => ($total_cals / 28.3495),
                'oz' => ($total_cals / 453.592),
                'lb' => floatval($total_cals)
            ];
            break;
    }

    return $cals;
}

function getWetIngredientCals($total_cals, $base_type)
{
    $cals = [];

    switch ($base_type) {
        case "1 Fluid Oz":
            $cals = [
                'fluid_oz' => $total_cals,
                'cup' => ($total_cals * 8),
                'gallon' => ($total_cals * 128)
            ];
            break;
        case "1 Cup":
            $cals = [
                'fluid_oz' => ($total_cals / 8),
                'cup' => $total_cals,
                'gallon' => ($total_cals * 16)
            ];
            break;
        case "1 Gallon":
            $cals = [
                'fluid_oz' => ($total_cals / 128),
                'cup' => ($total_cals / 16),
                'gallon' => $total_cals
            ];
            break;
    }

    return $cals;
}

function getIngredientBaseCals($ingredient)
{
    $inputs = get_field('macro_nutrients', $ingredient->ID);
    $total_cals = $inputs['calories'];
    $ingredient_type = getMeasurementType($ingredient);
    $base_measurement_type = $inputs['type_' . $ingredient_type];

    return ($ingredient_type === 'dry') ? getDryIngredientCals($total_cals, $base_measurement_type) : getWetIngredientCals($total_cals, $base_measurement_type);
}

function getIngredientCalsAndMacrosForRecipe($ingredient_post, $quantity, $units, $adjustment = 1)
{
    $ingredient_base_cals = getIngredientBaseCals($ingredient_post);

    $units = standardize_string($units);

    if ($ingredient_base_cals[$units]) {
        $ingredient_cals = $ingredient_base_cals[$units] * $quantity;
    }

    $ingredient_macros = getGramsAndCalsPerMacro($ingredient_post, $ingredient_cals, $adjustment);

    return $ingredient_macros;
}

function getIngredientInputCost($ingredient_post, $type, $quantity, $units, $adjustment)
{
    $ingredient = get_the_title($ingredient_post->ID);
    $ingredient_costs = calcUnitPrices();

    switch (trim($units)) {
        case "Grams":
            $measure = "Grams";
            break;
        case "Oz":
            $measure = "Oz";
            break;
        case "Lbs":
            $measure = "Lbs";
            break;
        case "Fluid Oz":
            $measure = "Fluid Oz";
            break;
        case "Cups":
            $measure = "Cups";
            break;
        case "Gallons":
            $measure = "Gallons";
            break;
    }

    $unit_price = $ingredient_costs[$ingredient]['unit'][$measure] * $quantity;

    return ($unit_price !== 0.0) ? ($unit_price * $adjustment) : false;
}

function gramsToPounds($quantity)
{
    return ($quantity / 453.592) ?: null;
}

function ozToPounds($quantity)
{
    return ($quantity / 16) ?: null;
}

function cupWeightInPounds($ingredient)
{
    return get_field('weight_conversion', $ingredient->ID)['cup_weight'] ?: 0.48;
}

function fluidOzToPounds($quantity, $ingredient = null)
{

    return (cupsToPounds($quantity / 8, $ingredient)) ?: null;
}

function cupsToPounds($quantity, $ingredient = null)
{
    $conversion = ($ingredient) ? get_field('weight_conversion', $ingredient->ID)['cup_weight'] : 0.48;

    return ($quantity * $conversion) ?: null;
}

function poundsToCups($quantity, $ingredient)
{
    $ingredient_cup_weight = cupWeightInPounds($ingredient);

    return ($ingredient_cup_weight) ? round($quantity / $ingredient_cup_weight, 2) : null;
}

function gallonsToPounds($quantity, $ingredient = null)
{

    return (cupsToPounds($quantity * 16, $ingredient)) ?: null;
}

function convertWeightToPounds($ingredient, $quantity, $units)
{

    switch (trim($units)) {
        case "Grams":
            $pounds = gramsToPounds($quantity);
            break;
        case "Oz":
            $pounds = ozToPounds($quantity);
            break;
        case "Lbs":
            $pounds = floatval($quantity);
            break;
        case "Fluid Oz":
            $pounds = fluidOzToPounds($quantity, $ingredient);
            break;
        case "Cups":
            $pounds = cupsToPounds($quantity, $ingredient);
            break;
        case "Gallons":
            $pounds = gallonsToPounds($quantity, $ingredient);
            break;
    }

    return ($pounds) ? round($pounds, 2) : null;
}

function ingredientWeight($ingredient, $quantity, $units, $adjustment)
{
    $weights = [];
    $quantity = $quantity * $adjustment;
    $quantity_formatted = ($quantity < 1) ? number_format($quantity, 2) : round($quantity, 1);
    $weights['amount'] = $quantity_formatted;
    $weights['unit'] = $units;
    $weights['pounds'] = convertWeightToPounds($ingredient, $quantity, $units);

    return $weights ?: null;
}

function calculateRecipePriceLegacy($recipe_totals, $profit_type, $profit_amount)
{
    if ($profit_type && $profit_amount && $recipe_totals['cost']) {

        $total_cost = $recipe_totals['cost'];
        $profit = ($profit_type === "Fixed Amount") ? ($profit_amount) : ($total_cost * ($profit_amount / 100));
        $price = $profit + $total_cost;

        $price = [
            'type' => $profit_type,
            'amount' => $profit_amount,
            'cost' => $total_cost,
            'price' => round($price, 2)
        ];
    }

    return $price ?: null;
}

function calculateRecipePrice($recipe, $cost)
{
    $profit_type = $recipe['recipe_recipes']['profit_type'];
    $profit_amount = floatval($recipe['recipe_recipes']['profit_amount']);
    $profit_percentage = floatval($recipe['recipe_recipes']['profit_percentage']);

    if ($profit_type && $cost) {

        if ($profit_type === "Amount") {
            $price = $cost + $profit_amount;
            $formatted_amount = '$' . $profit_amount;
        } elseif ($profit_type === "Percentage") {
            $price = $cost + ($cost * $profit_percentage / 100);
            $formatted_amount = $profit_percentage . '%';
        } else {
            // If an invalid profit_type is provided, return the original cost
            $price = $cost;
            $formatted_amount = '';
        }

        $result = [
            'type' => $profit_type,
            'amount' => $formatted_amount,
            'cost' => $cost,
            'price' => round($price, 2)
        ];
    }

    return $result;
}

function recipeTotals($ingredients = [], $adjustment = 1)
{
    $totals = [];
    $weight = 0;
    $cost = 0;
    $p_grams = 0;
    $p_cals = 0;
    $c_grams = 0;
    $c_cals = 0;
    $f_grams = 0;
    $f_cals = 0;

    foreach ($ingredients as $ingredient) {
        $weight += $ingredient['weight']['pounds'];
        $cost += $ingredient['unit_cost'];
        $p_grams += $ingredient['macros']['protein']['grams'];
        $p_cals += $ingredient['macros']['protein']['calories'];
        $c_grams += $ingredient['macros']['carbs']['grams'];
        $c_cals += $ingredient['macros']['carbs']['calories'];
        $f_grams += $ingredient['macros']['fat']['grams'];
        $f_cals += $ingredient['macros']['fat']['calories'];
    }

    $totals['weight'] = $weight * $adjustment;
    $totals['cost'] = $cost * $adjustment;
    $totals['price'] = $cost * $adjustment;
    $totals['calories'] = $p_cals + $c_cals + $f_cals * $adjustment;
    $totals['protein'] = [
        'calories' => $p_cals * $adjustment,
        'grams' => $p_grams * $adjustment,
        'percent' => round(($p_cals / $totals['calories']) * 100, 0)
    ];
    $totals['carbs'] = [
        'calories' => $c_cals * $adjustment,
        'grams' => $c_grams * $adjustment,
        'percent' => round(($c_cals / $totals['calories']) * 100, 0)
    ];
    $totals['fat'] = [
        'calories' => $f_cals * $adjustment,
        'grams' => $f_grams * $adjustment,
        'percent' => round(($f_cals / $totals['calories']) * 100, 0)
    ];


    return $totals ?: null;
}

function getCalsForRecipeIngredients($ingredients, $adjustment, $name = null)
{

    $ingredient_list = [];

    foreach ($ingredients as $ingredient) {
        $type = standardize_string($ingredient['type']);
        $ingredient_post = get_post($ingredient['ingredient_' . $type]);
        $units = ($type === 'dry') ? $ingredient['weight'] : $ingredient['volume'];
        $quantity = ($ingredient['amount_quantity']) ?: 0;
        $weight = ingredientWeight($ingredient_post, $quantity, $units, $adjustment);
        $recipe_cals = ($ingredient_post) ? getIngredientCalsAndMacrosForRecipe($ingredient_post, $quantity, $units, $adjustment) : null;
        $ingredient_cost = getIngredientInputCost($ingredient_post, $type, $quantity, $units, $adjustment);

        $ingredient_list[] = [
            'ingredient' => get_the_title($ingredient_post->ID),
            'post_id' => $ingredient_post->ID,
            'weight' => $weight,
            'unit_cost' => $ingredient_cost,
            'type' => $type,
            'macros' => $recipe_cals
        ];
    }

    return $ingredient_list;
}

function get_recipe_adjustment($adjustment)
{
    return ($adjustment) ?: 1;
}

function getIngredientsforRecipes($post_id, $adjustment = null, $name = null)
{
    $adjustment = get_recipe_adjustment($adjustment);
    $recipe_post_title = get_the_title($post_id);
    $recipes = get_field('recipes', $post_id);
    $ingredients = [];

    foreach ($recipes as $recipe) {
        $profit_type = $recipe['recipe_recipes']['profit_type'];
        $profit_amount = floatval($recipe['recipe_recipes']['profit_amount']);
        $recipe_name =  $recipe['recipe_recipes']['name'];
        $ingredients_list = getCalsForRecipeIngredients($recipe['recipe_recipes']['ingredients'], $adjustment, $name);
        $recipe_totals = recipeTotals($ingredients_list);
        $recipe_price = calculateRecipePrice($recipe, $recipe_totals['cost']);

        $ingredients[] = [
            "id" => $post_id,
            "recipe" => $recipe_post_title,
            "name" =>  $recipe_name,
            "profit_type" => $profit_type,
            "profit_amount" => $profit_amount,
            "totals" => $recipe_totals,
            "price" => $recipe_price,
            "ingredients" => $ingredients_list
        ];
    }

    return $ingredients;
}

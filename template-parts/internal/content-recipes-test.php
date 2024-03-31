<!--<div class="bg-white py-10">-->
<div class="mx-auto max-w-7xl">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">

                    <?php
                    // Format total (unit) costs
                    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                    $recipes = getIngredientsforRecipes(get_the_ID());

                    highlight_string("<?php\n\$recipes =\n" . var_export($recipes, true) . ";\n?>");

                    $output = '<div class="container mx-auto bg-white rounded shadow my-8 p-8">';

                    //$output .= get_template_part('template-parts/shared/content', 'input');

                    foreach ($recipes as $recipe) {
                        $output .= '<p class="text-lg md:text-xl font-bold leading-6 font-base my-6">' . get_the_title($recipe['recipe']) . ' (' . $recipe['name'] . ')</p>';
                        $output .= '
                            <table class="min-w-full mb-16">
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
                                </thead>
                                <tbody>';

                        $total_cost = 0;
                        $total_protein_cals = 0;
                        $total_carb_cals = 0;
                        $total_fat_cals = 0;
                        $total_protein_grams = 0;
                        $total_carb_grams = 0;
                        $total_fat_grams = 0;
                        $total_ingredient_cals = 0;

                        foreach ($recipe['ingredients'] as $ingredient) {

                            // Omit empty (no weight) ingredients
                            if ($ingredient['unit_cost']) {

                                $total_cost += $ingredient['unit_cost'];
                                $protein_cals = $ingredient['macros']['protein']['calories'];
                                $protein_grams = $ingredient['macros']['protein']['grams'];
                                $total_protein_cals += $protein_cals;
                                $total_protein_grams += $protein_grams;
                                $carb_cals = $ingredient['macros']['carbs']['calories'];
                                $carb_grams = $ingredient['macros']['carbs']['grams'];
                                $total_carb_cals += $carb_cals;
                                $total_carb_grams += $carb_grams;
                                $fat_cals = $ingredient['macros']['fat']['calories'];
                                $fat_grams = $ingredient['macros']['fat']['grams'];
                                $total_fat_cals += $fat_cals;
                                $total_fat_grams += $fat_grams;
                                $ingredient_cals = $protein_cals + $carb_cals + $fat_cals;
                                $total_ingredient_cals += $ingredient_cals;
                                $protein_percent = round($total_protein_cals/$total_ingredient_cals,2) * 100;
                                $carb_percent = round($total_carb_cals/$total_ingredient_cals,2) * 100;
                                $fat_percent = round($total_fat_cals/$total_ingredient_cals,2) * 100;
                                $total_macros = $protein_percent + $carb_percent + $fat_percent;

                                $output .= '<tr class="border-b border-gray-200">';
                                // Ingredient Name
                                $output .= '<td class="max-w-0 py-5 pl-4 pr-3 text-sm sm:pl-0">' . $ingredient['ingredient'] . '</td>';
                                // Amount
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">' . $ingredient['amount'] . '</td>';
                                // Unit Cost
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">' . $formatter->formatCurrency($ingredient['unit_cost'], 'USD') . '</td>';
                                // Protein
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
                                $output .= '<span class="ingredient-cals">' . $protein_cals . ' cals </span>';
                                $output .= '<span class="text-right">(' . $protein_grams . ' grams)</span></td>';
                                // Carbs
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
                                $output .= '<span class="ingredient-cals">' . $carb_cals . ' cals </span>';
                                $output .= '<span class="text-right">(' . $carb_grams . ' grams)</span></td>';
                                // Fat
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
                                $output .= '<span class="ingredient-cals">' . $fat_cals . ' cals </span>';
                                $output .= '<span class="text-right">(' . $fat_grams . ' grams)</span></td>';

                                // Total (Ingredient)
                                $output .= '<td class=" px-3 py-5 text-sm text-gray-500 sm:table-cell text-left">';
                                $output .= '<span class="ingredient-cals">' . $ingredient_cals . ' cals </span></td>';

                                // Total (Macro)
                                $output .= '</tr>';

                            }

                        }

                        $output .= '</tbody>';
                        $output .= '<tfoot>

                            <tr>
                                <th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0"><strong>Totals</strong></th>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0 hidden"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . $formatter->formatCurrency($total_cost, 'USD')  .'</span></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($total_protein_cals) . ' cals <span class=" ">(' . $total_protein_grams . ' grams)</span></td>
                              <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($total_carb_cals) . ' cals <span class=" ">(' . $total_carb_grams . ' grams)</span></td>
                              <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0">' . number_format($total_fat_cals) . ' cals <span class=" ">(' .  $total_fat_grams . ' grams)</span></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . number_format($total_ingredient_cals) . ' cals</span></td>
                            </tr>
                            <tr>
                              <th scope="row" colspan="" class="hidden pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0"></th>
                              <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                              <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                              <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . $protein_percent . '% Protein</span></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . $carb_percent . '% Carb</span></td>
                                <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 font-bold sm:pr-0"><span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">' . $fat_percent . '% Fat</span></td>
                              
                            </tr>';

                        // Cost
                        $output .=
                            '<tr>
                                  <th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Costs</th>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0">' . $formatter->formatCurrency($total_cost, 'USD')  . '</span></td>
                                </tr>';

                        // Make sure we have a defined profit before outputting cost
                        $profit_amount = $recipe['profit_amount'];
                        $profit_type = $recipe['profit_type'];
                        $profit = ($profit_type === "Fixed Amount") ? ($total_cost + $profit_amount) : ($total_cost * ($profit_amount/100));
                        $price = $profit + $total_cost;
                        $profit_calc = ($profit_type === "Fixed Amount") ? "$ {$profit_amount}" : "{$profit_amount}%";

                        if ($recipe['profit_amount']) {

                            // Profit
                            $output .=
                                '<tr>
                                  <th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Profit</th>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0">' . $formatter->formatCurrency($profit, 'USD')  . ' (' . $profit_calc . ')</td>
                                </tr>';

                            // Price
                            $output .=
                                '<tr>
                                  <th scope="row" colspan="" class="pl-4 pr-3 pt-6 text-left text-sm text-gray-500 sm:table-cell sm:pl-0 font-bold">Price</th>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0"></td>
                                  <td class="pl-3 pr-4 pt-6 text-left text-sm text-gray-500 sm:pr-0 font-bold"><span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-bold text-red-700 ring-1 ring-inset ring-red-600/10">' . $formatter->formatCurrency($price, 'USD')  . '</span></td>
                                </tr>';

                        }

                        $output .= '</tfoot></table>';

                    }

                    echo $output;
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>







<?php get_header(); ?>

<div class="bg-main py-10">
    <div class="container ingredient-container mx-auto bg-white rounded shadow my-8 p-8 ">

        <h1 class="text-2xl mb-6 py-4 text-center font-bold">Ingredients Table</h1>

        <?php
        $table_head_groups = [
            'Description' => [
                'col_span' => 2,
                'th_classes' => "bg-indigo-300 border-b border-indigo-400 uppercase"
            ],
            'Macro percentage (%)' => [
                'col_span' => 3,
                'th_classes' => "bg-amber-300 border-b border-amber-400 uppercase"
            ],
            'Calories (per lb)' => [
                'col_span' => 3,
                'th_classes' => "bg-cyan-300 border-b border-cyan-400 uppercase"
            ],
            'Totals (per lb)' => [
                'col_span' => 2,
                'th_classes' => "bg-red-400 border-b border-red-500 uppercase"
            ],
        ];

        $table_head_columns = [
            'Ingredient' => [
                'col_span' => 1,
                'th_classes' => 'bg-indigo-200 '
            ],

            'Type' => [
                'col_span' => 1,
                'th_classes' => 'bg-indigo-200 '
            ],

            'Protein %' => [
                'col_span' => 1,
                'th_classes' => 'bg-amber-200 '
            ],

            'Carb %' => [
                'col_span' => 1,
                'th_classes' => 'bg-amber-200 '
            ],

            'Fat %' => [
                'col_span' => 1,
                'th_classes' => 'bg-amber-200 '
            ],

            'Protein' => [
                'col_span' => 1,
                'th_classes' => 'bg-cyan-200 '
            ],

            'Carb' => [
                'col_span' => 1,
                'th_classes' => 'bg-cyan-200 '
            ],

            'Fat' => [
                'col_span' => 1,
                'th_classes' => 'bg-cyan-200 '
            ],

            'Cals' => [
                'col_span' => 1,
                'th_classes' => 'bg-red-300 '
            ],

            'Cost' => [
                'col_span' => 1,
                'th_classes' => 'bg-red-300 '
            ],
        ];

        $output  = output_table_open();

        $output .= output_table_head($table_head_groups);

        $output .= output_table_head($table_head_columns);

        $output .= '<tbody>';

        $ingredients = all_ingredients_by_type();

        foreach ($ingredients as $ingredient) {
            $output .= output_table_row($ingredient);
        }

        $output .= '</tbody>';

        echo $output;

        ?>

    </div>
</div>

<?php //get_footer(); ?>
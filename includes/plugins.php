<?php
// ACF
//$measurement_type = getMeasurementType($ingredient);
// field_64e29cd6e5ef3 (dry)
// field_64e29f4e818bb (wet)
function acf_load_ingredients_dry( $field ) {
    $field['choices'] = array();

    $ingredients = getIngredientPosts();

    foreach ($ingredients as $ingredient) {
        $measurement_type = getMeasurementType($ingredient);

        if ($measurement_type === "dry") {
            $value = $ingredient->ID;
            $label = get_the_title($ingredient->ID);

            $field['choices'][ $value ] = $label;
        }

    }

    return $field;
}

add_filter('acf/load_field/key=field_64e29cd6e5ef3', 'acf_load_ingredients_dry');

function acf_load_ingredients_wet( $field ) {
    $field['choices'] = array();

    $ingredients = getIngredientPosts();

    foreach ($ingredients as $ingredient) {
        $measurement_type = getMeasurementType($ingredient);

        if ($measurement_type === "wet") {
            $value = $ingredient->ID;
            $label = get_the_title($ingredient->ID);

            $field['choices'][ $value ] = $label;
        }

    }

    return $field;
}

add_filter('acf/load_field/key=field_64e29f4e818bb', 'acf_load_ingredients_wet');

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Menus & Alerts',
        'menu_title'    => 'Menus & Alerts',
        'menu_slug'     => 'menu-alerts',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

}
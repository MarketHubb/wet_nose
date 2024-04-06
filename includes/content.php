<?php
// Form - Doggo Profile (Ingredients)
function recipe_macros_for_doggo_profile($post_id)
{
    $recipe_inputs = getIngredientsforRecipes($post_id, 1);
    $output = '<dl class="grid grid-cols-2 gap-y-3 content-center items-center md:grid-cols-2 text-base mt-3">';
    $output .= '<dt class="mt-0"><span class="stylized text-gray-500 inline-block w-full">Protein:</span></dt>';
    $output .= '<dd class="mt-0 pl-0  border-transparent">';
    $output .= '<span class="stylized font-bold ps-0 mt-0">';
    $output .= $recipe_inputs[0]['totals']['protein']['percent'] . '%';
    $output .= '</span></dd>';
    $output .= '<dt class="mt-0"><span class="stylized text-gray-500 inline-block w-full">Fat:</span></dt>';
    $output .= '<dd class="mt-0 pl-0 "><span class="stylized font-bold ps-0 mt-0">';
    $output .= $recipe_inputs[0]['totals']['fat']['percent'] . '%';
    $output .= '</span></dd>';
    $output .= '<dt class="mt-0"><span class="stylized text-gray-500 inline-block w-full">Carbs:</span></dt>';
    $output .= '<dd class="mt-0 pl-0 "><span class="stylized font-bold ps-0 mt-0">';
    $output .= $recipe_inputs[0]['totals']['carbs']['percent'] . '%';
    $output .= '</span></dd></dl>';

    return $output;
}
function recipe_ingredients_for_doggo_profile($post_id)
{
    $recipe_inputs = getIngredientsforRecipes($post_id, 1);
    $ingredient_count = count($recipe_inputs[0]['ingredients']);

    $output  = '<p class="text-base mt-4 mb-2">';
    $output .= '<span class="emphasis whitespace-nowrap font-bold mt-2">' . $ingredient_count . ' simple ingredients</span>';
    $output .= '</p>';
    $output .= '<dl class="grid grid-cols-1 text-base leading-7 text-gray-600">';

    foreach ($recipe_inputs[0]['ingredients'] as $ingredient) {
        $output .= '<div class="relative pl-0 py-0">';
        $output .= '<dt class="font-semibold text-gray-900">';
        $output .= '<img class="inline-block rounded-full max-h-8 mr-3 my-0" src="' . get_field('ingredient_image_square', $ingredient['post_id'])['url'] . '" />';
        $output .= '<span class="text-base inline-block">' . $ingredient['ingredient'] . '</span>';
        $output .= '</dt><dd class="">';
        $output .= '<span></span>';
        $output .= '</dd></div>';
    }
    $output .= '</dl>';

    return $output;
}

// Form - Doggo Profile (Recipe Details)
function recipe_details_for_doggo_profile($post_id, $iteration)
{
    $recipe = get_post($post_id);
    $recipe_details = ['Ingredients', 'Macros'];
    $i = 1;

    $output  = '<div class="container mx-auto recipe-details py-3 px-1" data-id="' . $post_id . '">';
    $output .= '<div class="text-center mb-6 relative">';

    // $output .= '<h3 class="text-lg mb-5 block stylized whitespace-nowrap font-bold">';
    // $output .= get_the_title($post_id) . '</h3>';
    $output .= '<img class="recipe-image aspect-[12/10] object-bottom w-full object-cover rounded-md shadow" src="' . get_field('recipe_image', $post_id)['url'] . '" />';
    $output .= '<img src="' . get_home_url() . '/wp-content/uploads/2023/12/check.svg" class="selected-recipe-icon absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-14 filter-yellow h-full w-full max-w-100 z-10"/>';
    $output .= '<div class="absolute inset-0 bg-black bg-opacity-50 rounded-md recipe-image-bg"></div>';

    $output .= '</div>';

    foreach ($recipe_details as $recipe_detail) {
        $output .= '<section class="profile-recipe-accordion" aria-labelledby="details-heading" class="mt-12">';
        $output .= '<h2 id="details-heading" class="sr-only">Recipe details</h2>';
        $output .= '<div class="divide-y divide-gray-200 px-4">';
        // Button
        $output .= '<div><h3>';
        $output .= '<button type="button" class="group relative flex w-full items-center justify-between py-2 text-left" aria-controls="disclosure-' . $iteration . '" aria-expanded="false">';
        $output .= '<span class=" text-sm stylized font-bold accordion-btn">' . $recipe_detail . '</span><span class="ml-6 flex items-center">';
        $output .= '<svg class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg><svg class="hidden h-6 w-6 text-indigo-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                        </svg>';
        $output .= '</span></button></h3>';
        // Content
        $output .= '<div class="prose prose-sm pb-6 hidden" id="disclosure-' . $iteration . '">';

        if ($recipe_detail === 'Description') $output .= '<p>' . get_field('recipe_description', $post_id) . '</p>';
        if ($recipe_detail === 'Ingredients') $output .= recipe_ingredients_for_doggo_profile($post_id, $i);
        if ($recipe_detail === 'Macros') $output .= recipe_macros_for_doggo_profile($post_id, $i);

        $output .= '</div>';
        $output .= '</div></section>';

        $i++;
    }

    $output .= '</div>';

    return $output;
}

// region Recipe Calculator
function output_table_open()
{
    return '<table class="min-w-full mb-16">';
}
function output_table_head($args = [])
{
    $output = '<thead class=" text-gray-900">';
    $output .= '<tr>';

    foreach ($args as $key => $val) {
        $base_th_classes = 'py-3.5 pl-4 pr-3 font-semibold sm:pl-0 ';

        if (is_array($val)) {
            $col_span = ($val['col_span']) ?: '1';
            $th_classes = ($val['th_classes']) ?: '';
            $text_classes = ($val['text_classes']) ?: '';
            $text = $key;
        } else {
            $col_span = 1;
            $th_classes = 'text-gray-900 text-left';
            $text_classes = '';
            $text = $val;
        }

        $th_classes = $base_th_classes . $th_classes;

        $output .= '<th colspan="' . $col_span . '" scope="col" ';
        $output .= ' class="' . $th_classes . '">';
        $output .= '<span class="' . $text_classes . '">' . $text . '</span>';
        $output .= '</th>';
    }

    $output .= '</tr>
                </thead>';

    return $output;
}

function output_table_row($args = [])
{
    $output = '<tr class="border-b border-gray-200">';

    foreach ($args as $arg) {
        $output .= '<td class="max-w-0 py-5 pl-4 pr-3 text-sm sm:pl-0 text-center">';
        $output .= $arg;
        $output .= '</td>';
    }

    $output .= '</tr>';

    return $output;
}



// endregion

// region Section content
function section_start_classes($spacer_classes = true, $additional_classes = null)
{

    $base_classes = "mx-auto container py-24 lg:py-36 xl:py-52 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:pr-24 ";

    return ($additional_classes) ? $base_classes . $additional_classes : $base_classes;
}
function section_open()
{
    return '<div class="mx-auto container py-12 lg:py-24">';
}
function section_pre_heading($string)
{
    return "<h2 class=\"text-xl stylized text-primary font-semibold leading-7 text-indigo-600\">${string}</h2>";
}
function section_heading($string, $custom_classes = null)
{
    $string = emphasis_text_in_copy($string);
    $base_classes = ' mt-2 text-2xl lg:text-5xl font-heading font-bold tracking-tight text-gray-900 sm:text-4xl md:text-5xl';
    $class_names = ($custom_classes) ? $custom_classes . $base_classes : $base_classes;
    return "<h3 class=\"${class_names}\">${string}</h3>";
}
function section_description($string)
{
    $string = emphasis_text_in_copy($string);
    return "<p class=\"mt-2 text-xl\">${string}</p>";
}
function get_hero_alert($hero)
{
    $alert_copy = get_desktop_mobile_copy($hero['alert_link']['copy']);

    $output = '<div class="sm:mt-32 sm:flex lg:mt-16">';
    $output .= '<div class="inline-flex flex-auto md:flex-initial rounded-full px-4 py-1 text-sm leading-6 bg-primary text-white ring-1 ring-gray-900/10 hover:ring-gray-900/20">';

    if ($hero['alert_link']['link']) {
        $output .= '<a href="' . $hero['alert_link']['link'] . '" class="whitespace-nowrap">';
    }

    $output .= '<span class="font-semibold text-white md:hidden ">' . $alert_copy['mobile'] . '</span>';
    $output .= '<span class="font-semibold text-white hidden md:inline-block">' . $alert_copy['desktop'] . '</span>';

    if ($hero['alert_link']['link']) {
        $output .= '</a>';
    }

    $output .= '</div>
                    </div>';

    return $output ?: null;
}


function get_hero_description($hero)
{
    return '<p class="mt-3 md:mt-6 text-base lg:text-lg xl:text-xl leading-6 xl:leading-7 text-gray-700">' . $hero['hero_description'] . '</p>';
}

// region Global (String)
function standardize_string($str)
{
    if (substr($str, -1) == 's') {
        $str = substr($str, 0, -1);
    }

    return trimAndLowerString(str_replace(" ", "_", $str));
}

function trimAndLowerString($string)
{
    return trim(strtolower($string));
}
// endregion

//region Links
function getLinks($link_array)
{
    if (is_array($link_array) && !empty($link_array)) {
        $link_text_array = explode(",", $link_array['link_text']);
        $link_text = $link_text_array[0];
        $mobile_link_text = ($link_text_array[1]) ?: $link_text;
        $classes = ($link_array['link_style'] === 'Primary') ? 'hidden md:block rounded-md bg-primary-accent px-3.5 py-2.5 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'hidden md:block font-semibold leading-6 text-gray-900';
        $classes_mobile = ($link_array['link_style'] === 'Primary') ? 'block md:hidden rounded-md bg-primary-accent px-3.5 py-2.5 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'block md:hidden font-semibold leading-6 text-gray-900';
        $target = ($link_array['link_type'] === 'Page') ? $link_array['link_page'] : "#";
        $arrow = ($link_array['link_style'] === 'Secondary') ? '<span aria-hidden="true">→</span>' : '';

        if ($classes && $target) {
            $link = '<a href="' . $target . '" class="' . $classes . '">';
            $link .= $link_text . $arrow . '</a>';
            $link .= '<a href="' . $target . '" class="' . $classes_mobile . '">';
            $link .= $mobile_link_text . $arrow . '</a>';
        }

        return $link ?: null;
    }
}
                    //endregion
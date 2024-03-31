<?php
// region Recipe Calculator
function output_table_open()
{
    return '<table class="min-w-full mb-16">';
}
function output_table_head($args = [])
{
    $output  = '<thead class=" text-gray-900">';
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
        $output .= 'class="' . $th_classes . '">';
        $output .= '<span class="' . $text_classes . '">' . $text . '</span>';
        $output .= '</th>';

    }

    $output .= '</tr></thead>';

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



//  endregion

// region Section content
function section_start_classes($spacer_classes = true, $additional_classes = null)
{
    
    $base_classes = "mx-auto container py-24 lg:py-36 xl:py-52 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:pr-24 ";
    
    return ($additional_classes) ? $base_classes . $additional_classes : $base_classes;
}
function section_open()
{
    return '<div class="mx-auto container py-24">';
}
function section_pre_heading($string)
{
    return "<h2 class=\"text-xl stylized text-primary font-semibold leading-7 text-indigo-600\">${string}</h2>";
}
function section_heading($string, $custom_classes = null)
{
    $string = emphasis_text_in_copy($string);
    $base_classes = ' mt-2 text-5xl font-heading font-bold tracking-tight text-gray-900 sm:text-4xl md:text-5xl';
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

    $output  = '<div class="sm:mt-32 sm:flex lg:mt-16">';
    $output .= '<div class="inline-flex flex-auto md:flex-initial rounded-full px-4 py-1 text-sm leading-6 bg-primary text-white ring-1 ring-gray-900/10 hover:ring-gray-900/20">';

    if ($hero['alert_link']['link']) {
        $output .= '<a href="' . $hero['alert_link']['link'] . '" class="whitespace-nowrap">';
    }

    $output .= '<span class="font-semibold text-white md:hidden ">' . $alert_copy['mobile'] . '</span>';
    $output .= '<span class="font-semibold text-white hidden md:inline-block">' . $alert_copy['desktop'] . '</span>';

    if ($hero['alert_link']['link']) {
        $output .= '</a>';
    }

    $output .= '</div></div>';

    return $output ?: null;
}


function get_hero_description($hero)
{
    return '<p class="mt-3 md:mt-6 text-2xl text-gray-700">' . $hero['hero_description'] . '</p>';
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
            $link  = '<a href="' . $target . '" class="' . $classes . '">';
            $link .= $link_text . $arrow . '</a>';
            $link .= '<a href="' . $target . '" class="' . $classes_mobile . '">';
            $link .= $mobile_link_text . $arrow . '</a>';
        }

        return $link ?: null;
    }
}
//endregion

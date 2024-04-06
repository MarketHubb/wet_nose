<?php

function primary_button_classes($additional_classes = null)
{
    $base_classes = "hero-btn stylized w-full text-center md:w-auto rounded-md bg-redSoft shadow-md px-3.5 pt-2.5 pb-2 text-base md:text-lg font-semibold text-white hover:shadow-none hover:bg-redSoftLight z-10";
    $button_classes = (isset($additional_classes) && !empty($additional_classes)) ? $base_classes . $additional_classes : $base_classes;

    return $button_classes;
}

function emphasis_text_in_copy($string, $classes = null) {
    preg_match_all('/{(.*?)}/', $string, $matches);
    $base_classes = 'emphasis anti whitespace-nowrap"';
    $emphasis_classes = ($classes) ? $classes . ' ' . $base_classes : $base_classes;

    if (!empty($matches[0])) {
        $replace = '<span class="' . $emphasis_classes . '">' . $matches[1][0] . '</span>';
        $string  = str_replace($matches[0], $replace, $string);
    }

    return $string ?: null;
}

function get_desktop_mobile_copy($string, $delimiter = ",") {
    $copy = [];
    $string_array = explode($delimiter, $string);

    if (!empty($string_array)) {
        $copy['desktop'] = trim($string_array[0]);
        $copy['mobile'] = trim($string_array[1]) ?: trim($string_array[0]);
    }

    return $copy ?: null;
}

function remove_trailing_zero($number) {
    return rtrim((strpos($number,".") !== false ? rtrim($number, "0") : $number),".");
}

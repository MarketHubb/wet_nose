<?php

function emphasis_text_in_copy($string, $classes = null) {
    preg_match_all('/{(.*?)}/', $string, $matches);
    $base_classes = 'emphasis whitespace-nowrap"';
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

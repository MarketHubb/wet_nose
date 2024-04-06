<?php
// add_filter( 'gpld_limit_dates_options_FORMID_FIELDID', 'limit_dates_one_week_saturdays_wednesdays', 10, 3 );
// function limit_dates_one_week_saturdays_wednesdays( $options, $form, $field ) {
//     $today = date('m/d/Y');
//     $one_week_from_today = date('m/d/Y', strtotime($today . ' +1 week'));
    
//     $options['minDate'] = '{today}';
//     $options['minDateMod'] = '+1 week';
//     $options['daysOfWeek'] = array( 3, 6 );
    
//     // Add exceptions for all dates between today and one week from today
//     $start_date = new DateTime($today);
//     $end_date = new DateTime($one_week_from_today);
//     $period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
    
//     foreach ($period as $date) {
//         $options['exceptions'][] = $date->format('m/d/Y');
//     }
    
//     // One-off allowed dates
//     $allowed_dates = array('06/15/2023', '07/04/2023');
//     $options['exceptions'] = array_merge($options['exceptions'], $allowed_dates);
    
//     // One-off disallowed dates
//     $disallowed_dates = array('06/24/2023', '07/01/2023');
//     $options['exceptions'] = array_merge($options['exceptions'], $disallowed_dates);
//     $options['exceptionMode'] = 'disable';
    
//     return $options;
// }

add_filter( 'gpld_limit_dates_options_7_41', 'limit_dates_one_week_saturdays_wednesdays', 10, 3 );
function limit_dates_one_week_saturdays_wednesdays( $options, $form, $field ) {
    $today = date('m/d/Y');
    $set_pickup_days_field = get_field('available_pickup_days', 'option');

    foreach ($set_pickup_days_field as $key => $val) {
        $options['daysOfWeek'][] = intval($val); 
    }

    $minimum_days = get_field('days_before_pickup', 'option') . ' days';
    $one_week_from_today = date('m/d/Y', strtotime($today . ' +' . $minimum_days));
    
    $options['minDate'] = '{today}'; // Set the minimum date to today
    $options['minDateMod'] = $minimum_days; // Adjust the minimum date to one week from today
    // $options['daysOfWeek'] = array( 3, 6 ); // Only allow Wednesdays (3) and Saturdays (6)
    
    // Add exceptions for all dates between today and one week from today
    $start_date = new DateTime($today);
    $end_date = new DateTime($one_week_from_today);
    $period = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
    
    foreach ($period as $date) {
        $options['exceptions'][] = $date->format('m/d/Y');
    }
    
    return $options;
}

add_filter('gform_next_button', 'custom_next_button', 10, 2);
function custom_next_button($button, $form)
{
    $form_pages_options = get_field('form_pages', 'option');

    if ($form['id'] == 7) {
        if (str_contains($button, "gform_next_button_7_7")) {
            $button = str_replace('Next',$form_pages_options[0]['button'], $button);
        }
        if (str_contains($button, "gform_next_button_7_10")) {
            $button = str_replace('Next',$form_pages_options[1]['button'], $button);
        }
        if (str_contains($button, "gform_next_button_7_11")) {
            $button = str_replace('Next',$form_pages_options[2]['button'], $button);
        }
    }

    return $button;
}

add_filter('gform_field_content', function ($content, $field, $value, $lead_id, $form_id) {
    $base_input_classes = "gfield-choice-input ";
    $color_input_classes = "bg-white ";
    $input_state_classes = "focus:outline-inherit focus:outline-offset-0 focus:border-input focus:border ";


    if ($field->type == 'text' || $field->type == 'select') {
        return str_replace("class='", "class='focus:outline-inherit focus:outline-offset-0 focus:border-input focus:border ", $content);
    }

    if ($form_id == 7 && $field->type == 'address') {
        return str_replace('<input', '<input class="' . $color_input_classes . $input_state_classes . '" ', $content);
    }

    if ($form_id == 7) {
    }

    $base_label_classes = "inline-flex absolute w-full max-w-full h-full z-10 font-bold justify-center items-center ";
    $checked_classes = "checked:bg-none checked:border-input checked:border-2 hover:checked:border-2 checked:bg-inputFill checked:text-transparent ";
    $focus_classes = "focus:outline-input focus:outline-offset-0 focus:ring-0 focus:ring-transparent focus:ring-offset-transparent focus:ring-offset-0 focus:shadow-none focus:text-inputFill ";
    $hover_active_classes = "hover:checked:border-2 hover:checked:border-2 hover:bg-inherit ";
    $disabled_classes = "disabled:bg-gray-200 ";

    if ($form_id == 10 || $form_id == 7 && $field->type == 'radio' || $field->type == 'checkbox') {
        return str_replace('gfield-choice-input', $base_input_classes . $focus_classes . $hover_active_classes . $checked_classes . $disabled_classes, $content);
    }

    return $content;
}, 10, 5);

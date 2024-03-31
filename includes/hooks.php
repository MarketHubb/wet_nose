<?php

// add_filter('gform_progress_steps', 'hide_progress_steps', 10, 3);

// function hide_progress_steps($progress_steps, $form, $page) {
//     return $progress_steps;

//    $icons = get_field('icons', 'option');
//    $person = $icons[0]['icon'];
//    $dog = $icons[1]['icon'];
//    $plans = $icons[2]['icon'];
//    $checkout = $icons[3]['icon'];
//    $image_start = '<img class="gf_step_icon" src="';
//    $image_end = '" />';

//    if (!$form['id'] === 7) {
//        return $progress_steps;
//    }

//    if ($page === 1) {
//        $default = '1';
//        $replace = $image_start . $person . $image_end;
//        $progress_steps = str_replace($default, $replace, $progress_steps);
//    }
//    if ($page === 2) {
//        $default = '1';
//        $replace = $image_start . $dog . $image_end;
//        $progress_steps = str_replace($default, $replace, $progress_steps);
//    }

//     return $progress_steps;
// }
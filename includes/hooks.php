<?php
add_filter('gform_field_content', function ($content, $field, $value, $lead_id, $form_id) {
    GFCommon::log_debug(__METHOD__ . '(): modified field content ' . $content);
    if ($field->type == 'text' || $field->type == 'select') {
        return str_replace("class='", "class='focus:outline-inherit focus:outline-offset-0 focus:border-input focus:border ", $content);
    }

    $base_input_classes = "gfield-choice-input bg-white ";
    $base_label_classes = "inline-flex absolute w-full max-w-full h-full z-10 font-bold justify-center items-center ";
    $checked_classes = "checked:bg-none checked:border-input checked:border-2 hover:checked:border-2 checked:bg-inputFill checked:text-transparent";
    $focus_classes = "focus:outline-input focus:outline-offset-0 focus:ring-0 focus:ring-transparent focus:ring-offset-transparent focus:ring-offset-0 focus:shadow-none focus:text-inputFill";
    $hover_active_classes = "hover:checked:border-2 hover:checked:border-2";

    if ($form_id == 10 && $field->type == 'radio' || $field->type == 'checkbox' ) {
        return str_replace("input class='", "input class='gfield-choice-input bg-white focus:outline-input focus:outline-offset-0 focus:ring-0 focus:ring-transparent focus:ring-offset-transparent focus:ring-offset-0 focus:shadow-none focus:text-inputFill checked:bg-none checked:border-input checked:border-2 hover:checked:border-2 checked:bg-inputFill checked:text-transparent focus:border active:bg-inputFill  ", $content);
    }

    return $content;
}, 10, 5);


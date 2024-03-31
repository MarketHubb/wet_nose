<?php

function testFnc() {
    $test = "string";

    return $test;
}

function hero_alert($hero) {
    $alert_copy = get_desktop_mobile_copy($hero['alert_link']['copy']);

    $output  = '<div class="sm:mb-14 sm:flex">';
    $output .= '<div class="inline-flex flex-auto md:flex-initial rounded-full px-4 py-1 text-sm leading-6 bg-primary text-white ring-1 ring-gray-900/10 hover:ring-gray-900/20">';

    if ($hero['alert_link']['link']) {
        $output .= '<a href="' . $hero['alert_link']['link'] . '" class="whitespace-nowrap">';
    }

    $output .= '<span class="font-semibold text-white md:hidden ">' . $alert_copy['mobile'] . '</span>';
    $output .= '<span class="font-semibold tracking-wide text-white hidden md:inline-block">' . $alert_copy['desktop'] . '</span>';

    if ($hero['alert_link']['link']) {
        $output .= '</a>';
    }

    $output .= '</div></div>';

    return $output ?: null;
}

function hero_heading($hero) {
    $heading = emphasis_text_in_copy($hero['hero_heading']);
    return '<h1 class="mt-8 text-4xl md:text-6xl font-bold tracking-tight leading-relaxed text-gray-900 sm:mt-6 sm:text-7xl">' . $heading . '</h1>';
}

function hero_callouts($args, $layout = "vertical") {
    $callouts = $args['callouts'];
    $display = ($layout = "vertical") ? 'block' : 'flex';

    if ($callouts) {

        $callout = '<ul class="' . $display . ' items-center w-full text-sm font-medium text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white my-10">';

        $i = 1;
        foreach ($callouts as $key => $val) {
            $pl_class = ($i !== 1) ? "pl-3" : "";
            $ml_class = ($i !== 1) ? "ml-2" : "";
            $callout .= '<li class="w-full py-3">';
            $callout .= '<img src="' . get_home_url() . '/wp-content/uploads/2023/12/check.svg' . '" class="max-w-[20px] filter-yellow inline mx-auto" />';
            $callout .= '<p class="pl-4 py-1 inline text-xl w-full font-semibold">';
            $callout .= $val['callout'] . '</p>';

            $i++;
        }
        $callout .= '</ul>';

    }

    return $callout ?: null;
}

function hero_links($args, $format = "desktop") {
    $links_array = $args['hero_links_links'];
    $links_count = count($args['hero_links_links']);
    $container_class = ($format === "mobile") ? 'justify-content-center flex-column' : '';

    $output = '<div class="hero-links flex md:flex-row justify-start items-center mt-16 ' . $container_class . ' " data-count="' . $links_count . '">';

    foreach ($links_array as $link) {

        switch ($link['style']) {
            case "Button":
                $classes = 'class="hero-btn stylized rounded-md bg-secondary px-3.5 pt-2.5 pb-2 md:text-lg font-semibold text-white shadow-sm hover:bg-secondary-light" ';
                break;
            case "Text":
                $classes = 'class="hero-btn stylized rounded-md bg-transparent px-3.5 pt-2.5 pb-2 md:text-lg font-semibold text-secondary shadow-sm ring-2 ring-inset ring-secondary hover:ring-secondary-light hover:text-secondary-light" ';
                break;
        }

        switch ($link['type']) {
            case "Page":
                $el_type = 'a';
                $attributes = 'href="' . $link['link'] . '" ';
                break;
            case "Phone":
                $el_type = 'a';
                $attributes = 'href="tel:+' . $link['phone'] . '" ';
                break;
            case "Anchor":
                $el_type = 'a';
                $attributes = 'href="#' . $link['anchor'] . '" ';
                break;
            case "Modal":
            case "Form":
                $el_type = 'button';
                $attributes = 'data-action="form-display" data-target="' . $link['destination'] . '" ';
                break;
        }

        if ($el_type && $attributes) {
            $link_container_class = ($format === "mobile") ? 'mx-auto text-center' : '';
            $link_copy = $link['copy'];

            if ($link['type'] === 'Phone') {
                $link_copy = '<span class="d-block lh-1 phone-callout fw-normal small">' . $link_copy . '</span>' . $link['destination'];
            }

            $output .= '<div class="inline-flex ' . $link_container_class . '">';
            $output .= '<' . $el_type . ' ';
            $output .= $attributes . $classes . ' data-type="' . $link['type'] . '">';
            $output .= $link_copy;

            if ($link['style'] === 'Button') {
                $output .= '<img class="max-h-[12px] pl-2 filter-white inline" src="' . get_home_url() . '/wp-content/uploads/2023/12/arrow-right.svg' . '" />';
            }

            $output .= '</' . $el_type . '>';
            $output .= '</div>';
        }
    }

    $output .= '</div>';

    return ($output) ?: null;
}

function hero_overlap_image($args) {
    $output  = '<div class="container mx-auto relative -top-44 -mb-[15rem]">';
    $output .= '<div class="grid justify-center mx-auto">';
    $output .= '<img class="shadow-lg-up shadow-dark  rounded-full relative w-60" src="' . $args['overlap_image'] . '" alt="">';
    $output .= '</div></div>';

    return $output ?: null;
}

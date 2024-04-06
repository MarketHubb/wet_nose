<?php
if( have_rows('form_pages', 'option') ):
    $output = '<div class="container hidden" id="form-icons">';
    while ( have_rows('form_pages', 'option') ) : the_row();
        $output .= '<div data-type="' . get_sub_field('section', 'option') . '" ';
        $output .= '<div data-lead="' . get_sub_field('lead', 'option') . '" ';
        $output .= '<div data-heading="' . get_sub_field('heading', 'option') . '" ';
        $output .= 'data-icon="' . get_sub_field('icon', 'option') . '"></div>';
    endwhile;
    $output .= '</div>';
    echo $output;
endif;
?>
<?php
if( have_rows('form_weight_icons', 'option') ):
    $output = '<div class="container hidden" id="weight-icons">';
    while ( have_rows('form_weight_icons', 'option') ) : the_row();
        $output .= '<div data-type="' . get_sub_field('label', 'option') . '" ';
        $output .= 'data-icon="' . get_sub_field('icon', 'option') . '"></div>';
    endwhile;
    $output .= '</div>';
    echo $output;
endif;
?>

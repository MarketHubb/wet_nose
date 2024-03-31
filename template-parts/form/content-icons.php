<?php
if( have_rows('icons', 'option') ):
    $output = '<div class="container hidden" id="form-icons">';
    while ( have_rows('icons', 'option') ) : the_row();
        $output .= '<div data-type="' . get_sub_field('section', 'option') . '" ';
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

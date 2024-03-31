<?php get_header(); ?>

<?php
//$groups = acf_get_field_groups(array('post_type' => 'ingredient'));
//$groups = acf_get_field_groups('group_64dd45a77a11f');
//$groups = acf_get_fields('group_64dd45a77a11f');
/*highlight_string("<?php\n\$groups =\n" . var_export($groups, true) . ";\n?>");*/
?>

<?php
$test1_args = array(
    'testimonial' => '"My dogs absolutely love it"',
    'callout' => 'Yours will too. Get 20% off your first month when you fill out your custom doggo profile',
    'button_text' => 'Build Your Doggo Profile'
);
get_template_part('template-parts/testimonial/content', 'stars', $test1_args);
?>

<?php get_template_part('template-parts/home/content', 'how'); ?>

<?php //get_template_part('template-parts/home/content' , 'steps') 
?>

<?php
$sections = get_field('sections');

if (is_array($sections)) {
    foreach ($sections as $section) {
        $type = $section['section_type'];

        switch ($type) {
            case "Hero (Masonry)":
                $content = $section['section_hero_masonry'];
                $template_part = "masonry";
                break;
            case "Card List":
                $content = $section['card_list'];
                $template_part = "card-list";
                break;
        }

        get_template_part('template-parts/hero/content', $template_part, $content);
    }
}

if (have_rows('sections')) :
    $section = '';
    while (have_rows('sections')) : the_row();
    endwhile;
    echo $section;
endif;
?>

<?php get_template_part('template-parts/hero/content', 'masonry'); ?>

<?php
$test2_args = array(
    'testimonial' => '"My senior dog acts like a puppy again"',
    'callout' => 'Who says older dogs have to slow down as they age? A Healthy, homemade diet can help you dog live it\'s best life',
    'button_text' => 'Try Homemade Dog Food'
);
get_template_part('template-parts/testimonial/content', 'stars', $test2_args);
?>



<?php get_template_part('template-parts/global/content', 'card-image-left'); ?>

<?php //get_template_part('template-parts/section/content' , 'split') 
?>

<?php //get_template_part('template-parts/calendar/content' , 'split') 
?>



<?php get_footer(); ?>
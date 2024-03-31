<?php
/* Template Name: Builder */
get_header(); ?>

<?php
//get_template_part('template-parts/section/content', 'hero-background');

$sections = get_field('sections');

if (is_array($sections)) {
    foreach ($sections as $section) {
        $type = $section['section_type'];

        switch ($type) {
            case "Hero (Masonry)":
                $content = $section['section_hero_masonry'];
                $template = "masonry";
                break;
            case "Hero (Split Image)":
                $content = $section['section_hero_split'];
                $template = "hero-split";
                break;
            case "Card List":
                $content = $section['card_list'];
                $template = "card-list";
                break;
        }

        get_template_part('template-parts/section/content', $template, $content);
    }
}
?>



    <?php get_footer(); ?>

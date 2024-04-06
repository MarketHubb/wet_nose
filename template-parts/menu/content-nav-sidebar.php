<?php $object = get_queried_object(); ?>
<div class="mx-auto container flex flex-row justify-between">
    <div>
        <a href="<?php echo home_url(); ?>">
            <img class="max-h-14 py-2.5 w-auto" src="<?php echo get_field('logo', 'option'); ?>" />
        </a>
    </div>
    <div class="text-center flex items-center" id="nav-bar-slideout">
        <button id="openMenuIcon" class="bg-transparent text-gray-600 hover:text-gray-950 font-medium text-4xl md:text-5xl normal-case" type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
            <span class="leading-none text-primary">&#9776;</span>
        </button>
    </div>

    <!-- drawer component -->
    <div id="drawer-navigation" class="shadow-xl fixed bg-gray-100 lg:pt-16 lg:px-14 top-0 left-0 z-40 w-3/5 md:w-80 lg:w-96 h-screen p-4 overflow-y-auto transition-transform -translate-x-full" tabindex="-1" aria-labelledby="drawer-navigation-label">
        <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400"></h5>
        <div class="overflow-y-auto">
            <button id="closeMenuIcon" type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="float-right text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="w-6 h-6 filter-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>


            <?php
            if (have_rows('primary_menu', 'option')) :
                $menu_items = '<ul class="space-y-4 font-medium text-large md:text-regular mt-10 pl-4">';
                while (have_rows('primary_menu', 'option')) : the_row();
                    $menu_obj = get_sub_field('menu_page', 'option');
                    $base_classes = (!get_sub_field('menu_callout', 'option')) ? "inline-flex stylized font-semibold items-center border-b-2 border-transparent py-1.5" : "inline-flex btn stylized items-center rounded-md bg-secondary hover:bg-secondary-light px-4 md:px-3 pt-2 pb-1 mt-1 font-semibold text-white shadow-sm hover:text-white";
                    $active_class = ($menu_obj->ID === get_queried_object_id()) ? "" : "";
                    $color_class = ($menu_obj->ID === get_queried_object_id()) ? "text-gray-800 hover:text-gray-700" : "text-gray-500 hover:text-gray-700";
                    $menu_items .= '<li><a href="' . get_permalink($menu_obj) . '" class="' . $base_classes . ' ' . $color_class . ' ' . $active_class . '">' . get_the_title($menu_obj);

                    if (get_sub_field('menu_callout', 'option')) {
                        $menu_items .= '<img class="max-h-[12px] pl-2 filter-white inline" src="' . get_home_url() . '/wp-content/uploads/2023/12/arrow-right.svg' . '" />';
                    }

                    $menu_items .= '</a></li>';

                endwhile;
                $menu_items .= '</ul>';
                echo $menu_items;
            endif;
            ?>

        </div>

        <?php if (get_field('include_menu_testimonial', 'option')) { ?>

            <div class="container mx-auto px-2 lg:px-8 pb-8 bg-white shadow-lg rounded-lg relative top-40">
                <?php $menu_testimonial = get_field('menu_testimonial', 'option'); ?>

                <?php if ($menu_testimonial['image']) { ?>
                    <div class="mx-auto">
                        <img src="<?php echo $menu_testimonial['image'] ?>" class="rounded-full w-2/3 mx-auto relative -top-16 -mb-10 shadow-md alt="">
                    </div>
                <?php } ?>
                
                <?php if ($menu_testimonial['testimonial']) { ?>
                    <p class=" text-base md:text-[1.25rem] text-center italic font-semibold antialiased">"<?php echo $menu_testimonial['testimonial'];  ?>"</p>
                    <?php } ?>

                    </p>
                    </div>

                <?php } ?>
            </div>
    </div>
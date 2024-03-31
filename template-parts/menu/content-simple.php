<nav class="bg-white shadow">
    <div class="mx-auto container sm:px-6 md:px-0">
        <div class="flex h-16 justify-start">

            <div class="flex">

                <!-- Brand (Full) -->
                <div class="flex flex-shrink-0 items-center">
                    <a href="<?php echo home_url(); ?>" class="font-extrabold text-lg uppercase font-heading tracking-wide">Wet Nose Dog Food</a>
                </div>

                <!-- Nav (Full) -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <?php
                    if (have_rows('primary_menu', 'option')) :
                        $menu_items = '';
                        while (have_rows('primary_menu', 'option')) : the_row();
                            $menu_obj = get_sub_field('menu_page', 'option');
                            $base_classes = (!get_sub_field('menu_callout', 'option')) ? "inline-flex stylized font-semibold items-center border-b-2 border-transparent px-1 pt-1" : "inline-flex btn stylized items-center rounded-md bg-secondary px-3 pt-2 pb-1 font-semibold text-white shadow-sm hover:text-white";
                            $active_class = ($menu_obj->ID === get_queried_object_id()) ? "emphasis" : "";
                            $color_class = ($menu_obj->ID === get_queried_object_id()) ? "text-gray-800 hover:text-gray-700" : "text-gray-500 hover:text-gray-700";
                            $menu_items .= '<a href="' . get_permalink($menu_obj) . '" class="' . $base_classes . ' ' . $color_class . ' ' . $active_class . '">' . get_the_title($menu_obj) . '</a>';
                        endwhile;
                        echo $menu_items;
                    endif;
                    ?>
                </div>

            </div>
            

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="sm:hidden" id="mobile-menu">
        <div class="space-y-1 pb-3 pt-2">
            <!-- Current: "bg-indigo-50 border-indigo-500 text-indigo-700", Default: "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700" -->
            <a href="#" class="block border-l-4 border-indigo-500 bg-indigo-50 py-2 pl-3 pr-4 text-base font-medium text-indigo-700">Dashboard</a>
            <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Team</a>
            <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Projects</a>
            <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Calendar</a>
        </div>
        <div class="border-t border-gray-200 pb-3 pt-4">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">Tom Cook</div>
                    <div class="text-sm font-medium text-gray-500">tom@example.com</div>
                </div>
                <button type="button" class="relative ml-auto flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 space-y-1">
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Your
                    Profile</a>
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Settings</a>
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Sign
                    out</a>
            </div>
        </div>
    </div>
</nav>
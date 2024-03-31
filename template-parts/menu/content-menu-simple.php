<?php $object = get_queried_object(); ?>

<nav class="bg-white shadow">
	<div class="mx-auto container px-2 sm:px-6
lg:px-0">
		<div class="relative flex h-16 justify-between">

			<!-- Desktop nav container -->
			<div class="flex flex-1 items-center sm:items-stretch sm:justify-start md:justify-between">

				<!-- Brand -->
				<div class="flex flex-shrink-0 items-center">
					<a href="<?php echo home_url(); ?>" class="font-extrabold text-lg uppercase font-heading tracking-wide">Wet Nose Dog
						Food</a>
				</div>

				<!-- Nav -->
				<div class="hidden sm:ml-6 sm:flex items-center sm:space-x-8 nav-large">

					<?php 
						if( have_rows('primary_menu', 'option') ):
								$menu_items = '';
								while ( have_rows('primary_menu', 'option') ) : the_row();
									$menu_obj = get_sub_field('menu_page', 'option');
									$base_classes = (!get_sub_field('menu_callout', 'option')) ? "inline-flex stylized font-semibold items-center border-b-2 border-transparent px-1 pt-1" : "inline-flex btn stylized items-center rounded-md bg-secondary px-3 pt-2 pb-1 font-semibold text-white shadow-sm hover:text-white";
									$active_class = ($menu_obj->ID === get_queried_object_id()) ? "emphasis" : "";
									$color_class = ($menu_obj->ID === get_queried_object_id()) ? "text-gray-800 hover:text-gray-700" : "text-gray-500 hover:text-gray-700";
									$menu_items .= '<a href="'.get_permalink($menu_obj).'" class="' . $base_classes . ' ' . $color_class . ' ' . $active_class.'">'.get_the_title($menu_obj).'</a>';
								endwhile;
								echo $menu_items;
						endif; 
					 ?>
					 
				</div>

			</div>


			<!--
						Dropdown menu, show/hide based on menu state.

						Entering: "transition ease-out duration-200"
							From: "transform opacity-0 scale-95"
							To: "transform opacity-100 scale-100"
						Leaving: "transition ease-in duration-75"
							From: "transform opacity-100 scale-100"
							To: "transform opacity-0 scale-95"
					-->
			<!--    <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
						<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
						<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
						<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
					</div> -->
		</div>
	</div>
	</div>
	</div>

	<!-- Mobile menu, show/hide based on menu state. -->
	<div class="sm:hidden" id="mobile-menu">
		<div class="space-y-1 pb-4 pt-2">
			<!-- Current: "bg-indigo-50 border-indigo-500 text-indigo-700", Default: "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700" -->
			<a href="#" class="block border-l-4 border-indigo-500 bg-indigo-50 py-2 pl-3 pr-4 text-base font-medium text-indigo-700">Home</a>
			<a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Recipes</a>
			<a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">About
				Us</a>
			<a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Cost</a>
			<a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Why
				Homemade</a>
		</div>
	</div>
</nav>
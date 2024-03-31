<?php if (isset($args)) { ?>

    <div class="scroller bg-transparent" data-direction="right" data-speed="slow">
        <div class="scroller__inner">

            <?php
            foreach ($args[0]['ingredients'] as $ingredient) {
                $images = '';

                if (get_field('ingredient_image_square', $ingredient['post_id'])) {
                    $images .= '<img class="max-w-[200px] max-h-[200px] rounded-full" src="' . get_field('ingredient_image_square', $ingredient['post_id'])['url'] . '" />';
                }

                echo $images;
            }

            ?>

        </div>
    </div>

<?php  } ?>
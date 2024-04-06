<?php
if ($args) { ?>
<!-- py-12 lg:py-36 xl:py-52  -->
    <div id="hero-main" class="relative bg-main bg-cover bg-no-repeat hero" style="background-image: url(<?php echo $bg_image; ?>);">
        <div class="mx-auto container h-full py-16 lg:py-20 content-center lg:grid lg:grid-cols-12 lg:gap-x-8 lg:pr-24">
            <div class="pr-6 lg:col-span-7 xl:col-span-6 md:py-12">
                <div class="mx-auto max-w-2xl lg:mx-0">

                    <!-- Callout Link -->
                    <?php
                    if ($args['include_alert']) { ?>
                        <?php echo hero_alert($args); ?>
                    <?php } ?>


                    <!-- Heading -->
                    <?php if ($args['hero_heading']) { ?>
                        <?php echo hero_heading($args); ?>
                    <?php } ?>

                    <!-- Description -->
                    <?php if ($args['hero_description']) { ?>
                        <?php echo get_hero_description($args); ?>
                    <?php } ?>

                    <!-- Callouts -->
                    <?php if ($args['callouts']) { ?>
                        <?php echo hero_callouts($args); ?>
                    <?php } ?>

                    <!-- Links -->
                    <?php if ($args['hero_links_links']) { ?>
                        <?php echo hero_links($args); ?>
                    <?php } ?>

                </div>
            </div>
        </div>

        <!-- Primary Image -->
        <div class="relative lg:col-span-5 lg:-mr-8 lg:absolute lg:inset-0 lg:left-1/2 lg:mr-0">
            <img class="aspect-[3/2] rounded-xl md:rounded-none shadow-xl md:shadow-none mt-12 md:mt-0 w-full bg-gray-50 object-cover  object-bottom md:absolute md:inset-0 md:aspect-auto md:h-full" src="<?php echo $args['background_image']; ?>" alt="">
        </div>

    </div>
    </div>

    <!-- Overlap Image -->
    <?php if ($args['overlap_image']) { ?>
        <?php echo hero_overlap_image($args); ?>
    <?php } ?>

<?php } ?>
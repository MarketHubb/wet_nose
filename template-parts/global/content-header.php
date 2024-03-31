<?php if ($args) { ?>
<div id="header-main" class="header-alert sticky top-0 bg-primary z-20 shadow-lg">
    <div class="container flex justify-center items-center mx-auto text-center py-3">

        <?php if ($args['icon']) { ?>
            <img  class="max-h-[20px] filter-yellow px-2 header-icons pr-6 filter-tan inline rotate-[343deg]" src="<?php echo $args['icon']; ?>" alt="">
        <?php } ?>

        <?php if ($args['text']) { ?>
            
            <p class="text-lg text-white inline">

            <?php if ($args['link']) { ?>
                <a href="<?php echo $args['link']; ?>">
            <?php } ?>

            <?php $heading_copy = get_desktop_mobile_copy(emphasis_text_in_copy($args['text'], 'font-semibold mx-1 relative top-[1px]')); ?> 

                <span class="hidden md:inline"><?php echo $heading_copy['desktop']; ?></span>
                <span class="inline md:hidden text-sm"><?php echo $heading_copy['mobile']; ?></span>
                <img class="max-h-[12px] pl-1 filter-white inline" src="<?php echo get_home_url() . '/wp-content/uploads/2023/12/arrow-right.svg'; ?>" />

            <?php if ($args['link']) { ?>
                </a>
            <?php } ?>

            </p>

        <?php } ?>
                        
    </div>
</div>

<?php } ?>
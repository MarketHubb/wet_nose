   <?php if (isset($args)) { ?>

      <div class="bg-tan px-6 py-24 sm:py-32 lg:px-8">

         <div class="mx-auto max-w-6xl text-center" id="hero-simple">

            <div class="relative inline-block">
               <p class="text-xl lg:text-xxl font-bold stylized leading-7 text-primary inline-flex justify-center">
                  <?php echo $args['callout']; ?>
               </p>
            </div>

            <h2 class="mt-2 font-semibold lg:font-normal text-xl lg:text-3xl xl:text-4xl 2xl:text-5xl text-gray-900 sm:text-6xl">
               <?php echo $args['heading']; ?>
            </h2>

            <p class="mt-6 text-base md:text-xl leading-6 lg:leading-8 text-gray-600">
               <?php echo $args['description']; ?>
            </p>

            <?php if ($args['include_button']) { ?>
               <div class="mt-16">
                  <?php $button_group = $args['button']; ?>
                  <a href="<?php echo $button_group['link']; ?>" class="<?php echo primary_button_classes(); ?>">
                     <?php echo $button_group['text']; ?><img class="max-h-[12px] pl-2 filter-white inline" src="<?php echo get_home_url() . '/wp-content/uploads/2023/12/arrow-right.svg'; ?>">
                  </a>
               </div>
            <?php } ?>

         </div>
      </div>

   <?php } ?>
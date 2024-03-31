<?php if ($args) { ?>

    <div class="relative bg-main grid md:block">

        <!-- Copy Container -->
        <div class="mx-auto container lg:grid lg:grid-cols-12 lg:gap-x-8 lg:pr-24 order-last md:order-first">
            <div class="px-6 pb-24 pt-6 md:pt-10 sm:pb-32 lg:col-span-7 lg:px-0 lg:pb-56 lg:pt-24 xl:col-span-6">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <!-- Callout Alert -->
                    <?php if ($args['include_callout']) { ?>
                        <div class="hidden md:block sm:mt-32 sm:flex lg:mt-16">
                            <div class="relative text-center md:text-left font-semibold rounded-full px-4 py-1 text-sm leading-6 bg-primary text-white ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                                <?php echo $args['callout']['copy']; ?><span class="inline md:hidden pl-1.5">&rarr;</span>
                                <a href="<?php echo $args['callout']['link']; ?>" class="hidden md:inline whitespace-nowrap font-semibold text-white">.
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <?php echo $args['callout']['emphasis']; ?>  <span aria-hidden="true">&rarr;
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Icons (Horizontal) -->
                    <?php
                    $callouts = $args['icons'];

                    if ($callouts) {
                        $callout = '<div class="grid grid-cols-3 relative right-2.5 md:right-6 lg:right-14 mt-2 md:mt-24  mb-2 md:mb-10">';

                        $i = 1;
                        foreach ($callouts as $key => $val) {
                            $icon_text = explode(",",$val['text']);
                            $mobile_text = ($icon_text[1]) ?: $icon_text[0];
                            $pl_class = ($i !== 1) ? "pl-3" : "";
                            $ml_class = ($i !== 1) ? "ml-2" : "";
                            $callout .= '<div class="px-4 md:px-0">';
                            $callout .= '<img src="' . $val['icon']['url'] . '" class="block h-1/3 md:h-full hero-callout-icons mx-auto mb-2 md:mb-3" />';
                            $callout .= '<div class="flex items-center">';
                            $callout .= '<p class="hidden md:inline-block w-full text-center text-sm font-bold text-secondary leading-tight md:leading-normal">' . $icon_text[0] . '</p>';
                            $callout .= '<p class="inline-block md:hidden w-full text-center text-sm font-bold text-secondary leading-tight md:leading-normal">' . trim($mobile_text) . '</p>';
                            $callout .= '</div></div>';

                            $i++;
                        }
//                        $callout .= '</ul>';
                        $callout .= '</div>';
                        echo $callout;
                    }
                    ?>

                    <!-- Heading -->
                    <?php if ($args['content']['heading']) { ?>
                        <h1 class="mt-0 md:mt-10 text-3xl md:text-5xl font-bold md:font-normal tracking-tight text-gray-900  sm:text-7xl">
                            <?php echo $args['content']['heading']; ?>
                        </h1>
                    <?php } ?>

                    <!-- Description -->
                    <?php if ($args['content']['description']) { ?>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            <?php echo $args['content']['description']; ?>
                        </p>
                    <?php } ?>

                    <!-- Links -->
                    <?php if (!empty($args['links']['links'])) { ?>
                        <div class="mt-10 flex items-center gap-x-6">
                            <?php foreach ($args['links']['links'] as $link) { ?>
                                <?php $linkEl = getLinks($link); ?>
                                <?php echo ($linkEl) ?: null; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>

        <!-- Primary Image -->
        <div class="relative lg:col-span-5 lg:-mr-8 xl:absolute xl:inset-0 xl:left-1/2 xl:mr-0 order-first md:order-last">
            <img class="aspect-[3/2] w-full bg-gray-50 object-cover lg:absolute lg:inset-0 lg:aspect-auto lg:h-full" src="<?php echo $args['content']['image']['url']; ?>" alt="">
        </div>

    </div>

<?php } ?>

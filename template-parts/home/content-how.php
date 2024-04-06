<div class="overflow-hidden bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <div class="lg:ml-auto lg:pl-4 lg:pt-4">
                <div class="lg:max-w-lg">
                    <?php echo section_pre_heading("For the Love of Pups"); ?>
                    <?php echo section_heading("Live Long & {Pawsper}"); ?>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        We believe all furry family members should have access to clean, nutritionally balanced, affordable meals. Made with all the best ingredients, so our pups can live a long loving life.
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        We know all dogs are unique and entitled to their preference. That's why Wet Nose is here to get to know each and every pup. We've created the best tail wagging recipes for every age, size, and breed! Covering the tracks left behind and preparing for the adventures that lie ahead. We're here to accommodate and fur-ever adapt to your pups lifestyle.
                    <em class="block pt-6 text-sm">Each recipe can be stored for up to 6 months in the freezer. Thawed recipes can be stored for up to 7 days in the fridge.</em>
                    </p>
                </div>
            </div>
            <div class="flex items-start justify-end lg:order-first">
                <div>
                    <img src="<?php echo home_url() . '/wp-content/uploads/2023/12/Pawsibilities.webp'; ?>" alt="Cooking with your pup" class="w-[48rem] -rotate-2 max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem]" width="2432" height="1442">
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$test_args = array(
    'testimonial' => '"My picky eater devours this food"',
    'callout' => 'Even the pickiest of eaters love our homemade recipes. Sign up today to get 20% off your first month'
);
get_template_part('template-parts/testimonial/content' , 'stars', $test_args); 
?>


<div class="overflow-hidden bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <div class="lg:pr-8 lg:pt-4">
                <div class="lg:max-w-lg">
                    <?php echo section_pre_heading("How it works"); ?>
                    <?php echo section_heading("Nutritious {dog food} made easy"); ?>
                    <!-- <p class="mt-6 text-lg leading-8 text-gray-600">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.</p> -->

                    <div class="mt-6">

                        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-3 divide-y divide-gray-100 lg:max-w-none lg:grid-cols-1 mt-2">
                            <div class="px-4 py-6 sm:grid sm:grid-row-2 sm:gap-4 sm:px-0">
                                <dt class="font-bold leading-6 stylized text-xl text-gray-900">Getting started</dt>
                                <dd class="mt-1  leading-6 text-gray-700 sm:col-span-2 sm:mt-0">We cook, portion, vacuum seal, label and pack your meals fresh! You’ll choose the “pick up date” and time. We’ll have ready, one month of fresh dog food.</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-row-2 sm:gap-4 sm:px-0">
                                <dt class="font-bold leading-6 stylized text-xl text-gray-900">Week 1</dt>
                                <dd class="mt-1  leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Your WEEK 1 bag will be thawed and ready to serve your dog when you pick it up from us</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-row-2 sm:gap-4 sm:px-0">
                                <dt class="font-bold leading-6 stylized text-xl text-gray-900">Weeks 2-4</dt>
                                <dd class="mt-1  leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Weeks 2-4 bags will be frozen for your convenience to be be stored in your freezer. Keep it frozen and they stay ready-to-serve for up to 6 months</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-row-2 sm:gap-4 sm:px-0">
                                <dt class="font-bold leading-6 stylized text-xl text-gray-900">Need help?</dt>
                                <dd class="mt-1  leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Each bag is labeled specific to your pup, with instructions on how much to feed, frequency of meals, and is dated with the <em>time to thaw</em> for your convenience.</dd>
                            </div>
                        </dl>

                    </div>

                </div>
            </div>
            <img src="<?php echo home_url() . '/wp-content/uploads/2023/12/Dog-Eating.webp'; ?>" alt="Dog Eating" class="w-[48rem] rotate-2 max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0" width="2432" height="1442">
        </div>
    </div>
</div>
<?php
/* Template Name: Ingredients */
get_header(); ?>

<div class="container mx-auto">
    <div class="overflow-x-auto flex">
        <?php 
        $ingredients = get_posts(array(
            'post_type' => 'ingredient',
            'posts_per_page' => -1,
        ));
        
        $ingredient_count = count($ingredients);

        $list = '';

        foreach ($ingredients as $ingredient) {
            $list .= '<div class="flex-none py-6 px-3 first:pl-6 last:pr-6">';
            $list .= '<div class="flex flex-col items-center justify-center gap-3">';
            $list .= '<img class="w-14 rounded-full" src=""';
        }
        ?>

    <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25"><div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,#fff,rgba(255,255,255,0.6))] dark:bg-grid-slate-700/25 dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]" style="background-position: 10px 10px;"></div><div class="relative rounded-xl overflow-auto"><div class="max-w-md mx-auto bg-white shadow-xl min-w-0 dark:bg-slate-800 dark:highlight-white/5">
                <div class="overflow-x-auto flex">
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-14 rounded-full" src="https://images.unsplash.com/photo-1501196354995-cbb51c65aaea?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">Andrew</strong>
                        </div>
                    </div>
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-18 h-18 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">Emily</strong>
                        </div>
                    </div>
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-18 h-18 rounded-full" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">Whitney</strong>
                        </div>
                    </div>
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-18 h-18 rounded-full" src="https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">David</strong>
                        </div>
                    </div>
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-18 h-18 rounded-full" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">Kristin</strong>
                        </div>
                    </div>
                    <div class="flex-none py-6 px-3 first:pl-6 last:pr-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <img class="w-18 h-18 rounded-full" src="https://images.unsplash.com/photo-1605405748313-a416a1b84491?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80">
                            <strong class="text-slate-900 text-xs font-medium dark:text-slate-200">Sarah</strong>
                        </div>
                    </div>
                </div>
            </div></div><div class="absolute inset-0 pointer-events-none border border-black/5 rounded-xl dark:border-white/5"></div></div>

</div>


<?php get_footer(); ?>

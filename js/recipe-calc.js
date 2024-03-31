jQuery(function ($) {

    function wp_ajax_nopriv_get_recipe_inputs(post_id, name, updateType, baseVal, newVal){
        $('.recipe-container').html();

        $.ajax({
            type:"POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "get_recipe_inputs",
                post_id: post_id,
                name: name,
                updateType: updateType,
                baseVal: baseVal,
                newVal: newVal
            },
            dataType: "json",

            success:function(data){
                console.log(data);
                $('.recipe-container').html(data).fadeIn('slow');
            },

            complete:function(data){
            }
        });
    }

    $(window).load(function() {

        const baseWeight = $('.recipe-container').data('baseweight');
        const baseCals = $('.recipe-container').data('basecals');

        $('body').on('click', '.update-btn', function () {
            let post_id = $('#recipes').data('postid');
            let name = $(this).closest('.recipe-container').data('name');
            let updateType = $(this).data('type');
            let baseVal = (updateType === "weight") ? baseWeight : baseCals;
            let newVal = $(this).closest('.recipe-update').find('input[type="number"]').val();

            wp_ajax_nopriv_get_recipe_inputs(post_id, name, updateType, baseVal, newVal);
        });

    }); // END $(window).load(function()


}); // jQuery End

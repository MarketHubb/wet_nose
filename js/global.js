(function ($) {

    // ## AJAX ##

    // Show zip code warning
    function form_create_temp_user(form_data, first_name, last_name, email) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "form_create_temp_user",
                form_data: form_data,
                first_name: first_name,
                last_name: last_name,
                email: email,
            },
            dataType: "json",
            success: function (user) {
                let user_id = user.data.ID

                if (user_id) {
                    $('form#gform_7 input#input_7_28').val(user_id);
                }
            },
            complete: function () {
                let user_id_val = $('form#gform_7 input#input_7_28').val();
            }
        });
    }

    // Verify zip code
    function ajaxVerifyZip(zip) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "form_verify_zip",
                zip: zip,
            },
            dataType: "json",

            success: function (data) {
                if (!data[1]) {
                    zipWarning.removeClass('hidden');                    
                } else {
                    zipWarning.addClass('hidden');
                }
            },
            complete: function () {

            }
        });
    }

    // Calculate subscription price
    function form_subscription_price(zip) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "form_subscription_price",
                zip: zip,
            },
            dataType: "json",

            success: function (data) {
                console.table("data", data);
            },
            complete: function () {

            }
        });
    }

    // ## FUNCTIONS ##

    function openCloseSidebar() {
        sidebarNav.toggleClass("-translate-x-full");
    }

    function launchForm() {
        pageContainer.toggle();
        formContainer.toggle();
    }

    // Get icons from page
    function getFormHeading() {
        let formHeading = [];

        $('#form-sections div').each(function () {
            formHeading.push($(this));
        })

        return formHeading
    }  

    // Replace numbers with custom icons
    function setFormHeading() {
        if (formHeading.length > 0) {
            $('#gf_step_7_1').html(formHeading[0]);
            $('#gf_step_7_2').html(formHeading[1]);
            $('#gf_step_7_3').html(formHeading[2]);
        }

        let formPageHeadingClass = "grid-cols-" + formHeading.length;
        $('#gf_page_steps_7').addClass(formPageHeadingClass);

        activeFormPage();
    }

    function activeFormPage() {
        $('#gf_page_steps_7 .gf_step').each(function () {
            $(this).removeClass('shadow-lg');

            if ($(this).hasClass('gf_step_active')) {
                $(this).addClass('shadow-lg');
            }
        });
    }

    function setRecipesCountAttr() {
        $('#gform_7 #field_7_15 .gchoice').each(function (index, el) {
            let input = $(this).find('input:checkbox');
            let label = $(this).find('label.gform-field-label');

            input.attr('data-count', 0);
            $('<div class="flex justify-center w-100"><span class="absolute recipe-count"></span></div>').insertBefore(label);
        });
    }

    function getSelectedRecipeCount() {
        let total = 0;

        $('body #gform_7 #field_7_15 input').each(function () {
            total += $(this).data().count;
        });

        return total;
    }

    function setMaxLengthZip() {
        // $('#input_7_19').attr('maxlength', 5);
        $('#input_7_40_5').attr('maxlength', 5);
    }

    function verifyZip(el) {
        let zipVal = el.currentTarget.value;
        let zipLength = zipVal.toString().length;

        if (zipLength !== 5) {
            return false;
        }
        
        if (zipRef !== zipVal) {
            zipRef = zipVal
            ajaxVerifyZip(zipVal);
        }
    }

    // ## GLOBALS ##

    const windowWidth = $(window).width();
    const windowHeight = $(window).height();
    const headerHeight = $('#header-main').outerHeight();
    const navbarHeight = $('#nav-bar-slideout').outerHeight();
    const heroHeight = windowHeight - (navbarHeight + headerHeight);
    const formContainer = $('#form-container');
    const pageContainer = $('#page-content');
    const formHeading = getFormHeading();
    const openMenuIcon = $("#openMenuIcon");
    const closeMenuIcon = $("#closeMenuIcon");
    const sidebarNav = $("#drawer-navigation");
    const form = $('form#gform_7');
    // const zip = $('input#input_7_19');
    const zip = $('input#input_7_40_5');
    const zipWarning = $('#zip-warning');
    let zipRef;

    // ## EVENT: WINDOW (LOAD) ##

    $(window).load(function () {
        
        // Page: Initialization - Form heading
        setFormHeading();

        // FORM: Zip code - Set max length (5) | Move warning | Verify location
        // setMaxLengthZip();
        zip.closest('.ginput_container').after(zipWarning);
        zip.on("keyup", $(this), verifyZip);
        zip.on("focusout", $(this), verifyZip);

        // Load form weight icons
        $('head').append('<style>#gform_wrapper_7 choice_6_16_0::before:before{width:800px !important;}</style>');

        // Show / hide form
        $('[data-action="form-display"]').on('click', function () {
            pageContainer.toggle();
            formContainer.toggle();
        });

        // HERO - Auto height 
        if (windowWidth >= 768) {
            $('#hero-main').css({
                height: heroHeight,
            });
        }
        
        $('#gform_next_button_7_7').on('click', function () {
            let form_data = $('#gform_7').serialize();
            let first_name = $('#input_7_18').val().toLowerCase().trim();
            let last_name = $('#input_7_26').val().toLowerCase().trim();
            let email = $('#input_7_3').val().toLowerCase().trim();

            if (first_name.length > 0 && last_name.length > 0 && email.length > 0) {
                form_create_temp_user(form_data, first_name, last_name, email) 
            }

        });

        // Page loaded: Update icons + set recipes count data attr
        jQuery(document).on('gform_page_loaded', function (event, form_id, current_page) {
            setFormHeading();

            if (current_page == 2) {
            }

            if (current_page == 3) {
                let couponInput = $('#gf_coupon_code_7');
                couponInput.val("WETNOSE10");
                
                let dogNames = '';
                let dogs = $(document).find('.dog-name')

                dogs.each(function (index) {
                    if ($(this).text().length > 0) {
                        let separator = (dogs.length > index + 2) ? ', and ' : '';
                        dogNames += $(this).text() + separator;    
                    }
                    
                });
                
                let dailyRate = (parseFloat($('#input_7_32').text().replace('$', '')) / 30).toFixed(2);
                let sectionHeadingContainer = $('#field_7_22');
                let sectionHeadingDescription = $('<span class="text-lg mt-2 inline-block">' + dogNames + ' will love our healthy, homemade dog food.</span>');
                let sectionHeadingText = $('<span class="stylized"> for only <strong class="emphasis ml-1">$' + dailyRate + '<span class="font-thin font-base">/</span>day</strong></span>');
                sectionHeadingText.appendTo(sectionHeadingContainer.find('h3'));
                $('#gfield_description_7_22').html(sectionHeadingDescription);
                // let updatedText = sectionHeading.text() + ' for only $' + dailyRate + '/day';
                // sectionHeading.text(updatedText);

                $('#gform_page_7_3 .gform_page_footer').hide();
                let checkoutDetailsContainer = $('<div id="form-checkout-details" class="col-start-8 col-span-5 md:px-16 md:pt-[4rem] bg-inputFill h-full w-full"></div>');
                checkoutDetailsContainer.insertAfter('#gform_fields_7_3');


                let checkoutDetails = $(document).find('#form-checkout-details');
                $('#field_7_35').appendTo(checkoutDetails);
                $('#field_7_35').appendTo(checkoutDetails);
                $('#field_7_41').appendTo(checkoutDetails);
                $('#field_7_39').appendTo(checkoutDetails);
                $('#field_7_43').appendTo(checkoutDetails);
                $('#field_7_32').appendTo(checkoutDetails);
                $('#field_7_42').appendTo(checkoutDetails);
                $('#field_7_38').appendTo(checkoutDetails);
                $('<button type="submit" class="w-full mt-6 rounded-md shadow-md hover:shadow-none border border-transparent bg-redSoft px-4 py-3 text-lg font-bold text-white hover:bg-redSoft focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Subscribe</button>').appendTo(checkoutDetails);

                $(document).find('#form-checkout-details')

            }

            if (current_page == 4) {
                // FORM: Recipes - Set hidden "Recipes Selected" field
                // let recipesSelectedVal = '';
                
                // $('#gform_7 #field_7_15 .gchoice input:checked').each(function (index) {
                //     recipesSelectedVal += $(this).closest('.gchoice').find('label.gform-field-label h3').text() + ((index > 0) ? ' ' : ' & ');
                // });

                // $('#input_7_25').val(recipesSelectedVal.trim());
                
            }
        });

        openMenuIcon.add(closeMenuIcon).on("click", openCloseSidebar);

    }); // END $(window).load(function()


})(jQuery); // jQuery End

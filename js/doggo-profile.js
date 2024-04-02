(function ($) {

    // ## AJAX ##
    function ajaxGetSubscriptionCost(mer, recipeIds) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "doggo_monthly_cost",
                mer: mer,
                recipeIds: recipeIds
            },
            dataType: "json",

            success: function (recipeInputsArray) {
                if (recipeInputsArray.length) {
                    let subscriptionCost = getTotalSubscriptionCost(recipeInputsArray);
                    console.table("subscriptionCost", subscriptionCost);
                    if (subscriptionCost > 0) {
                        $('body input#input_10_24').val(subscriptionCost);
                    }
                }
            },
            complete: function (recipeInputsArray) {
                if ($('body input#input_10_24').val() > 0) {
                    submitBtn.prop('disabled', false);
                }
            }
        });
    }    

    // AJAX - Populate recipe inputs
    function populateRecipeInputsDoggoProfile(recipeIds) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "doggo_recipe_inputs",
                recipeIds: recipeIds,
            },
            dataType: "json",
            success: function (data) {
                let recipeInputContainer = $('fieldset#field_10_20 .ginput_container');
                let recipeDetailsContainer = $('<div id="recipe_details_container" class="grid grid-cols-3 gap-x-8 col-span-12"></div>');
                recipeInputContainer.after(recipeDetailsContainer);

                // Object.entries(data).forEach(([key, value]) => {
                    // let input = $(document).find('#field_10_20 input[value="' + key + '"]');

                    // if (input.length === 1) {
                    // let label = input.closest('.gchoice').find('label');
                    // let recipeDetails = $(value);
                    // label.after(value);
                    // } 
                    // let targetInput = $(document).find('#field_10_20 input[value="' + key + '"]');

                    //     if (targetInput.length === 1) {
                    //         targetInput.next().html(value);
                    //     }
                // });
            },
            complete: function (data) {
                let targetContainer = $(document).find('#recipe_details_container');
                // let recipeContainerReversed = Object.keys(data.responseJSON).reverse();

                if (targetContainer.length === 1) {
                    // Object.entries(data.responseJSON).forEach(([key, value]) => { 
                    // Object.entries(recipeContainerReversed).forEach(([key, value]) => { 
                        targetContainer.append(data.responseJSON);
                    // });
                }
            }
        });
    }

    // ## FUNCTIONS ##
    function getRecipeIdsAndLoadNewInputs() {
        var recipeIds = [];

        $(document).find('#input_10_20 input[type="checkbox"]').each(function () {
            recipeIds.push($(this).val());
            if ($(this).attr("checked")) {
                console.table("Checked", $(this).val());
            } else {
                console.table("NOT checked", $(this).val());
            }
        });

        if (recipeIds.length > 0) {
            populateRecipeInputsDoggoProfile(recipeIds);
        }
    }
    function getTotalSubscriptionCost(recipeArray) {
        let totalCost = 0;

        for (let i = 0; i < recipeArray.length; i++) {
            for (let j = 0; j < recipeArray[i].length; j++) {
                totalCost += recipeArray[i][j].price.cost;
            }
        }

        return totalCost.toFixed(2);
    }


    function validateDoggoProfileForm(formInputs) {
        console.table("formInputs", formInputs);
        let emptyInputs = [];

        for (const item of formInputs) {
            if (item.name.includes('input') && !item.value) {
                emptyInputs.push(item.name);
            }
        }
        return emptyInputs;
    }

    function calculateMER(formArray) {
        let weightKg;
        let rer;
        let mer;

        let signalment = Number(formArray.find(item => item.name === "input_6").value);
        let activity = Number(formArray.find(item => item.name === "input_15").value);
        let bodyType = Number(formArray.find(item => item.name === "input_16").value);
        let weight = Number(formArray.find(item => item.name === "input_17").value);

        if (!signalment || !activity || !bodyType || !weight) {
            return false;
        }

        // 1. Convert Lbs to Kg
        if (weight > 0) {
            weightKg = (weight / 2.2).toFixed(2);
        }
        // 2. Resting energy requirement (RER)
        if (weightKg) {
            rer = Math.round(70 * (weightKg ** .75));
        }
        // 3. Calculate MER
        mer = Math.round(rer * signalment * activity * bodyType);
        
        return mer;
    }

    function setMerFromDoggoInputs(form) {
        let inputArray = $('form#gform_10').serializeArray();
        console.table("inputArray", inputArray);
        let merInput = form.find('#input_10_18');
        let formVals = {};

        $.each(inputArray, function (i, field) {
            if (field.name.includes("input_") && field.value.length > 0) {
                formVals[field.name] = field.value;
            }
        });

        let merVal = calculateMER(formVals);

        if (merVal && merVal > 0) {
            merInput.val(merVal);
        }

        return merVal;
    }

    function checkIfDoggoProfileSubmitIsDefined() {
        if (typeof submitBtn == 'undefined') submitBtn = $(document).find('.tingle-modal-box__footer .gpnf-btn-submit');
    }

    function setMerAndSubscriptionCost(formArray) {
        let mer = Number(calculateMER(formArray));

        if (!mer) return false;
        
        $('input#input_10_18').val(mer);

        let recipeIds = [];

        $(document).find('form#gform_10 #input_10_20 input[type="checkbox"]:checked').each(function () {
            recipeIds.push($(this).val());
        });

        if (recipeIds.length > 0) {
            ajaxGetSubscriptionCost(mer, recipeIds)
        }
        
    }

    function serializeFormAndValidateInputs(formArray) {
        let checkedRecipes = $('body form#gform_10 #input_10_20 input[type="checkbox"]:checked').length;

        if (checkedRecipes > 0) {
            let checkedCheckboxes = $('body form#gform_10 #input_10_20 input[type="checkbox"]:checked').map(function () {
                return { name: this.name, value: this.value };
            }).get();
            formArray = formArray.concat(checkedCheckboxes);
        } else {
            formArray.push({ name: "input_20", value: "" });
        }

        return validateDoggoProfileForm(formArray);
    }

    // ## WINDOW LOAD ##
    $(document).ready(function () {

        $(document).on('gpnf_post_render', function (event, nestedFormId, currentPage) {
            if (nestedFormId === 10) {
                var $modal = $('.gpnf-modal-7-8');
                if ($modal.length > 0 && $modal.hasClass('tingle-modal--visible')) {
                    
                    // Recipe details (ajax)
                    getRecipeIdsAndLoadNewInputs();

                    let form = $(document).find('form#gform_10');
                    // form.find('#input')
                    // getRecipeIdsAndLoadNewInputs();

                    form.on('change', 'input, radio, checkbox, select', function () {
                        let formArray = $('body form#gform_10').serializeArray();
                        let emptyInputs = serializeFormAndValidateInputs(formArray);

                        if (emptyInputs.length <= 2) {
                            setMerAndSubscriptionCost(formArray);
                        }
                    });

                    // Example: Attaching a click event handler to a button inside the modal
                    $modal.find('.my-custom-button').on('click', function () {
                        console.log('Custom button clicked!');
                    });
                }
            }
        });

        // Listen for launch of "Add Pup" modal window
        document.addEventListener('click', function (event) {
            if (event.target.matches('.gpnf-add-entry')) {
                setTimeout(function () {
                    
                    checkIfDoggoProfileSubmitIsDefined();
                    submitBtn.prop("disabled", true);
                    
                    // Dynamic labels & headings
                    let genderNameSpan = $('<span><span class="profile-name-label">Her</span> name is</span>');
                    $('label[for="input_10_1"]').html(genderNameSpan);
                    let nueteredSpan = $('<span class="profile-nuetered-label">My pup</span> <span>is</span>');
                    $('label[for="input_10_6"]').html(nueteredSpan);
                    let describeSectionSpan = $('<span class="stylized">Describe <span class="profile-describe-section stylized">your dog</span></span>');
                    $('#field_10_25 > h3').html(describeSectionSpan);
                    let activitySpan = $('<span class="">What\'s <span class="profile-activity-label">your pups</span> average daily activity level?</span>');
                    $('#field_10_15 > legend').html(activitySpan);
                    let bodyTypeSpan = $('<span class="">What\'s <span class="profile-body-label">your pups</span> current body type?</span>');
                    $('#field_10_16 > legend').html(bodyTypeSpan);
                    let submitBtnSpan = $('<span class="stylized">Add  <span class="profile-submit-btn stylized">Your Pup</span></span>');
                    $('.tingle-modal-box__footer button.gpnf-btn-submit').html(submitBtnSpan);
                    

                }, 0);
            }
        }, true); 

        

        // EVENT: Open/close recipe accordion
        $(document).on("click", ".profile-recipe-accordion button.group", function () {
            $(this).find('svg').toggleClass('hidden');
            $(this).closest('div').find('.prose').toggleClass('hidden');
        });

        // EVENT:: Dynamic name label (she/he)
        $(document).on("change", "form#gform_10 select#input_10_3", function (event) {
            let gender = $(this).val();
            let nameLabelText = (gender == "She") ? 'Her' : 'His';
            let nameFieldLabel = $(this).closest('div.gfield').next().find('span.profile-name-label');
            nameFieldLabel.text(nameLabelText);
        });

        // EVENT:: Dynamic name insertion
        $(document).on("focusout", "form#gform_10 input#input_10_1", function (event) {
            let name = $(this).val();

            if (name.length > 0) {
                $(this).closest('.tingle-modal').find('.profile-nuetered-label,.profile-describe-section,.profile-activity-label,.profile-body-label,.profile-submit-btn').text(name);
            }
        });

        // $(document).on('change', '#input_10_20 input[type="checkbox"]', function () {
        //     let form = $(this).closest('.tingle-modal').find('form');
        //     let isDoggoProfileComplete = validateDoggoProfileForm(form.serializeArray());
        //     let mer = setMerFromDoggoInputs(form);
        //     let recipes = [];

        //     $('form #input_10_20 .gchoice').each(function () {
        //         let input = $(this).find('input');
                
        //         if (input.is(':checked')) {
        //             recipes.push(input.val())
        //         }
        //     });

        //     let cost = doggoMonthlyCost(mer, recipes);
        //     console.table("cost", cost);
        // });


    }); // END $(window).load(function()


})(jQuery); // jQuery End

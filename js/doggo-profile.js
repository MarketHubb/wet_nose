(function ($) {

    // ## AJAX ##
    function ajaxGetSubscriptionPrice(mer, recipeIds) {
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'doggo_monthly_cost',
                mer: mer,
                recipeIds: recipeIds
            },
            dataType: 'json',
 
            success: function (recipeInputsArray) {
                console.table('recipeInputsArray', recipeInputsArray);
                if (recipeInputsArray.length) {
                    let subscriptionCost = calculateTotalSubscriptionPrice(recipeInputsArray);
                    if (subscriptionCost > 0) {
                        $('body input#input_10_24').val(subscriptionCost);
                    }
                }
            },
            complete: function () {
                if ($('body input#input_10_24').val() > 0) {
                    $(document).find('.tingle-modal-box__footer .gpnf-btn-submit').prop('disabled', false);
                }
            }
        });
    }    

    // AJAX - Populate recipe inputs
    function populateRecipeInputsDoggoProfile(recipeIds) {
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'doggo_recipe_inputs',
                recipeIds: recipeIds,
            },
            dataType: 'json',
            success: function () {
                let recipeInputContainer = $('fieldset#field_10_20 .ginput_container');
                let recipeDetailsContainer = $('<div id="recipe_details_container" class="grid grid-cols-3 gap-x-8 col-span-12"></div>');
                recipeInputContainer.after(recipeDetailsContainer);
            },
            complete: function (data) {
                let targetContainer = $(document).find('#recipe_details_container');

                if (targetContainer.length === 1) {
                    targetContainer.append(data.responseJSON);
                    connectRecipeCheckboxesAndImages();
                }
            }
        });
    }

    // ## FUNCTIONS ##
    function getRecipeIds(checked) {
        checked = !!checked;
        let targetCheckboxEl = (checked) ? 'input[type="checkbox"]:checked' : 'input[type="checkbox"]';
        var recipeIds = [];

        $(document).find('#input_10_20 ' + targetCheckboxEl).each(function () {
            recipeIds.push($(this).val());
        });

        return recipeIds;
    }

    function getRecipeIdsAndLoadNewInputs() {
        let recipeIds = getRecipeIds();        

        if (recipeIds.length > 0) {
            populateRecipeInputsDoggoProfile(recipeIds);
        }
    }
    function calculateTotalSubscriptionPrice(data) {
        let totalPrice = 0;

        for (let i = 0; i < data.length; i++) {
            const item = data[i][0];
            totalPrice += item.price.price;
        }

        console.table('totalPrice', totalPrice);

        return totalPrice;
    }


    function validateDoggoProfileForm(formInputs) {
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

        let signalment = Number(formArray.find(item => item.name === 'input_6')?.value) || undefined;
        let activity = Number(formArray.find(item => item.name === 'input_15')?.value) || undefined;
        let bodyType = Number(formArray.find(item => item.name === 'input_16')?.value) || undefined;
        let weight = Number(formArray.find(item => item.name === 'input_17')?.value) || undefined;

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

    function setMerAndSubscriptionCost(formArray) {
        let mer = Number(calculateMER(formArray));

        if (!mer) return false;
        
        $('input#input_10_18').val(mer);

        let selectedRecipeIds = [];

        $(document).find('form#gform_10 #input_10_20 input[type="checkbox"]:checked').each(function () {
            selectedRecipeIds.push($(this).val());
        });

        if (selectedRecipeIds.length > 0) {
            ajaxGetSubscriptionPrice(mer, selectedRecipeIds);
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
            formArray.push({ name: 'input_20', value: '' });
        }

        setMerAndSubscriptionCost(formArray);

        return validateDoggoProfileForm(formArray);
    }

    function setCleanRecipeInputVal() {
        let recipeText = '';
        let selectedRecipes = $(document).find('#field_10_20 input[type="checkbox"]:checked');
        let i = 1;

        selectedRecipes.each(function() {
            let seperator = (i != selectedRecipes.length) ? ' | ' : '';
            recipeText += $(this).next().text() + seperator;
            i++;
        });

        $(document).find('#input_10_26').val(recipeText);
    }

    function updateRecipeDetailsClasses() {
        $(document).find('.recipe-details').each(function () {
            let containerId = $(this).data('id');
            let checkbox = $(document).find('#field_10_20 input[value="' + Number(containerId) + '"]');
            let checkedState = checkbox.prop('checked');

            $(this).toggleClass('selected-recipe', checkedState);
            $(this).toggleClass('disabled-recipe', checkbox.prop('disabled'));
        });

        setCleanRecipeInputVal();
    }

    function connectRecipeCheckboxesAndImages() {
        const recipeDetails = $(document).find('.recipe-details');

        console.table('recipeDetails', recipeDetails);

        recipeDetails.each(function () {
            const recipeImage = $(this).find('.recipe-image');
            const selectedRecipeIcon = $(this).find('.selected-recipe-icon');
            const containerId = $(this).data('id');

            recipeImage.on('click', function () {
                const checkbox = $(`#field_10_20 input[type="checkbox"][value="${containerId}"]`);

                if (checkbox.prop('disabled')) {
                    return; 
                }

                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            });

            selectedRecipeIcon.on('click', function () {
                const checkbox = $(`#field_10_20 input[type="checkbox"][value="${containerId}"]`);

                if (checkbox.prop('disabled')) {
                    return; 
                }

                checkbox.prop('checked', false).trigger('change');
            });
        });

        updateRecipeDetailsClasses();
    }


    // ## WINDOW LOAD ##
    $(document).ready(function () {

        $(document).on('gpnf_post_render', function (event, nestedFormId, currentPage) {
            if (nestedFormId === 10) {
                var $modal = $('.gpnf-modal-7-8');
                if ($modal.length > 0 && $modal.hasClass('tingle-modal--visible')) {
                    
                    // AJAX
                    getRecipeIdsAndLoadNewInputs();

                    // GLOBAL 
                    $('#field_10_20').on('change', 'input[type="checkbox"]', updateRecipeDetailsClasses);

                    let form = $(document).find('form#gform_10');

                    form.on('change', 'input, radio, checkbox, select', function () {
                        let formArray = $('body form#gform_10').serializeArray();
                         serializeFormAndValidateInputs(formArray);
                    });
                    
                }
            }
        });

        // Listen for launch of "Add Pup" modal window
        document.addEventListener('click', function (event) {
            if (event.target.matches('.gpnf-add-entry')) {
                setTimeout(function () {
                    
                    let submitBtn = $(document).find('.tingle-modal-box__footer .gpnf-btn-submit');
                    submitBtn.prop('disabled', true);
                    
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
        $(document).on('click', '.profile-recipe-accordion button.group', function () {
            $(this).find('svg').toggleClass('hidden');
            $(this).closest('div').find('.prose').toggleClass('hidden');
        });

        // EVENT:: Dynamic name label (she/he)
        $(document).on('change', 'form#gform_10 select#input_10_3', function () {
            let gender = $(this).val();
            let nameLabelText = (gender == 'She') ? 'Her' : 'His';
            let nameFieldLabel = $(this).closest('div.gfield').next().find('span.profile-name-label');
            nameFieldLabel.text(nameLabelText);
        });

        // EVENT:: Dynamic name insertion
        $(document).on('focusout', 'form#gform_10 input#input_10_1', function () {
            let name = $(this).val();

            if (name.length > 0) {
                $(this).closest('.tingle-modal').find('.profile-nuetered-label,.profile-describe-section,.profile-activity-label,.profile-body-label,.profile-submit-btn').text(name);
            }
        });

    }); // END $(window).load(function()


})(jQuery); // jQuery End

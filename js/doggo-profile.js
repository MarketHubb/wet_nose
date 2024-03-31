(function ($) {
    // ## AJAX ##
    function doggoMonthlyCost(mer, recipeIds) {
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: "doggo_monthly_cost",
                mer: mer,
                recipeIds: recipeIds
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

    function calculateMER(formVals) {
        let age = Number(formVals.input_13);
        let signalment = Number(formVals.input_6);
        let activity = Number(formVals.input_15);
        let bodyType = Number(formVals.input_16);
        let weight = Number(formVals.input_17);
        let weightKg;
        let rer;
        let mer;

        // Convert Lbs to Kg
        if (weight > 0) {
            weightKg = (weight / 2.2).toFixed(2);
        }

        // Resting Energy Requirement (RER)
        if (weightKg) {
            rer = Math.round(70 * (weightKg ** .75));
        }

        // Calculate  MER
        mer = Math.round(rer * signalment * activity * bodyType);
        
        return mer;
    }

    function setMerFromDoggoInputs(form) {
        let inputArray = $('form#gform_10').serializeArray();
        let merInput = form.find('#input_10_18');
        let formVals = {};

        $.each(inputArray, function (i, field) {
            if (field.name.includes("input_") && field.value.length > 0) {
                formVals[field.name] = field.value;
            }
        });

        if (Object.keys(formVals).length >= 10) {
            let merVal = calculateMER(formVals);

            if (merVal && merVal > 0) {
                merInput.val(merVal);
            }

            return merVal;
        }
    }

    function initialize_recipe_count_attributes() {

    }

    // ## WINDOW LOAD ##
    $(window).load(function () {

        // Listen for launch of "Add Pup" modal window
        document.addEventListener('click', function (event) {
            if (event.target.matches('.gpnf-add-entry')) {
                setTimeout(function () {
                    
                    // Setup labels and section headings for dynamic insertion
                    let genderNameSpan = $('<span><span class="profile-name-label">Her</span> name is</span>');
                    $('label[for="input_10_1"]').html(genderNameSpan);
                    let describeSectionSpan = $('<span class="stylized">Describe <span class="profile-desction-section stylized">your dog</span></span>');
                    $('#field_10_25 > h3').html(describeSectionSpan);
                    // let nameLabel = $('label[for="input_10_1"]');
                    // let newLabel = genderNameSpan + nameLabel.text();
                    // nameLabel.html(newLabel);


                    // Disable submit until Ajax returned
                    // let submitButton = document.querySelector('.tingle-btn--primary.gpnf-btn-submit');
                    // submitButton.disabled = true;
                    
                }, 0);
            }
        }, true); // Set the 'capture' option to 'true'

        // Plugin: Gravity Wiz - Nested form buttons
        gform.addFilter('gpnf_modal_button_css_classes', function (classes, buttonType) {
            
            if (buttonType === "submit") {
                classes += ' custom-class';    
            }
            
            return classes;
        });

        // EVENT:: Change name label on gender (select) change
        $(document).on("change", "form#gform_10 select", function (event) {
            let targetId = event.target.id;

            if (targetId == 'input_10_3') {
                let gender = $(this).val();
                let nameLabelText = (gender == "She") ? 'Her' : 'His';
                let nameFieldLabel = $(this).closest('div.gfield').next().find('span.profile-name-label');
                nameFieldLabel.text(nameLabelText);
            }

            if (targetId == 'input_10_13' && $('#input_10_1').val().length > 0) {
                let name = $('#input_10_1').val();
                let descriptionSectionSpan = $(this).closest('form').find('.profile-desction-section');
                descriptionSectionSpan.text(name);
            }

        });
        // $('document').on("change", "select#input_10_3", function () {
        //     let gender = $(this).val();
        //     console.table("gender", gender);
        //     let nameFieldLabel = $(this).closest('div.gfield').next().find('label.gfield_label');
        //     nameFieldLabel.text("Does this work");
        // });

        // EVENT (Change): Form - Doggo Profile
        // $('body').on("change", "#gform_10 select, #gform_10 input", function () {

        // document.addEventListener('DOMContentLoaded', function () {
        //     const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        //     let totalCount = 0;

        //     // Initialize all checkboxes with data-count=0
        //     checkboxes.forEach(checkbox => {
        //         checkbox.setAttribute('data-count', '0');
        //     });

        //     // Event listener for checkbox click/touch
        //     checkboxes.forEach(checkbox => {
        //         checkbox.addEventListener('change', function () {
        //             const currentCount = parseInt(this.getAttribute('data-count'));
      
        //             if (this.checked) {
        //                 if (currentCount === 0 && totalCount < 2) {
        //                     this.setAttribute('data-count', '1');
        //                     totalCount++;
        //                 } else if (currentCount === 1) {
        //                     this.setAttribute('data-count', '2');
        //                     totalCount++;
        //                     checkboxes.forEach(otherCheckbox => {
        //                         if (otherCheckbox !== this) {
        //                             otherCheckbox.setAttribute('data-count', '0');
        //                             otherCheckbox.checked = false;
        //                         }
        //                     });
        //                 }
        //             } else {
        //                 if (currentCount === 2) {
        //                     this.setAttribute('data-count', '1');
        //                     totalCount--;
        //                 } else if (currentCount === 1) {
        //                     this.setAttribute('data-count', '0');
        //                     totalCount--;
        //                 }
        //             }
      
        //             updateCountVisual(this);
        //         });
        //     });

        // Function to update the count visual using Tailwind CSS classes
        //     function updateCountVisual(checkbox) {
        //         const label = checkbox.nextElementSibling;
        //         const countVisual = label.querySelector('.count-visual');
    
        //         if (!countVisual) {
        //             const newCountVisual = document.createElement('div');
        //             newCountVisual.classList.add('count-visual', 'mt-2', 'text-sm', 'font-bold');
        //             label.appendChild(newCountVisual);
        //         }
    
        //         const count = parseInt(checkbox.getAttribute('data-count'));
        //         label.querySelector('.count-visual').textContent = `Count: ${count}`;
    
        //         if (count === 0) {
        //             label.classList.remove('bg-green-200');
        //         } else if (count === 1) {
        //             label.classList.add('bg-green-200');
        //         } else if (count === 2) {
        //             label.classList.add('bg-green-400');
        //         }
        //     }
        // });

       



        //     let form = $(this).closest('form');
        //     let submitBtn = $(this).closest('.tingle-modal').find('.tingle-modal-box__footer .gpnf-btn-submit');
        //     let testBtn = $(this).closest('.tingle-modal').find('#custom-form-submit');

        //     if (!testBtn.length > 0 || testBtn === undefined) {
        //         $('<button id="custom-form-submit">Test Form</button>').insertAfter(submitBtn);    
        //     }
            
        //     submitBtn.attr('disabled', true);
        //     let mer = setMerFromDoggoInputs(form);
        //     console.table("mer", mer);
        // });

        $('body').on('click', '#custom-form-submit', function () {
            let form = $(this).closest('.tingle-modal').find('form');
            console.table("form", form);
            let mer = setMerFromDoggoInputs(form);
            let recipes = [];

            $('form #input_10_20 .gchoice').each(function () {
                let input = $(this).find('input');
                
                if (input.is(':checked')) {
                    recipes.push(input.val())
                }
            });

            // if (formInputs.input_18 && formInputs.input) {}

            let cost = doggoMonthlyCost(mer, recipes);
            console.table("cost", cost);
        });

    }); // END $(window).load(function()


})(jQuery); // jQuery End

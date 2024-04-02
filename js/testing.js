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

         success: function (recipeInputsArray) {
            if (recipeInputsArray.length) {
               let subscriptionCost = calculateSubscriptionCost(recipeInputsArray);
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
            Object.entries(data).forEach(([key, value]) => {
               let targetInput = $(document).find('#field_10_20 input[value="' + key + '"]');

               if (targetInput.length === 1) {
                  targetInput.next().html(value);
               }
            });
         },
         complete: function (data) {
                
         }
      });
   }

   // ## FUNCTIONS ##
   function getRecipeIdsAndUpdateInput() {
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
   function calculateSubscriptionCost(recipeArray) {
      let totalCost = 0;

      for (let i = 0; i < recipeArray.length; i++) {
         for (let j = 0; j < recipeArray[i].length; j++) {
            totalCost += recipeArray[i][j].price.cost;
         }
      }

      return totalCost.toFixed(2);
   }


   function validateDoggoProfileForm(formInputs) {

      for (const item of formInputs) {
         if (item.name.includes('input') && !item.value) {
            return false;
         }
      }
      return true;
   }

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
   
   $(document).ready(function () {

      $(document).on('gpnf_post_render', function (event, nestedFormId, currentPage) {
         if (nestedFormId === 10) {
            var $modal = $('.gpnf-modal-7-8');
            if ($modal.length > 0 && $modal.hasClass('tingle-modal--visible')) {
               // Your custom script to be executed when the nested form with ID 10 is rendered
               $('#input_10_24').val(100);
               $('#input_10_18').val(1000);

               let form = $(document).find('form#gform_10');
               form.on('change', 'input, radio, checkbox, select', function () {
                  console.log('here');
                  let formInputs = $('body form#gform_10').serializeArray();
                  validateDoggoProfileForm(formInputs);
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

               getRecipeIdsAndUpdateInput();
                    

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
})(jQuery);
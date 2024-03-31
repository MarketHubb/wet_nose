<?php
add_action('wp_ajax_form_create_temp_user', 'form_create_temp_user');
add_action('wp_ajax_nopriv_form_create_temp_user', 'form_create_temp_user');

function form_create_temp_user() {
    $form_data = $_POST['form_data'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    parse_str($form_data, $form_values);

    $form_id = $form_values['gform_submit'];

     // Generate a random password & username
    $password = wp_generate_password(12, false);
    $username = trim(strtolower($first_name)) . '_' . trim(strtolower($last_name));

    if ($form_id != 7) {
        wp_send_json_error('Invalid form ID');
    }

    // Check if the user already exists
    if (email_exists($email) || username_exists($username)) {
        $existing_user = get_user_by_email(trim($email));

        if (isset($existing_user)) {
            wp_send_json_success($existing_user);
        }
    }

    // Create the user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error($user_id->get_error_message());
    }

    // Update user meta
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);

    // Set user role to 'customer'
    $user = new WP_User($user_id);
    $user->set_role('customer');

    // Set nickname
    wp_update_user(array(
        'ID' => $user_id,
        'nickname' => $first_name,
    ));

    // Set temporary password
    wp_set_password($password, $user_id);

    // Send password reset email
    wp_new_user_notification($user_id, null, 'both');

    wp_send_json_success($user);
}


add_action('wp_ajax_create_temp_customer_user', 'create_temp_customer_user');
add_action('wp_ajax_nopriv_create_temp_customer_user', 'create_temp_customer_user');

function create_temp_customer_user()
{
    $form_data = $_POST['form_data'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    parse_str($form_data, $form_values);

    $form_id = $form_values['gform_submit'];

    if ($form_id != 7) {
        wp_send_json_error('Invalid form ID');
    }

    $username = trim(strtolower($first_name)) . '_' . trim(strtolower($last_name));
    $user_registration = new GF_User_Registration();
    $partial_entry = $form_values;
    $partial_entry['first_name'] = $first_name;
    $partial_entry['last_name'] = $last_name;
    $partial_entry['username'] = $username;
    $partial_entry['email'] = $email;

    // Generate a random password
    $password = wp_generate_password(12, false);
    $partial_entry['password'] = $password;
    
    $form = GFAPI::get_form($form_id);
    $user_setup = $user_registration->maybe_process_feed($partial_entry, $form);

    if (is_wp_error($user_setup)) {
        wp_send_json_error($user_setup->get_error_message());
    } else {
        wp_send_json_success('User created');
    }
}


// Create customer user role in WP
add_action('wp_ajax_create_user_on_next_button', 'create_user_on_next_button');
add_action('wp_ajax_nopriv_create_user_on_next_button', 'create_user_on_next_button');
function create_user_on_next_button()
{
    $form_data = $_POST['form_data'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    parse_str($form_data, $form_values);

    $form_id = $form_values['gform_submit'];

    if ($form_id != 7) {
        wp_send_json_error('Invalid form ID');
    }

    $user_registration = new GF_User_Registration();
    $partial_entry = $form_values;
    $partial_entry['first_name'] = $first_name;
    $partial_entry['last_name'] = $last_name;
    $partial_entry['username'] = $username;
    $partial_entry['email'] = $email;

    // Generate a random password
    $password = wp_generate_password(12, false);
    $partial_entry['password'] = $password;

    $form = GFAPI::get_form($form_id);
    $user_registration->maybe_process_feed($partial_entry, $form);

    wp_send_json_success('User created');
}

// Form: Doggo Profile - Calculate subscription price
add_action('wp_ajax_doggo_monthly_cost', 'doggo_monthly_cost');
add_action('wp_ajax_nopriv_doggo_monthly_cost', 'doggo_monthly_cost');
function doggo_monthly_cost()
{
    if (!empty($_POST['mer']) && !empty($_POST['recipeIds'])) {
        $daily_mer = $_POST['mer'];
        $recipe_ids = $_POST['recipeIds'];
        $cals_per_recipe = ($daily_mer * 30) / count($recipe_ids);

        if ($daily_mer <= 0 || empty($recipe_ids)) {
            die();
        }

        $output = [];

        // $base_recipe_inputs = [];

        foreach ($recipe_ids as $recipe_id) {
            // $base_recipe_inputs[] = getIngredientsforRecipes($recipe_id, 1);
            $base_recipe = getIngredientsforRecipes($recipe_id, 1);
            $base_cals = $base_recipe[0]['totals']['calories'];
            $adjustment = floatval($cals_per_recipe / $base_cals);
            $adjusted_recipe = getIngredientsforRecipes($recipe_id, $adjustment);
            $output[] = $adjusted_recipe;
        }

        // if (!empty($base_recipe_inputs)) {
        //     foreach ($base_recipe_inputs as $base_recipe_input) {
        //         $base_cals = $base_recipe_input[0]['totals']['calories'];
        //         $adjustment = floatval($cals_per_recipe / $base_cals);
        //         $output[] = $base_cals;
        //     }
        // }

        // echo json_encode($base_recipe_inputs);
        echo json_encode($output);
        die();
    }
}

// Form: Doggo Profile - Verify zip code
add_action('wp_ajax_form_verify_zip', 'form_verify_zip');
add_action('wp_ajax_nopriv_form_verify_zip', 'form_verify_zip');
function form_verify_zip()
{
    if (!empty($_POST['zip'])) {
        $output = [];

        $zip_codes_in_range = get_field('form_zip_codes', 'option');
        $zip_codes_in_range = explode(',', $zip_codes_in_range);
        $zip_codes_in_range = array_map('trim', $zip_codes_in_range);

        $output[] = $zip_codes_in_range;

        $found = (in_array(trim($_POST['zip']), $zip_codes_in_range)) ? true : false;

        $output[] = $found;


        echo json_encode($output);
        die();
    }
}


add_action('wp_ajax_get_recipe_inputs', 'get_recipe_inputs');
add_action('wp_ajax_nopriv_get_recipe_inputs', 'get_recipe_inputs');

function get_recipe_inputs()
{
    if (!empty($_POST['post_id']) && !empty($_POST['name']) && !empty($_POST['updateType']) && !empty($_POST['baseVal']) && !empty($_POST['newVal'])) {

        $post_id = $_POST['post_id'];
        $name = $_POST['name'];
        $updateType = $_POST['updateType'];
        $baseVal = $_POST['baseVal'];
        $newVal = $_POST['newVal'];
        global $wp_embed, $post;

        $recipe_tables = [];

        $adjustment = floatval($newVal / $baseVal);

        $recipes = getIngredientsforRecipes($post_id, $adjustment);

        foreach ($recipes as $recipe) {
            $recipe_tables[] =  get_recipe_table($recipe, false);
        }

        echo json_encode($recipe_tables);

        wp_reset_postdata();
    }
    die();
}

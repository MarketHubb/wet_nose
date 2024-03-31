<?php
/*
Template Name: WooCommerce Account Sign-up (Tailwind CSS)
*/

// Include the header
get_header();
?>

<?php
// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    $errors = new WP_Error();

    // Validate the form data
    if (empty($username)) {
        $errors->add('username_empty', __('Please enter a username.', 'woocommerce'));
    }
    if (empty($email)) {
        $errors->add('email_empty', __('Please enter an email address.', 'woocommerce'));
    }
    if (empty($password)) {
        $errors->add('password_empty', __('Please enter a password.', 'woocommerce'));
    }

    if ($errors->get_error_code()) {
        // Display form errors
        foreach ($errors->get_error_messages() as $error) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . esc_html($error) . '</div>';
        }
    } else {
        // Create the new user account
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            // Display registration error
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . esc_html($user_id->get_error_message()) . '</div>';
        } else {
            // Log in the new user and redirect to the account page
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }
    }
}
?>

<div class="woocommerce-account-signup bg-gray-100 py-10">
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Create an Account', 'woocommerce' ); ?></h2>

                    <form method="post" class="woocommerce-form woocommerce-form-register register">

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="text-red-500">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="text-red-500">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="text-red-500">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" id="reg_password" autocomplete="new-password" />
                        </div>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <div class="flex items-center justify-between">
                            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                        </div>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer
get_footer();
?>



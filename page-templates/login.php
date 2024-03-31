<?php
/*
Template Name: WooCommerce Login (Tailwind CSS)
*/

// Include the header
get_header();

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate the user
    $user = wp_signon(array(
        'user_login' => $username,
        'user_password' => $password,
        'remember' => isset($_POST['rememberme']),
    ), false);

    if (is_wp_error($user)) {
        // Display login error
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . esc_html($user->get_error_message()) . '</div>';
    } else {
        // Redirect to the manage account page
        wp_redirect(wc_get_account_endpoint_url('edit-account'));
        exit;
    }
}
?>

<div class="woocommerce-account-login bg-gray-100 py-10">
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Login', 'woocommerce'); ?></h2>

                    <form method="post" class="woocommerce-form woocommerce-form-login login">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="text-red-500">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-red-500">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" id="password" autocomplete="current-password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input class="mr-2 leading-tight" type="checkbox" name="rememberme" id="rememberme" value="forever" />
                                <label class="text-sm" for="rememberme"><?php esc_html_e('Remember me', 'woocommerce'); ?></label>
                            </div>
                            <a class="inline-block align-baseline text-sm text-blue-500 hover:text-blue-800" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
                        </div>

                        <div class="mt-6">
                            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                            <button type="submit" class="woocommerce-button button woocommerce-form-login__submit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
                        </div>
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
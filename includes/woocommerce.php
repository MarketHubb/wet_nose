<?php 
// Customize navigation
function custom_account_navigation_item( $items ) {
    unset( $items['downloads'] );
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_account_navigation_item' );

// Add "Doggo Profiles" to account nav
function add_custom_account_menu_items( $menu_items ) {
    // Add new menu items
    $new_menu_items = array(
        'doggo-profiles' => __( 'Doggo Profiles', 'tailpress' ),
    );

    // Insert the new menu items after the "Orders" menu item
    $menu_items = array_slice( $menu_items, 0, 2, true ) +
                  $new_menu_items +
                  array_slice( $menu_items, 2, NULL, true );

    return $menu_items;
}
add_filter( 'woocommerce_account_menu_items', 'add_custom_account_menu_items' );

// Register the custom endpoint
function register_doggo_profiles_endpoint() {
    add_rewrite_endpoint( 'doggo-profiles', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'register_doggo_profiles_endpoint' );

// Handle the custom endpoint content
function doggo_profiles_endpoint_content() {
    include get_template_directory() . '/woocommerce/myaccount/doggo-profiles.php';
}
add_action( 'woocommerce_account_doggo-profiles_endpoint', 'doggo_profiles_endpoint_content' );

// Create new "customer" user when first page of lead form is completed
add_action( 'gform_partialentries_post_entry_saved', function( $partial_entry, $form, $resume_token ) {
    if ( $form['id'] != 7 ) {
        return;
    }
    
    $user_registration = new GF_User_Registration();
    $user_registration->maybe_process_feed( $partial_entry, $form );
}, 10, 3 );
<?php

add_action('init', function() {

    // Key, Display Name, (3rd arg "Capabilities" to be added below)
    add_role('customer', 'Customer');

    // Get the "Customer" role
    $customer = get_role('customer');

    // Give minimum (subscriber / read only) capabilities to access WP backend
    $customer->add_cap('read');

});
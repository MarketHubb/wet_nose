<?php
/*
Template Name: Stripe Demo
*/

get_header();
?>

<div class="container">
    <h1>Stripe Payment Demo</h1>

    <form id="stripe-payment-form" method="POST">
        <div class="form-group">
            <label for="amount">Amount (in cents)</label>
            <input type="number" id="amount" name="amount" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Pay with Stripe</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51O2cExBQlCuWXXZWZW6RrRdf3ApL92XVzx7BL4pj3UtMKrZ0BqgCIrAq2XefBQ4lxRH9Alt6YhekfOTFbfte8jsK00zEq8IDX0');
    var form = document.getElementById('stripe-payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        var amount = document.getElementById('amount').value;

        stripe.redirectToCheckout({
            lineItems: [{
                price: 'price_1MiVNbHV8KzvUQPcAzvzVGV1',
                quantity: 1,
            }],
            mode: 'payment',
            successUrl: '<?php echo home_url('/payment-success'); ?>',
            cancelUrl: '<?php echo home_url('/payment-canceled'); ?>',
        }).then(function(result) {
            if (result.error) {
                alert(result.error.message);
            }
        });
    });
</script>

<?php
get_footer();
?>
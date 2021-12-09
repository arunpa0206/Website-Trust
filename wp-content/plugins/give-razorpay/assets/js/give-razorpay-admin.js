jQuery(document).ready(function($){

    var accountOptions = $( 'input[name="razorpay_per_form_account_options"]' );

    accountOptions.on( 'change', function() {
        if ( 'enabled' === $( this ).val() ) {
            $( '.give-razorpay-meta-field' ).show();
        } else {
            $( '.give-razorpay-meta-field' ).hide();
        }
    });

});
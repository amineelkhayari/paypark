$form = $(".require-validation");
var base_url = $('meta[name=base_url]').attr('content');
var duration, amount, subscription_id, currency;
var formData = new FormData();
$(document).ready(function () {
    currency = $('input[name=currency]').val();
    $('.purchase').click(function () {
        $('.paymentCard').toggle(500);
    });

    // image upload
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var type = $('#imagePreview').attr('data-id');
                var fileName = document.getElementById("image").value;
                var idxDot = fileName.lastIndexOf(".") + 1;
                var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
                if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                else {
                    $('input[type=file]').val('');
                    alert("Only jpg/jpeg and png files are allowed!");
                    if (type == 'add') {
                        $('#imagePreview').css('background-image', 'url()');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image").change(function () {
        readURL(this);
    });

    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var type = $('#imagePreview1').attr('data-id');
                var fileName = document.getElementById("image1").value;
                var idxDot = fileName.lastIndexOf(".") + 1;
                var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
                if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                    $('#imagePreview1').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview1').hide();
                    $('#imagePreview1').fadeIn(650);
                }
                else {
                    $('input[type=file]').val('');
                    alert("Only jpg/jpeg and png files are allowed!");
                    if (type == 'add') {
                        $('#imagePreview1').css('background-image', 'url()');
                        $('#imagePreview1').hide();
                        $('#imagePreview1').fadeIn(650);
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image1").change(function () {
        readURL1(this);
    });

    $('input[name="paymentRadio"]').change(function () {
        var rdBtn = $('input[name="radioBtn"]:checked').val();
        rdBtn = rdBtn.split("/");
        duration = rdBtn[0];
        amount = rdBtn[1];
        subscription_id = rdBtn[2];

        formData.append('duration', duration);
        formData.append('amount', amount);
        formData.append('subscription_id', subscription_id);
        if (this.value == 'stripe') {
            $('.stripeCard').show(500);
            console.log(formData, 'from change payment method')

            $('.paypalCard').hide(500);
            StripePayment();
        }

        if (this.value == 'paypal') {
            $('.paypalCard').show(500);
            $('.stripeCard').hide(500);
            paypalPayment();
        }

    });

    // datatable start
    var datatable = $('.datatable').DataTable({
        "buttons": [
            'print',
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
        ],
        language: {
            paginate:
            {
                previous: "<i class='fa fa-angle-left'>",
                next: "<i class='fa fa-angle-right'>",
                first: "<i class='fa fa-angle-double-left'>",
                last: "<i class='fa fa-angle-double-right'>",
            }
        },
        pagingType: "full_numbers",
    });
    $('#export_print').on('click', function (e) {
        e.preventDefault();
        datatable.button(0).trigger();
    });

    $('#export_copy').on('click', function (e) {
        e.preventDefault();
        datatable.button(1).trigger();
    });

    $('#export_excel').on('click', function (e) {
        e.preventDefault();
        datatable.button(2).trigger();
    });

    $('#export_csv').on('click', function (e) {
        e.preventDefault();
        datatable.button(3).trigger();
    });

    $('#export_pdf').on('click', function (e) {
        e.preventDefault();
        datatable.button(4).trigger();
    });
    // datatable over
});

function StripePayment() {
    console.log(formData, "formdata");
    var stripe = Stripe($('input[name=stripe_publish_key]').val());

    console.log($('input[name=stripe_publish_key]').val(), "stripe")
    var elements = stripe.elements();
    var cardNumber = elements.create('cardNumber', {
        classes: {
            base: 'card-number required form-control border border-[#D5DAE1] rounded-lg p-3 w-full mt-3'
        }
    });
    var cardExpiry = elements.create('cardExpiry', {
        classes: {
            base: 'card-expiry required form-control border border-[#D5DAE1] rounded-lg p-3 w-full mt-3'
        }
    });
    var cardCvc = elements.create('cardCvc', {
        classes: {
            base: 'card-cvc required form-control border border-[#D5DAE1] rounded-lg p-3 w-full mt-3'
        }
    });

    console.log(cardCvc);
    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');

    $('.btn-submit').bind('click', function (e) {
        var rdBtn = $('input[name="radioBtn"]:checked').val();
        rdBtn = rdBtn.split("/");
        duration = rdBtn[0];
        amount = rdBtn[1];
        subscription_id = rdBtn[2];
        inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
        $errorMessage.addClass('hide');
        console.log('rdvuttin', rdBtn)

        $('.has-error').removeClass('has-error');
        $inputs.each(function (i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        // var month = $('.expiry-date').val().split('/')[0];
        // var year = $('.expiry-date').val().split('/')[1];
        // $('.card-expiry-month').val(month);
        // $('.card-expiry-year').val(year);

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            stripe.createToken(cardNumber).then(stripeResponseHandler);
            // Stripe.setPublishableKey($('input[name=stripe_publish_key]').val());

            //     Stripe.createToken({
            //         number: $('.card-number').val(),
            //         cvc: $('.card-cvc').val(),
            //         exp_month: $('.card-expiry-month').val(),
            //         exp_year: $('.card-expiry-year').val()
            //     }, stripeResponseHandler);
        }
    });

}

function stripeResponseHandler(result) {
    console.log(result, "reponse")
    if (result.error) {
        $('.stripe_alert').show();
        $('.stripe_alert').text(result.error.message);
    }
    else {
        var token = result.token.id;
        if (token) {
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            formData.append('payment_token', token);
            formData.append('payment_type', 'STRIPE');
            subscription();
        } else alert("problem in payment")

    }
}


function subscription() {
    console.log("subscription form data", formData.entries)
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/owner/purchaseSubscription',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.success == true) {
                location.href = base_url + '/owner/dashboard';
            }
            else {
                alert('Oops,Something went wrong');
            }
        },
        error: function (err) {
            alert('Oops,Something went wrong');
        }
    });
}

function paypalPayment() {
    if (currency != 'INR') {
        $('.paypal_row_body').html('');
        paypal_sdk.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    formData.append('payment_token', details.id);
                    formData.append('payment_type', 'PAYPAL');
                    subscription();
                });
            }
        }).render('.paypal_row_body');
    }
    else {
        $('.paypal_row_body').html('INR currency not supported in Paypal');
    }
}



function modelOpen() {
    Swal.fire({
        title: 'Limit Reached!',
        text: "You've reached the maximum parking space limit.\nPlease upgrade your subscription plan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + '/owner/subscription';
        }
    });

}
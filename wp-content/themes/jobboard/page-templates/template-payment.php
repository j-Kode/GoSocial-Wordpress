<?php

/**

 * Template Name: Payment

 *

 * @package WordPress

 * @subpackage Job_Board

 * @since Job Board 1.0

 *

 */


require 'stripe/init.php';
get_header();

if(isset($_GET["pid"]))
    $pid = $_GET["pid"];
else
    $pid = -1;
?>
<script>

    Stripe.setPublishableKey('pk_test_RiVl4Tk5cWWcssUOnWJHbqYG');

    function hireUser()
    {
        var ahguid = getCookie("ahguid");
        var pid = <?php echo $pid; ?>;
        if(!ahguid)
        {
            alert("Your session has expired!")
            window.location="/editcampaign?ID="+pid;
        }
        else{
            jQuery.ajax({
                type: "POST",
                url: "/post.php",
                data: { hireGuid: ahguid },
                success: function (returned) {
                    if(returned)
                        window.location="/editcampaign?ID="+pid;
                    else
                        window.location="/thank-you";
                }
            });
        }

    }

    function stripeResponseHandler(status, response) {
        if (response.error) {
            // re-enable the submit button
            $('.submit-button').removeAttr("disabled");
            // show the errors on the form
            $(".payment-errors").html(response.error.message);
        } else {
            var form$ = $("#payment-form");
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            // and submit
            form$.get(0).submit();
        }
    }
    $(document).ready(function () {
        $("#payment-form").submit(function (event) {
            $('.submit-button').attr("disabled", "disabled");
            var ahguid = getCookie("ahguid");
            var pid = <?php echo $pid; ?>;
            if(pid != -1 && !ahguid)
            {
                alert("Your session has expired");
                window.location="/editcampaign?ID="+pid;
            }
            else {
                $('#ahguid').val(ahguid);
                // createToken returns immediately - the supplied callback submits the form if there are no errors
                Stripe.createToken({
                    number: $('#cardNumber').val(),
                    cvc: $('#cvcNumber').val(),
                    exp_month: $('#expMonth').val(),
                    exp_year: $('#expYear').val()
                }, stripeResponseHandler);
                return false; // submit from callback
            }
        });

    

        $('#amount').keyup(function () {
            var depositAmount = $(this).val() * 1;
            var fee = (depositAmount * 0.03) + 0.3;
            if (depositAmount >= 20 && depositAmount <= 10000) {
                $('#stripeFee').text('$' + fee.toFixed(2));
                $('#depositAmount').text('$' + depositAmount.toFixed(2));
                $('#totalDeposit').text('$' + (depositAmount + fee).toFixed(2));
            }
            else
            {
                $('#stripeFee').text('');
                $('#depositAmount').text('');
                $('#totalDeposit').text('');
            }
        });


        $('#payment-form')
        .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                cc: {
                    selector: '[data-stripe="number"]',
                    validators: {
                        creditCard: {
                            message: 'The credit card number is not valid'
                        },
                        notEmpty: {
                            message: 'The credit card number is required'
                        }
                    }
                },
                fullName: {
                    validators: {
                        notEmpty: {
                            message: 'The full name is required'
                        }
                    }
                },
                expMonth: {
                    selector: '[data-stripe="exp-month"]',
                    validators: {
                        notEmpty: {
                            message: 'The expiration month is required'
                        },
                        digits: {
                            message: 'The expiration month can contain digits only'
                        },
                        callback: {
                            message: 'Expired',
                            callback: function(value, validator) {
                                value = parseInt(value, 10);
                                var year         = validator.getFieldElements('expYear').val(),
                                    currentMonth = new Date().getMonth() + 1,
                                    currentYear  = new Date().getFullYear();
                                if (value < 0 || value > 12) {
                                    return false;
                                }
                                if (year == '') {
                                    return true;
                                }
                                year = parseInt(year, 10);
                                if (year > currentYear || (year == currentYear && value >= currentMonth)) {
                                    validator.updateStatus('expYear', 'VALID');
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                },
                expYear: {
                    selector: '[data-stripe="exp-year"]',
                    validators: {
                        notEmpty: {
                            message: 'The expiration year is required'
                        },
                        digits: {
                            message: 'The expiration year can contain digits only'
                        },
                        callback: {
                            message: 'Expired',
                            callback: function(value, validator) {
                                value = parseInt(value, 10);
                                var month        = validator.getFieldElements('expMonth').val(),
                                    currentMonth = new Date().getMonth() + 1,
                                    currentYear  = new Date().getFullYear();
                                if (value < currentYear || value > currentYear + 100) {
                                    return false;
                                }
                                if (month == '') {
                                    return false;
                                }
                                month = parseInt(month, 10);
                                if (value > currentYear || (value == currentYear && month >= currentMonth)) {
                                    validator.updateStatus('expMonth', 'VALID');
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                },
                amount: {
                    validators: {
                        notEmpty:{
                            message: 'Please enter deposit amount'
                        },
                        digits: {
                            message: 'Deposit amount can only contain digits'
                        },
                        callback: {
                            message: "Deposit amount must be greater than 20 and less than 10000",
                            callback: function (value, validator) {
                                value = parseInt(value, 10);
                                var deposit = validator.getFieldElements('amount').val();
                                if (value < 20 || value > 10000) {
                                    $('#stripeFee').text('');
                                    $('#depositAmount').text('');
                                    $('#totalDeposit').text('');
                                    return false;
                                }
                                if (deposit == '')
                                    return false;
                                if (value >= 20 && value <= 10000) {
                                    validator.updateStatus('amount', 'VALID');
                                    return true;
                                } else
                                    return false;
                            }
                        }
                    }
                },
                cvvNumber: {
                    selector: '[data-stripe="cvc"]',
                    validators: {
                        notEmpty: {
                            message: 'The CVV number is required'
                        },
                        cvv: {
                            message: 'The value is not a valid CVV',
                            creditCardField: 'cc'
                        }
                    }
                }
            }
        })
        .on('success.validator.fv', function(e, data) {
            if (data.field === 'cc' && data.validator === 'creditCard') {
                var $icon = data.element.data('fv.icon');
                // data.result.type can be one of
                // AMERICAN_EXPRESS, DINERS_CLUB, DINERS_CLUB_US, DISCOVER, JCB, LASER,
                // MAESTRO, MASTERCARD, SOLO, UNIONPAY, VISA

                switch (data.result.type) {
                    case 'AMERICAN_EXPRESS':
                        $icon.removeClass().addClass('form-control-feedback fa fa-cc-amex');
                        break;

                    case 'DISCOVER':
                        $icon.removeClass().addClass('form-control-feedback fa fa-cc-discover');
                        break;

                    case 'MASTERCARD':
                    case 'DINERS_CLUB_US':
                        $icon.removeClass().addClass('form-control-feedback fa fa-cc-mastercard');
                        break;

                    case 'VISA':
                        $icon.removeClass().addClass('form-control-feedback fa fa-cc-visa');
                        break;

                    default:
                        $icon.removeClass().addClass('form-control-feedback fa fa-credit-card');
                        break;
                }
            }
        })
        .on('err.field.fv', function(e, data) {
            if (data.field === 'cc') {
                var $icon = data.element.data('fv.icon');
                $icon.removeClass().addClass('form-control-feedback fa fa-times');
            }
        });

        $("#creditCardTab").click(function(){
            // $(".tab").addClass("active"); // instead of this do the below
            $(this).addClass("active");
            $("#gsmCreditTab").removeClass("active");
            $('#gsmCreditPayment').hide();
            $("#creditCardPayment").show();
        })
        $("#gsmCreditTab").click(function(){
            // $(".tab").addClass("active"); // instead of this do the below
            $(this).addClass("active");
            $("#creditCardTab").removeClass("active");
            $("#creditCardPayment").hide();
            $('#gsmCreditPayment').show();

        })
    });


</script>

<div id="page-title-wrapper" style="border-bottom: 1px solid #C5C5C5;">

    <div class="container">
        <div class="col-md-12">
            <h1 class="page-title" style="font-size: 28px; padding-top: 44px">Payments</h1>
        </div>

    </div>
    <div class="container" style="color:#fff;">
        <div class="col-md-12">
            <?php custom_breadcrumbs(); ?>
        </div>
    </div>
    <!-- /.container -->

</div>
<!-- /#page-title -->

<div id="content" style="background-color: #f0f0f0">

    <div class="container">
        <div class="col-sm-7" style="background-color: #fff; border: 1px solid #ddd; padding:0 0 25px 0">
            <div style="padding:0 0 15px 40px;min-height:auto">
                <div class="col-sm-12">
                    <h3>Select Payment Method</h3>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="contact_email">
                            PayPal
                            <input type="radio" name="priceType" value="2" checked="checked" />
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="contact_email">
                            Credit Card
                            <input type="radio" name="priceType" value="2" checked="checked" />
                        </label>
                    </div>
                </div>
            </div>
            <?php
            if($pid != -1)
            {
            $posting = getPostingDetails($pid); ?>
<di class="col-sm-12" style="padding:0; margin: 0 -1px">
            <ul class="nav nav-tabs" id="paymentTabs">
                <li id="creditCardTab" role="presentation" class="active"><a href="#">Credit Card</a> </li>
                <li id="gsmCreditTab" role="presentation"><a href="#">GoSocialMedia Credit</a></li>
            </ul>
</di>
            <div class="col-sm-12" id="gsmCreditPayment" style="display:none">
                <div class="col-sm-12" style="border-bottom:1px solid #ddd;">
                    <h3>Summary</h3>
                    <label style="float:left"><?php echo $posting->postingTitle ?></label>
                    <label style="float:right"><? echo $posting->paymentAmount ?></label>
                </div>
                <div class="col-sm-12" style="padding-top:25px;">
                        <button class="btn btnbooking" style="font-size: 20px!important; float:right" onClick="hireUser()">Use Credit</button>
                </div>
                <div style="clear:both"></div>
            </div>
            <?php }?>
            <div class="col-sm-10" id="creditCardPayment">
                <form id="payment-form" action="<?php echo get_site_url(); ?>/thank-you" method="post">
                    <ul class="" style="list-style-type:none">
                        <!-- /.form-group -->
                        <li>
                            <div class="form-group form-group-lg">
                                <label for="contact_email">Cardholder Name</label>
                                <input type="text" class="form-control" name="fullName" data-stripe="name" />
                            </div>
                            <div class="form-group form-group-lg">
                                <label>Card Number</label>
                                <input type="text" id="cardNumber" class="form-control" data-stripe="number" />
                            </div>
                        </li>
                        <li>
                            <div class="form-group form-group-lg">
                                <label>CVV</label>
                                <input type="text" id="cvvNumber" class="form-control" data-stripe="cvc" />
                            </div>
                        </li>
                        <li style="width:40%">
                            <div class="form-group form-group-lg">
                                <label>Expiration</label>
                                <input type="text" id="expMonth" class="form-control" placeholder="Month" data-stripe="exp-month" />
                                </div>
                        </li>
                        <li style="width:40%">
                            <div class="form-group form-group-lg">
                                <input type="text" id="expYear" class="form-control" placeholder="Year" data-stripe="exp-year" />
                            </div>
                        </li>
                        
                        <li>
                            <div class="form-group form-group-lg">
                                <label>Amount</label>
                                <input id="amount" type="text" name="amount" class="form-control" placeholder="price" />
                                <input id="ahguid" name="ahguid" type="hidden" />
                            </div>
                        </li>

                        <li>

                            <h1>
                                <button class="btn btnbooking" style="font-size: 20px!important; font-weight: 600" data-loading-text="Charging....">Charge Now</button>
                            </h1>

                        </li>
                    </ul>
                </form>


            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <table class="table paymentBreakdown">
                    <tr>
                        <td >
                            Payment Breakdown
                        </td>
                        <td>
                            CAD
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Deposit Amount
                        </td>
                        <td>
                            <label id="depositAmount"></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Deposit Fee
                        </td>
                        <td>
                            <label id="stripeFee"></label>
                        </td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>
                            <label id="totalDeposit"></label>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <!-- /.container -->

</div>
<!-- /#content -->



<?php

jobboard_create_gmaps( 'jobboard-gmaps' );

?>

<?php get_footer(); ?>
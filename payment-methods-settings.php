<?php

session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

include "header.php"; ?>
<html>

<head>
    <title>Payment Gateways & Payment Methods Settings | <?= $settings['app_name'] ?> - Dashboard</title>
</head>
</body>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">

        <h2>Payment Gateways & Methods Settings</h2>
        <?php
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
        $data = $fn->get_settings('payment_methods', true);
        ?>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
        </ol>
        <hr />
    </section>
    <?php if ($permissions['settings']['read'] == 1) { ?>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Payment Methods Settings</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="col-md-4">
                                <form method="post" id="payment_method_settings_form">
                                    <input type="hidden" id="payment_method_settings" name="payment_method_settings" required="" value="1" aria-required="true">
                                    <h5>COD Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="cod_payment_method">COD Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="cod_payment_method_btn" class="js-switch" <?php if (isset($data['cod_payment_method']) && !empty($data['cod_payment_method']) && $data['cod_payment_method'] == '1') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
                                        <input type="hidden" id="cod_payment_method" name="cod_payment_method" value="<?= (isset($data['cod_payment_method']) && !empty($data['cod_payment_method'])) ? $data['cod_payment_method'] : 0; ?>">
                                    </div>
                                    <hr>
                                    <h5>Paypal Payments </h5>
                                    <hr>
                                    <div class="form-group">
                                        <label for="paypal_payment_method">Paypal Payments <small>[ Enable / Disable ] </small></label><br>
                                        <input type="checkbox" id="paypal_payment_method_btn" class="js-switch" <?= (!empty($data['paypal_payment_method']) && $data['paypal_payment_method'] == 1) ? 'checked' : ''; ?>>
                                        <input type="hidden" id="paypal_payment_method" name="paypal_payment_method" value="<?= (isset($data['paypal_payment_method']) && !empty($data['paypal_payment_method'])) ? $data['paypal_payment_method'] : 0; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Payment Mode <small>[ sandbox / live ]</small></label>
                                        <select name="paypal_mode" class="form-control">
                                            <option value="">Select Mode </option>
                                            <option value="sandbox" <?= (isset($data['paypal_mode']) && $data['paypal_mode'] == 'sandbox') ? "selected" : "" ?>>Sandbox ( Testing )</option>
                                            <option value="production" <?= (isset($data['paypal_mode']) && $data['paypal_mode'] == 'production') ? "selected" : "" ?>>Production ( Live )</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Currency Code <small>[ PayPal supported ]</small> <a href="https://developer.paypal.com/docs/api/reference/currency-codes/" target="_BLANK"><i class="fa fa-link"></i></a></label>
                                        <select name="paypal_currency_code" class="form-control">
                                            <option value="">Select Currency Code </option>
                                            <option value="USD" <?= (isset($data['paypal_currency_code']) && $data['paypal_currency_code'] == 'USD') ? "selected" : "" ?>>United States dollar </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_business_email">Paypal Business Email</label>
                                        <input type="text" class="form-control" name="paypal_business_email" value="<?= (isset($data['paypal_business_email'])) ? $data['paypal_business_email'] : '' ?>" placeholder="Paypal Business Email" />
                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_notification_url">Notification URL <small>(Set this as IPN notification URL in you PayPal account)</small></label>
                                        <input type="text" class="form-control" name="paypal_notification_url" value="<?= $protocol . "://" . $_SERVER['SERVER_NAME'] . "/paypal/ipn.php" ?>" placeholder="Paypal IPN notification URL" disabled />
                                    </div>
                                    <hr>




                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    <?php } else { ?>
        <div class="alert alert-danger">You have no permission to view settings</div>
    <?php } ?>
    <div class="separator"> </div>
</div><!-- /.content-wrapper -->
</body>

</html>
<?php include "footer.php"; ?>
<!-- <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('contact_us');
</script> -->
<script type="text/javascript">
    /* paypal change button value */
    var changeCheckbox = document.querySelector('#paypal_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#paypal_payment_method').val(1);
        } else {
            $('#paypal_payment_method').val(0);
        }
    };





    /* COD button value */

    var changeCheckbox = document.querySelector('#cod_payment_method_btn');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#cod_payment_method').val(1);
        } else {
            $('#cod_payment_method').val(0);
        }
    };
</script>
<script>
    $('#payment_method_settings_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'public/db-operation.php',
            data: formData,
            beforeSend: function() {
                $('#btn_update').val('Please wait..').attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                $('#result').html(result);
                $('#result').show().delay(5000).fadeOut();
                $('#btn_update').val('Save').attr('disabled', false);
            }
        });
    });
</script>
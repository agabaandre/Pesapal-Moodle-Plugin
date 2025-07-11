<?php
require_once("lib.php");
require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

global $DB, $CFG;
$amount = $cost;
$localisedcost = format_float($cost, 2, true);
$publicKey = $this->get_config('pubKey');
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- pay now -->
<div style="text-align: center" id="raveView">
    <form>
        <!--v3 api -->
        <script src="https://checkout.pesapal.com/v3.js"></script>


        <button id="payWithRaveBtn" type="button" onClick="payWithRave()">
            <div style="padding-top: 5px; font-weight: bold; color: #171A21">
                <img src="<?php echo $CFG->wwwroot; ?>/enrol/pesapal/pix/icon.png">
                <span style="padding-left: 5px">Click here to Enrol</span>
            </div>
            <img src="<?php echo $CFG->wwwroot; ?>/enrol/pesapal/pix/flwbadge.png"
                 style="width: 200px; padding: 5px 10px 4px;">
            <div style="font-size: 12px; padding-bottom: 5px">
                <?php echo '<b>'.get_string('cost')." is $instance->currency $localisedcost".'</b>';?>
            </div>
        </button>
    </form>
</div>

<form method="post" action="#" id="submitForm">
    <input type="hidden" name="responseData" id="responseData" value=""/>
</form>

<div id="loaderView">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 60 60">
        <path fill="currentColor" fill-rule="evenodd" d="M59.972 26a37.616 37.616 0 0 0-.71-3.455 30.092 30.092 0 0 0-1.446-4.26 30.682 30.682 0 0 0-4.809-7.849 29.483 29.483 0 0 0-7.594-6.389A29.733 29.733 0 0 0 36.292.551C34.63.216 32.956.015 31.255.002a39.08 39.08 0 0 0-3.964.16 30.369 30.369 0 0 0-9.898 2.747 30.882 30.882 0 0 0-7.34 4.848A30.286 30.286 0 0 0 4.4 14.495C2.7 17.28 1.427 20.32.73 23.509c-.562 2.545-.83 5.17-.696 7.782.12 2.532.509 5.063 1.272 7.488a30.823 30.823 0 0 0 1.782 4.5 30.367 30.367 0 0 0 2.464 4.112 30.149 30.149 0 0 0 6.67 6.764 29.967 29.967 0 0 0 18.779 5.827 29.845 29.845 0 0 0 9.724-1.942 29.06 29.06 0 0 0 8.237-4.862c1.232-1.045 2.33-2.224 3.362-3.47 1.045-1.259 1.982-2.585 2.76-4.018a29.445 29.445 0 0 0 1.714-3.817c.24-.643.469-1.286.656-1.956.2-.71.348-1.446.482-2.17.201-1.138.281-2.317.174-3.469-.093.51-.174 1.005-.294 1.5a14.602 14.602 0 0 1-.55 1.688c-.428 1.165-.964 2.29-1.473 3.416a36.09 36.09 0 0 1-2.25 4.125 28.98 28.98 0 0 1-1.353 1.996c-.482.643-1.031 1.259-1.58 1.862a23.257 23.257 0 0 1-3.617 3.268 26.913 26.913 0 0 1-4.3 2.585c-3.026 1.473-6.335 2.357-9.683 2.652a27.72 27.72 0 0 1-10.22-1.018 27.424 27.424 0 0 1-8.72-4.393 27.441 27.441 0 0 1-6.455-6.939c-1.808-2.719-3.054-5.786-3.737-8.987a26.897 26.897 0 0 1-.402-2.532c-.08-.723-.147-1.46-.174-2.196a26.23 26.23 0 0 1 .281-4.581c.496-3.295 1.568-6.47 3.228-9.363a26.813 26.813 0 0 1 5.64-6.885 26.563 26.563 0 0 1 7.607-4.701 25.887 25.887 0 0 1 5.01-1.46 24.97 24.97 0 0 1 2.611-.362c.429-.04.844-.04 1.273-.08.174 0 .348.013.522.013 2.906-.053 5.826.322 8.599 1.192a25.15 25.15 0 0 1 8.237 4.42 25.798 25.798 0 0 1 6.295 7.475 27.988 27.988 0 0 1 2.934 7.795c.134.63.24 1.26.348 1.889a2.11 2.11 0 0 0 .91 1.433c1.045.696 2.505.228 3.014-.897.174-.389.228-.804.161-1.193z"></path>
    </svg>
    <!--    <img style="width: 50px;" src="--><?php //echo $CFG->wwwroot; ?><!--/enrol/pesapal/pix/flw_spinner.svg"/>-->
</div>
<!-- custom styling -->
<style>
    #payWithRaveBtn {
        min-width: 220px;
        border: 1px solid #dee0eb;
        border-radius: 2px;
    }

    #loaderView {
        text-align: center;
        color: #f5a623;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    #loaderView > svg {
        animation: rotate 0.5s infinite linear;
    }

    #payWithRaveBtn:hover {
        background: whitesmoke;
    }
    .swal-button {
        padding: 7px 19px;
        border-radius: 2px;
        background-color: #f5a623;
        color: #0f1c70;
        font-size: 14px;
    }
    .swal-overlay {
        background-color: rgba(245, 166, 35, 0.45);
    }
    .swal-modal {
        border-radius: 0;
    }

</style>

<?php
if (isset($_POST['responseData'])) {
    $data = json_decode($_POST['responseData'], true);
    $paid_amount = $data['amount'];
    $app_fee = $data['appfee'];
    $payment_status = $data['status']; // successful
    $response_code = (int)$data['chargeResponseCode'];
    $response_reason_code = $response_code;
    $response_reason_text = $data['chargeResponseMessage'];
    $auth_mode = $data['authModelUsed'];
    $trans_id = $data['id'];
    $method = $data['paymentType'];
    $account_number = $data['raveRef'];
    $full_name = $data['customer']['fullName'];
    $phone = $data['customer']['phone'];
    $email = $data['customer']['email'];
    $order_ref = $data['orderRef'];
    $flw_ref = $data['flwRef'];
    $currency = $data['currency'];
    $tx_ref = $data['txRef'];

    $enrolpesapal = new stdClass();
    $enrolpesapal->amount = $paid_amount;
    $enrolpesapal->app_fee = $app_fee;
    $enrolpesapal->payment_status = $payment_status;
    $enrolpesapal->response_code = $response_code;
    $enrolpesapal->response_reason_code = $response_reason_code;
    $enrolpesapal->response_reason_text = $response_reason_text;
    $enrolpesapal->auth_mode = $auth_mode;
    $enrolpesapal->trans_id = $trans_id;
    $enrolpesapal->method = $method;
    $enrolpesapal->account_number = $account_number;
    $enrolpesapal->full_name = $full_name;
    $enrolpesapal->phone = $phone;
    $enrolpesapal->email = $email;
    $enrolpesapal->order_ref = $order_ref;
    $enrolpesapal->flw_ref = $flw_ref;
    $enrolpesapal->currency = $currency;
    $enrolpesapal->tx_ref = $tx_ref;
    $enrolpesapal->timeupdated = time();
    $enrolpesapal->courseid = $instance->courseid;
    $enrolpesapal->userid = $USER->id;
    $enrolpesapal->item_name = $coursefullname;
    $enrolpesapal->instanceid = $instance->id;

    $ret1 = $DB->insert_record("enrol_pesapal", $enrolpesapal, true);

    // try and enrol student
    if (!$plugininstance = $DB->get_record("enrol", array("id" => $instance->id, "status" => 0))) {
        echo "
          <script>
              document.getElementById('loaderView').style.display = 'none'
          </script>
        ";
        print_error("Not a valid instance id");
        die;
    }

    if ($plugininstance->enrolperiod) {
        $timestart = time();
        $timeend = $timestart + $plugininstance->enrolperiod;
    } else {
        $timestart = 0;
        $timeend = 0;
    }
    $plugin = enrol_get_plugin('pesapal');
    /* Enrol User */
    if ((float)$paid_amount >= (float)$cost) {
        $plugin->enrol_user($plugininstance, $USER->id, $plugininstance->roleid, $timestart, $timeend);
        echo "<script type='text/javascript'>
                swal('Success', 'Payment successful', 'success');
               window.location.href='$CFG->wwwroot/course/view.php?id=$instance->courseid';
               </script>";
    } else {
        echo "<script type='text/javascript'>
                swal('Error', 'Payment failed. Try again!', 'error');
               </script>";
    }
}
?>


<script>
    document.getElementById('loaderView').style.display = 'none'

    function payWithRave() {
        const url = window.location.href;

        // v3 inline
        const raveCheckout = PesapalCheckout({
            public_key: '<?php echo $publicKey; ?>',
            tx_ref: new Date().getTime().toString(),
            amount: <?php echo $amount; ?>,
            currency: "<?php echo $instance->currency; ?>",
            country: "<?php echo enrol_get_plugin('pesapal')->get_currency_countries($instance->currency); ?>",
            customer: {
                email: "<?php echo $USER->email;?>",
                name: "<?php echo "$USER->firstname $USER->lastname";?>",
            },

            onclose: function () {
                swal('', 'Transaction was Cancelled', 'error', {
                    button: "Close"
                });
            },
            callback: function (response) {
                raveCheckout.close();
                console.log(response);
                document.getElementById('raveView').style.display = 'none'
                document.getElementById('loaderView').style.display = 'block'

                if (response.status === 'successful') {
                    swal({
                        title: 'Success',
                        text: 'Payment successful',
                        icon: "success",
                        button: {
                            text: "Proceed to Course",
                            value: true,
                            visible: true
                        }
                    }).then((value) => {
                        document.getElementById('responseData').value = JSON.stringify(response)
                        document.getElementById('submitForm').submit()

                    });
                } else {
                    swal('Error', response.data.response_parsed.message, 'error', {
                        button: "Close"
                    });
                }
            }
        });
    }
</script>

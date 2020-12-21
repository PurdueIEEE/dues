<?php

    // POST to receive data here:
    //define('LISTS_STANDALONE', true);
    include 'secrets.php';
    include 'DirectoryServices/lists.php';
    if (isset($_POST['method'])) {
        $params = $_POST;
        if (!isset($params['method']))
            return http_return(["error" => "no method given"], 400);
        $method = $params['method'];
        unset($params['method']);

        // Ad-hoc dues verification.
        if ($method === 'dues') {
            $db = mysqli_connect("localhost", "root", MYSQL_SECRET, $dbname);
            if (!$db)
                return http_return(["error" => "failed to connect to mysql"], 400);

            $email = mysqli_real_escape_string($db, trim($params['email']));
            $query = "SELECT * FROM `2021-2022` WHERE email = '$email'";
            $result = $db->query($query);
            return http_return(["result" => $result->fetch_array(MYSQLI_ASSOC)]);
        }

        try {
            return http_return(["result" => dynamic_invoke("Lists::$method", $params, function($x) {
                return http_return(["error" => "invalid parameter $x"], 400);
            })]);
        } catch (ReflectionException $e) {
            return http_return(["error" => "no such method"], 400);
        }
    }
?>
<?php
    // GET to show form here:
    $title = "Subscriptions";
    include 'header.php';
?>
    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a>
                </li>
                <li class="active"><?php echo $title ?></li>
            </ol>
        </div>
    </div>

    <!-- Well -->
    <div class="well">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center text-dark text-primary" style="padding-bottom: 24px;">
                Select a person by their email address to display or update their subscriptions.
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="input-group form-group text-dark">
                    <span class="input-group-addon" id="sizing-addon1">@</span>
                    <input class="form-control input-lg" id="email-input" placeholder="Email..." type="email">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="bottom" title="Display the person's subscriptions." onclick="get()">FIND</button>
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="bottom" title="Update the person's subscriptions." onclick="set()">UPDATE</button>
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="bottom" title="Switch to another person." onclick="reset()">EXIT</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="row" id="success-box" style="display: none">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="alert alert-success"></div>
            </div>
        </div>
        <div class="row" id="error-box" style="display: none">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="alert alert-danger"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="form-group text-dark">
                    <div class="checkbox">
                        <?php foreach (Lists::all(true) as $key => $value): ?>
                        <label class="btn btn-primary" style="width:100%;">
                            <input class="dynamic-box form-control" type="checkbox" autocomplete="off" name="list[]" value="<?php echo $key; ?>" id="<?php echo $key; ?>">
                            <?php echo $value; ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.well -->

    <!-- Script -->
    <script type="application/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        String.prototype.capitalize = function() {
            return this.replace(/(^|\s)([a-z])/g, function(m, p1, p2) {
                return p1 + p2.toUpperCase();
            });
        };
        $(".dynamic-box").prop("disabled", true);

        function verify_email() {
            var email = $("#email-input").val();
            var error = "";
            var emailPattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (!emailPattern.test(email)) {
                error += "Please make sure the email is valid. <BR>";
            }
            if (error.length > 0) {
                $(".alert-danger").html(error);
                $("#error-box").show();
                return null;
            }
            return email;
        }

        function get() {
            var email = verify_email()
            if (email == null) return;

            $(".dynamic-box").prop("disabled", false);
            $.post("subs.php", {
                method: 'find',
                email: email
            }, function(data) {
                $.post("subs.php", {
                    method: 'info',
                    email: email
                }, function(data2) {
                    var name = email;
                    if (data2.result.length != 0) {
                        name = data2.result.cn[0].capitalize();
                    }
                    $.post("subs.php", {
                        method: 'dues',
                        email: email
                    }, function(data3) {
                        if (data3.result != null) {
                            name += " (Dues Paid)";
                        } else {
                            name += " (Dues UNPAID)";
                        }

                        $("#email-input").prop("disabled", true);
                        $(".dynamic-box").prop("checked", false);
                        for (var i = 0; i < data.result.length; i++) {
                            $("#" + data.result[i]).prop("checked", true);
                        }
                        $(".alert-success").html("Displaying subscriptions for <b>" + name + "</b>:");
                        $("#success-box").show();
                    });
                });
            });
        }
        function set() {
            var email = verify_email()
            if (email == null) return;

            $(".dynamic-box").prop("disabled", false);
            $.post("subs.php", {
                method: 'find',
                email: email
            }, function(data) {
                $.post("subs.php", {
                    method: 'info',
                    email: email
                }, function(data2) {
                    var name = email;
                    if (data2.result.length != 0) {
                        name = data2.result.cn[0].capitalize();
                    }

                    /*
                    $("#email-input").prop("disabled", true);
                    $(".dynamic-box").prop("checked", false);
                    for (var i = 0; i < data.result.length; i++) {
                        $("#" + data.result[i]).prop("checked", true);
                    }
                    */

                    $(".alert-success").html("");
                    $("#success-box").hide();
                    $(".alert-danger").html("This is currently unimplemented. <b>" + name + "</b>:");
                    $("#error-box").show();
                });
            });
        }

        // Reset the locked-in email and clear all checkboxes.
        function reset() {
            var email = verify_email()
            if (email == null) return;

            $("#email-input").prop("disabled", false);
            $("#email-input").val("");
            $(".dynamic-box").prop("checked", false);
            $(".dynamic-box").prop("disabled", true);

            $(".alert-success").html("");
            $("#success-box").hide();
            $(".alert-danger").html("");
            $("#error-box").hide();
        }
    </script>
<?php
    include 'footer.php';
?>

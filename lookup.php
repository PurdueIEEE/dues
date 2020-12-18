<?php
    include 'secrets.php';

    if (isset($_POST['id']) || isset($_POST['email'])) {
        $db = mysqli_connect("localhost", "root", MYSQL_SECRET, "pieee");

        if (!$db) {
            die('<p class="error">Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error()."</p>");
        }

        if (isset($_POST['id'])) {
            $id = hash('sha512', $_POST['id']);

            $query = "SELECT * FROM `2020-2021` WHERE id='".$id."'";

            $results = $db->query($query);

            if ($results) {
                $row = mysqli_fetch_array($results);
                if (count($row) == 0) {
                    exit();
                }
                printf("Name: %s<BR>Email: %s<BR>", $row['name'], $row['email']);
                exit();
            }
        } else if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
            if (empty($email)) {
                exit();
            }

            $email = mysqli_real_escape_string($db, $email);

            $query = "SELECT * FROM `2020-2021` WHERE email LIKE '%$email%'";

            $results = $db->query($query);

            if ($results) {
                while($row = mysqli_fetch_array($results)) {
                    printf("Name: %s<BR>Email: %s<BR>", $row['name'], $row['email']);
                }
                exit();
            }
        }
    }
?>

<?php
    $title = "Lookup Members";
    include 'header.php';
?>

    <style>
        .error {
            border-color: red;
        }
    </style>

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
    <!-- /.row -->

    <!-- Well -->
    <div class="well">

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="form-group text-dark">
                    <label for="inputlg" style="font-size: 45px;">Enter ID:</label>
                    <input class="form-control input-lg" id="id-input" type="password" onkeyup="checkId(event)">
                    <span id="id-error-lookup" class="help-block" style="color: red; display: none">Could not find member by ID</span>
                    <span id="id-error-match" class="help-block" style="color: red; display: none">ID text does not match ID format</span>
                </div>

                <div class="form-group text-dark">
                    <label for="inputlg" style="font-size: 45px;">Search by Email:</label>
                    <input class="form-control input-lg" id="email-input" type="text" onkeyup="checkEmail(event)">
                    <span id="email-error" class="help-block" style="color: red; display: none">Could not find member by Email</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div id ="data" class="col-lg-8 col-lg-offset-2 text-center text-dark">
            </div>
        </div>

    </div>
    <!-- /.well -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <script type="application/javascript">

        function checkId(event) {
            if (event.which === 13) {
                var id = $('#id-input').val();

                id = id.match(/00[0-9]{8}/gm);
                if (id != null) {
                    $.post("lookup.php", {
                        id: id[0]
                    }, function(ret) {
                        if (ret.trim()) {
                            addData(ret)
                        } else {
                            $("#id-input").addClass("error")
                            $("#id-error-lookup").show()
                        }
                    });
                } else {
                    $("#id-input").addClass("error")
                    $("#id-error-match").show()
                    // do something to say they don't exist
                }

                $('#id-input').val("");
            } else {
                $("#id-error-lookup").hide()
                $("#id-error-match").hide()
            }
        }

        function checkEmail(event) {
            if (event.which === 13) {
                var email = $('#email-input').val();
                $.post("lookup.php", {
                    email: email
                }, function(ret) {
                    if (ret.trim()) {
                        addData(ret)
                    } else {
                        $("#email-input").addClass("error")
                        $("#email-error").show()
                    }
                });
            } else {
                $("#email-input").removeClass("error")
                $("#email-error").hide()
            }
        }

        function addData(data) {
            $("#data").prepend(`<h2>${data}</h2>`)
        }
    </script>

<?php
    include 'footer.php';
?>

<?php

    if (isset($_POST['id']) || isset($_POST['email'])) {
        $db = mysqli_connect("localhost", "root", "password1", "pieee");

        if (!$db) {
            die('<p class="error">Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error()."</p>");
        }

        if (isset($_POST['id'])) {
            $id = hash('sha512', $_POST['id']);

            $query = "SELECT * FROM `membership` WHERE id='".$id."'";

            $results = $db->query($query);

            if ($results) {
                $row = mysqli_fetch_array($results);
                if (count($row) == 0) {
                    exit();
                }
                printf("Name: %s<BR>Email: %s<BR>", $row['name'], $row['email']);
                exit();
            }
        } else {
            $email = $_POST['email'];

            $email = mysqli_real_escape_string($db, trim($email));
            $query = "SELECT * FROM `membership` WHERE email LIKE '%$email%'";

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

    <!-- Page Heading/Breadcrumbs -->
    <div class="row">
        <div class="col-lg-12">
        <h1 class="page-header"><?php echo $title ?></h1>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a>
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
                    <input class="form-control input-lg" id="id-input" type="text" onkeyup="checkId(event)">
                </div>

                <div class="form-group text-dark">
                    <label for="inputlg" style="font-size: 45px;">Search by Email:</label>
                    <input class="form-control input-lg" id="email-input" type="text" onkeyup="checkEmail(event)">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center text-dark">
                <h2 id="id-parsed"></h2>
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
                        $('#id-parsed').html(ret);
                    });

                } else {
                    $('#id-parsed').html("");
                }
                $('#id-input').val("");
            }
        }

        function checkEmail(event) {
            if (event.which === 13) {
                var email = $('#email-input').val();
                $.post("lookup.php", {
                    email: email
                }, function(ret) {
                    $('#id-parsed').html(ret);
                });
            } else {
                $('#id-parsed').html("");
            }
        }
    </script>

<?php
    include 'footer.php';
?>

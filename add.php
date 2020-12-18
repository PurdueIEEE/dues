<?php
    include 'secrets.php';

    if (isset($_POST['id'])) {
        $db = mysqli_connect("localhost", "root", MYSQL_SECRET, "pieee");

        if (!$db) {
            die('<p class="error">Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error()."</p>");
        }

        $name      = $db->real_escape_string($_POST['name']);
        $email     = $db->real_escape_string($_POST['email']);
        $enterer   = $db->real_escape_string($_POST['enterer']);

        $id = hash('sha512', $_POST['id']);
        $committee   = $db->real_escape_string($_POST['committee']);

        $query = "INSERT INTO `2020-2021` (name, email, id, enterer, committee) VALUES ('$name', '$email', '$id', '$enterer', '$committee')";

        $db->query($query);

        exit();
    }

?>


<?php
    $title = "Add Members";
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

        <div id="error-box" class="row" style="display: none">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="alert alert-danger"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">

                <div class="form-group text-dark">
                    <label for="name-input" style="font-size: 45px;">Name:</label>
                    <input class="form-control input-lg" id="name-input" type="text">
                </div>

                <div class="form-group text-dark">
                    <label for="email-input" style="font-size: 45px;">Email:</label>
                    <input class="form-control input-lg" id="email-input" type="email">
                </div>

                <div class="form-group text-dark">
                    <label for="id-input" style="font-size: 45px;">Purdue ID:</label>
                    <input class="form-control input-lg" id="id-input" type="password">
                </div>

                <div class="form-group text-dark">
                    <label for="id-input" style="font-size: 45px;">Committee: </label>
            <select class="form-control input-lg" id="id-committee">
            <option value="None">None</option>
            <option value="Aerial">Aerial</option>
            <option value="Computer Society">Computer Society</option>
            <option value="EMBS">EMBS</option>
            <option value="MTT-S">MTT-S</option>
            <option value="Racing">Racing</option>
            <option value="ROV">ROV</option>
            </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="form-group text-dark">
                    <label for="enterer-input" style="font-size: 45px;">Who is entering this info? (Only for officers to fill in)</label>
                    <input class="form-control input-lg" id="enterer-input" type="text">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 text-center" style="padding-top: 25px">
                <button type="button" class="btn btn-primary btn-lg" onclick="submit()">Submit</button>
            </div>
        </div>


    </div>
    <!-- /.well -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <script type="application/javascript">

        function submit() {

            var name      = $("#name-input").val();
            var email     = $("#email-input").val();
            var id        = $("#id-input").val();
            var enterer   = $("#enterer-input").val();
            var committee = $("#id-committee").val();

            var error = "";

            var namePattern  = /^([a-zA-Z0-9-_'.]+\s*){2,}$/g;
            var emailPattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

            if (!namePattern.test(name)) {
                error = "Please make sure the name only contains a-z, 0-9, ., ', -, or _. <BR>";
            }

            if (!emailPattern.test(email)) {
                error += "Please make sure the email is valid. <BR>";
            }

            id = id.match(/00[0-9]{8}/);

            if (id == null) {
                error += "Please enter a valid id (10 numbers). <BR>";
            } else {
                id = id[0];
            }

            if (enterer.length < 1) {
                error += "Please make sure you specify who is entering this information. <BR>";
            }


            if (error.length > 0) {
                $(".alert-danger").html(error);
                $("#error-box").show();
            } else {
                $.post("add.php", {
                    name: name,
                    email: email,
                    id: id,
                    enterer: enterer,
                    committee: committee
                }, function(data) {
                     $("#name-input").val("");
                     $("#email-input").val("");
                     $("#id-input").val("");
                     $("#id-committee").val("None");
                });
            }
        }
    </script>

    <?php
        include 'footer.php';
    ?>

<?php
    include 'secrets.php';

    $db = mysqli_connect("localhost", "root", MYSQL_SECRET, "pieee");

    if (!$db) {
        die('<p class="error">Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error()."</p>");
    }
    if(isset($_POST['year'])) {
        $year = $_POST['year'];
        $query = "SELECT * FROM `". $year ."` ORDER BY `". $year ."`.`name` ASC";
        $query_count = "SELECT count(*) as num_people FROM `". $year ."`";

        $results = $db->query($query);
        $results_count = $db->query($query_count);
    }

?>


<?php
    $title = "Treasurer";
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

            <select id="year" name="year">
                <option value="2020-2021">Select Year</option>
                <option value="2020-2021">2020 - 2021</option>
                <option value="2019-2020">2019 - 2020</option>
                <option value="2018-2019">2018 - 2019</option>
                <option value="2017-2018">2017 - 2018</option>
            </select>
            <br />

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

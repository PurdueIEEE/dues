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
    $title = "View Members";
    include 'header.php';
?>
    <head>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    </head>

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
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
            <form method="post" action="" class="text-dark">
                <select name="year" class="form-control" style="width: 200px; display: inline-block;">
                    <option value="2020-2021">Select Year</option>
                    <option value="2020-2021">2020 - 2021</option>
                    <option value="2019-2020">2019 - 2020</option>
                    <option value="2018-2019">2018 - 2019</option>
                    <option value="2017-2018">2017 - 2018</option>
                </select>
                <input type="submit" class="btn btn-default" value="Go!"/>
            </form>
            <br />
            </div>
        </div>
    </div>

    <!-- Well -->
    <div class="well text-dark">

        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">

                <?php
                    $count = mysqli_fetch_array($results_count);
                    echo "<h1><center>$count[num_people] Members</center></h1>";
                ?>

                <table class="table table-striped" id="tblMembers" class="display">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Enterer</th>
                            <th>Committee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($row = mysqli_fetch_array($results)) {
                                $name = $row['name'];
                                $email = $row['email'];
                                $enterer = $row['enterer'];
                                $committee = $row['committee'];

                                echo "<tr>";
                                    echo "<td>$name</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$enterer</td>";
                                    echo "<td>$committee</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- /.well -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <script type="application/javascript">

    </script>

    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
    function selectyear() {
        var year = document.getElementById('year').value;
        $.post("view.php", {
            year: year
        }, function(ret) {});
    }

    $(document).ready(function() {
        $('#tblMembers').DataTable();
    });
    </script>

<?php
    include 'footer.php';
?>

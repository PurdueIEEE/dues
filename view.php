<?php 
        $db = mysqli_connect("localhost", "root", "password1", "pieee");

        if (!$db) {
            die('<p class="error">Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error()."</p>");
        }

        $query = "SELECT * FROM `membership` ORDER BY `membership`.`name` ASC";

        $results = $db->query($query);

?>



<?php 
    $title = "View Members";
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
    <div class="well text-dark">

        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Enterer</th>
                      </tr>
                    </thead>
                    <tbody> 
                    <?php
                        while($row = mysqli_fetch_array($results)) {
                            $name = $row['name'];
                            $email = $row['email'];
                            $enterer = $row['enterer'];

                            echo "<tr>";
                                echo "<td>$name</td>";
                                echo "<td>$email</td>";
                                echo "<td>$enterer</td>";
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

<?php 
    include 'footer.php';
?>

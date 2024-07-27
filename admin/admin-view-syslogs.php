<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php'); ?>

<body id="page-top">

    <?php include("vendor/inc/nav.php"); ?>

    <div id="wrapper">
        <?php include('vendor/inc/sidebar.php'); ?>
        <div id="content-wrapper">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">System Logs</li>
                </ol>

                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-key"></i>
                        System Logs
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Email</th>
                                        <th>Event Name</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Establish a database connection
                                    $mysqli = new mysqli("localhost", "root", "", "vehiclebookings");

                                    // Check connection
                                    if ($mysqli->connect_error) {
                                        die("Connection failed: " . $mysqli->connect_error);
                                    }

                                    $ret = "SELECT * FROM `tms_syslogs`";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row->u_email; ?></td>
                                            <td><?php echo $row->event_name; ?></td>
                                            <td><?php echo $row->action; ?></td>
                                            <td><?php echo $row->booking_time; ?></td>
                                        </tr>
                                    <?php $cnt = $cnt + 1; } 
                                    
                                    // Close the database connection
                                    $mysqli->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">
                        <?php
                        date_default_timezone_set("Asia/Manila");
                        echo "The time is " . date("h:i:sa");
                        ?>
                    </div>
                </div>
            </div>

            <?php include("vendor/inc/footer.php"); ?>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="admin-logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="vendor/js/sb-admin.min.js"></script>
    <script src="vendor/js/demo/datatables-demo.js"></script>

</body>

</html>

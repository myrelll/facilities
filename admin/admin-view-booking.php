<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<style>
    @media print {
      body * {
        visibility: hidden;
      }
      .printArea, .printArea * {
        visibility: visible;
      }
      .printArea {
        position: absolute;
        left: 0;
        top: 0;
      }
    }

    .form-inline .form-control, .form-inline .btn {
      display: inline-block;
      width: auto;
      vertical-align: middle;
      height: calc(1.5em + 0.75rem + 2px);
    }
  </style>

<body id="page-top">

 <?php include("vendor/inc/nav.php");?>


  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="admin-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">View Bookings</li>
        </ol>

        <!-- DataTables Example -->
       <?php
        $facility_filter = isset($_GET['facility']) ? $_GET['facility'] : '';
        $month_filter = isset($_GET['month']) ? $_GET['month'] : '';
        $year_filter = isset($_GET['year']) ? $_GET['year'] : '';

        // Construct the SQL query with filters
        $query = "SELECT e.id, v.v_name, e.reserved_by, e.name, e.start, e.end 
                  FROM events e 
                  JOIN tms_vehicle v ON e.facility_id = v.v_id 
                  WHERE 1";

        if ($facility_filter) {
            $query .= " AND v.v_id = ?";
        }

        if ($month_filter) {
            $query .= " AND MONTH(e.start) = ?";
        }

        if ($year_filter) {
            $query .= " AND YEAR(e.start) = ?";
        }

        $stmt = $mysqli->prepare($query);

        // Bind parameters if filters are set
        if ($facility_filter && $month_filter && $year_filter) {
            $stmt->bind_param("iii", $facility_filter, $month_filter, $year_filter);
        } elseif ($facility_filter && $month_filter) {
            $stmt->bind_param("ii", $facility_filter, $month_filter);
        } elseif ($facility_filter && $year_filter) {
            $stmt->bind_param("ii", $facility_filter, $year_filter);
        } elseif ($month_filter && $year_filter) {
            $stmt->bind_param("ii", $month_filter, $year_filter);
        } elseif ($facility_filter) {
            $stmt->bind_param("i", $facility_filter);
        } elseif ($month_filter) {
            $stmt->bind_param("i", $month_filter);
        } elseif ($year_filter) {
            $stmt->bind_param("i", $year_filter);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        ?>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-filter"></i>
            Filter Bookings
          </div>
          <div class="card-body">
            <form method="GET" action="" class="form-inline">
              <div class="form-group mr-2">
                <label for="facility" class="mr-2">Facility</label>
                <select name="facility" id="facility" class="form-control">
                  <option value="">All</option>
                  <?php
                  // Fetch the list of facilities from the database
                  $facility_query = "SELECT v_id, v_name FROM tms_vehicle";
                  $facility_result = $mysqli->query($facility_query);
                  while ($facility_row = $facility_result->fetch_assoc()) {
                    echo "<option value='{$facility_row['v_id']}'>{$facility_row['v_name']}</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group mr-2">
                <label for="month" class="mr-2">Month</label>
                <select name="month" id="month" class="form-control">
                  <option value="">All</option>
                  <?php
                  // Generate month options
                  for ($m = 1; $m <= 12; $m++) {
                    $monthName = date("F", mktime(0, 0, 0, $m, 1));
                    echo "<option value='$m'>$monthName</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group mr-2">
                <label for="year" class="mr-2">Year</label>
                <select name="year" id="year" class="form-control">
                  <option value="">All</option>
                  <?php
                  // Generate year options
                  for ($y = 2020; $y <= date("Y"); $y++) {
                    echo "<option value='$y'>$y</option>";
                  }
                  ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary mr-2">Filter</button>
              <button type="button" class="btn btn-secondary" onclick="printTable()">Print</button>
            </form>
          </div>
        </div>

        <div class="card mb-3 printArea">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Bookings
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Reserved By</th>
                    <th>Facility</th>
                    <th>Event Description</th>
                    <th>Event Start</th>
                    <th>Event End</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                  ?>
                  <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $row->reserved_by; ?></td>
                    <td><?php echo $row->v_name; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->start; ?></td>
                    <td><?php echo $row->end; ?></td>
                  </tr>
                  <?php
                    $cnt++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Asia/Tokyo");
              echo "Generated : " . date("h:i:sa");
            ?>
          </div>
        </div>

      </div>

      <!-- Sticky Footer -->
      <?php include("vendor/inc/footer.php");?>
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
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

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="vendor/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="vendor/js/demo/datatables-demo.js"></script>
  <script>
    function printTable() {
      window.print();
    }
  </script>

</body>

</html>

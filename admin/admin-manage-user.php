<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];

  // Delete User
  if(isset($_GET['del'])) {
      $user_id = intval($_GET['del']);
      $query = "DELETE FROM tms_user WHERE u_id=?";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param('i', $user_id);
      if($stmt->execute()) {
          $succ = "User Deleted";
      } else {
          $err = "Failed to Delete User";
      }
  }
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">
 <!-- Navigation -->
 <?php include("vendor/inc/nav.php");?>
 <!-- Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>
    <!-- Content -->
    <div id="content-wrapper">
      <div class="container-fluid">

        <?php if(isset($succ)) {?>
        <script>
            setTimeout(function () { swal("Success!","<?php echo $succ;?>!","success"); }, 100);
        </script>
        <?php } ?>

        <?php if(isset($err)) {?>
        <script>
            setTimeout(function () { swal("Failed!","<?php echo $err;?>!","error"); }, 100);
        </script>
        <?php } ?>

        <!-- Breadcrumbs and Add User Button -->
        <div class="row mb-3">
          <div class="col">
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="#">User</a>
              </li>
              <li class="breadcrumb-item active">View Users</li>
            </ol>
          </div>
          <div class="col-auto">
            <!-- Add User Button -->
            <a href="admin-add-driver.php" class="btn btn-primary">Add User</a>
          </div>
        </div>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-users"></i>
            Registered Users
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <?php
                    $ret="SELECT * FROM tms_user WHERE u_category = 'Admin' ORDER BY RAND() LIMIT 1000"; 
                    $stmt= $mysqli->prepare($ret);
                    $stmt->execute();
                    $res=$stmt->get_result();
                    $cnt=1;
                    while($row=$res->fetch_object()) {
                ?>
                <tbody>
                  <tr>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $row->u_fname;?> <?php echo $row->u_lname;?></td>
                    <td><?php echo $row->u_phone;?></td>
                    <td><?php echo $row->u_addr;?></td>
                    <td><?php echo $row->u_email;?></td>
                    <td>
                      <a href="admin-manage-single-usr.php?u_id=<?php echo $row->u_id;?>" class="badge badge-success"><i class="fa fa-user-edit"></i> Update</a>
                      <a href="admin-manage-user.php?del=<?php echo $row->u_id;?>" class="badge badge-danger"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                  </tr>
                </tbody>
                <?php $cnt = $cnt+1; }?>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Africa/Nairobi");
              echo "Generated : " . date("h:i:sa");
            ?>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php include("vendor/inc/footer.php");?>
    </div>
  </div>
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
  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="vendor/js/sb-admin.min.js"></script>
  <script src="vendor/js/demo/datatables-demo.js"></script>
</body>
</html>

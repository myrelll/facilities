<!DOCTYPE html>
<html lang="en">
<?php
  session_start();
include('admin/vendor/inc/config.php'); //dytoy jy connection agaramid ti connection ti database;
include("vendor/inc/head.php");
include 'Calendar.php';
$calendar = new Calendar();?>


<body>

  <!-- Navigation -->
  <?php include("vendor/inc/nav.php");?>

  <!-- Page Content -->
  <div class="container">
  
  <?php

      $ret="SELECT * FROM tms_vehicle"; //get all feedbacks
      $stmt= $mysqli->prepare($ret) ;
      $stmt->execute() ;//ok
      $res=$stmt->get_result();
      {
    ?>

    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">About
    </h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="index.php">Home</a>
      </li>
      <li class="breadcrumb-item active">View Schedule</li>
    </ol>

    
    <!-- Intro Content -->
    <div class="row">
      <div class="col-lg-6">
        <img class="img-fluid rounded mb-4" src="vendor/img/placeholder-1.png" alt="">
      </div>
      <div class="col-lg-6">
        <h2>About Us</h2>
        <p>
            Vehicle Booking System is a system build to deal with problems faced in
            transportation. Major problems faced in this transport sector are such as task allocation,
            tracking of vehicles, assigning routes, payment, booking order, delivery report, generating
            transactions receipt, overworking of the employees, security of goods, users, drivers and also
            maintenance of the vehicles in terms of finance and time consumption.Vehicle Booking System 
            will be able to solve major problems such as task allocation where by a GPRS will
            be mounted in all vehicles and cab to ensure that each and every vehicle and cab is traced and
            assigned a tasks at a time, also keep track of all financial reports and expenses incurred in the
            organization by ensuring that all payments are made through credit/debit card or pay bill. The
            system will be able to have details of all the customers who has booked vehicle and also view
            the vehicles with tasks at hand and those without.
        </p>
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
  <?php }?>
  <!-- Footer -->
<?php include("vendor/inc/footer.php");?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>

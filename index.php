<?php
session_start();
include('admin/vendor/inc/config.php');
include("vendor/inc/head.php");
include("vendor/inc/nav.php");

$v_id = $_GET['v_id'] ?? '';

$v_name = '';
$custodian = '';
$custodianEmail = '';

if (!empty($v_id)) {
    $stmt = $mysqli->prepare("SELECT v_name, v_driver FROM tms_vehicle WHERE v_id = ?");
    $stmt->bind_param("i", $v_id);
    $stmt->execute();
    $stmt->bind_result($v_name, $custodian);
    $stmt->fetch();
    $stmt->close();

    if ($custodian) {
        $stmt = $mysqli->prepare("SELECT a_email FROM tms_admin WHERE a_name = ?");
        $stmt->bind_param("s", $custodian);
        $stmt->execute();
        $stmt->bind_result($custodianEmail);
        $stmt->fetch();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-lugx-gaming.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .section.trending {
        margin-top: 10px;
    }

    .jumbotron {
        position: relative;
        background: none;
        color: white;
        margin-bottom: 20px;
    }

    .jumbotron:after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        background-image: url('assets/images/banner.jpg');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        width: 100%;
        height: 80%;
        opacity: 0.10;
        z-index: -1;
    }

    .jumbotron .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 80%;
        background-color: #D2F6FF;
        z-index: -1;
    }

    .jumbotron .lead {
        color: #4889cf;
        text-align: justify;
        margin: 0 2in;
        line-height: 1.8;
    }

    .kcp-facilities {
        text-align: center;
        font-size: 24px;
        color: #E38732;
        margin-bottom: 20px;
        margin-top: -50px;
        animation: fadeInDown 1s ease-in-out;
    }

    .trending-items {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin: -10px; /* Adjusted negative margin */
    }

    .trending-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 10px; /* Adjusted margin */
        cursor: pointer;
        text-align: center;
        animation: fadeInUp 1s ease-in-out;
    }

    .trending-item .thumb {
        width: 80px;
        height: 80px;
        margin-bottom: 5px;
        background-color: #D2F6FF;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-sizing: border-box;
        transition: background-color 0.3s ease;
    }

    .trending-item .thumb:hover {
        background-color: #F8E1CC;
    }

    .trending-item .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .trending-item .down-content {
        width: 100%;
        word-break: break-word;
    }

    .trending-item .down-content h4 {
        font-size: 11px;
        margin: 10px 0;
        font-weight: 300;
        color: #777;
    }

    @media (max-width: 768px) {
        .jumbotron .lead {
            margin: 0 1in;
        }
    }

    @media (max-width: 576px) {
        .jumbotron .lead {
            margin: 0 0.5in;
        }
    }

    @keyframes fadeInDown {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }


    .carousel-inner .row {
        max-width: 900px; /* Adjusted maximum width */
        margin: 0 auto; /* Center align the row */
    }

    .carousel-item {
        width: 100%;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: transparent;
        width: 10px;
        height: 10px;
        margin-top: -120px;
    }

    .carousel-control-prev-icon {
        background-image: none;
        margin-right: 100px;
    }

    .carousel-control-prev-icon::before {
        content: '‹';
        font-size: 50px;
        color: #4889cf;
        display: inline-block;
    }

    .carousel-control-next-icon {
        background-image: none;
        margin-left: 50px;
    }

    .carousel-control-next-icon::before {
        content: '›';
        font-size: 50px;
        color: #4889cf;
        display: inline-block;
    }

</style>

 <script>
        function navigateToSchedule(v_id) {
            window.location.href = 'calendar_view.php?v_id=' + v_id;
        }
    </script>
</head>

<body>
    <?php
    $ret = "SELECT * FROM tms_vehicle ORDER BY RAND()";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    $itemsPerPage = 8;
    $totalItems = $res->num_rows;
    $totalPages = ceil($totalItems / $itemsPerPage);
    ?>

    <div class="jumbotron pt-6">
        <div class="overlay"></div>
        <h3 class="display-5" style="color: #4889cf; font-size:16px;"><center>REMINDERS</center></h3>
        <hr class="my-2">
        <p class="lead" style="font-size: 13px;">You can view the complete schedule of each facility by clicking the image of the facility. However, please note that some dates may appear available but are actually reserved. These reservations might not yet be reflected in the calendar because they are either not yet updated by the admin or are pending approval.</p>
    </div>

    <div class="section trending">
        <div class="container">
            <div class="kcp-facilities"> 
                <h2 style="color: #4889cf">KCP FACILITIES</h2>
            </div>
            
            <!-- Custom Width Container -->
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-inner">
                    <?php
                    $currentPage = 0;
                    $rowIndex = 0;

                    while ($row = $res->fetch_object()) {
                        if ($rowIndex % $itemsPerPage === 0) {
                            if ($currentPage > 0) {
                                echo '</div>'; // Close previous row
                                echo '</div>'; // Close previous carousel-item
                            }
                            echo '<div class="carousel-item' . ($currentPage === 0 ? ' active' : '') . '">';
                            echo '<div class="row">';
                            $currentPage++;
                        }
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                            <div class="trending-item" onclick="navigateToSchedule(<?php echo $row->v_id; ?>)">
                                <div class="thumb">
                                    <img src="vendor/img/<?php echo $row->v_dpic; ?>" alt="">
                                </div>
                                <div class="down-content">
                                    <h4><?php echo $row->v_name; ?></h4>
                                </div>
                            </div>
                        </div>
                        <?php
                        $rowIndex++;
                    }
                    if ($totalItems % $itemsPerPage > 0) {
                        echo '</div>'; // Close last row
                        echo '</div>'; // Close last carousel-item
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <?php include("vendor/inc/footer.php");?>
  <!--.Footer-->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
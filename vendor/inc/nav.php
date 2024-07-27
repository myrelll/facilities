<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>iBookMo - Facility Booking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Custom Navbar Background Color */
        .custom-navbar {
            background-color: #7cdff7; /* Your desired background color */
        }
        
        /* Custom Font and Gradient for Navbar Brand */
        .navbar-brand {
            font-family: 'Arial Black', sans-serif;
            font-size: 2rem;
            background: #4889cf;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        /* Subtle Glow Animation for Navbar Brand */
        .navbar-brand:hover {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        /* Custom Color for Login Link */
        .navbar-nav .nav-link {
            color: #4889cf; /* Match the color of the brand text */
            transition: color 0.3s ease;
        }

        /* Custom Hover Color for Login Link */
        .navbar-nav .nav-link:hover {
            color: #3b6cb7; /* Slightly darker shade for hover effect */
        }
    </style>
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">iBookMo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php" style="color: #4889cf">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

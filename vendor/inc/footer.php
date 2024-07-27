<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ensure full viewport height */
        margin: 0;
        font-family: 'Poppins', sans-serif; /* Optional: Adjust font family */
    }

    .content {
        flex: 1; /* Fill remaining vertical space */
        padding-bottom: 60px; /* Space for the footer */
    }

    footer {
        background-color: #a1edff; /* Your desired background color */
        color: #ffffff; /* Text color */
        text-align: center; /* Center align text */
        padding: 10px 0; /* Adjust padding as needed */
        width: 100%; /* Full width of viewport */
        position: fixed; /* Fixed position at the bottom */
        bottom: 0; /* Stick to the bottom */
        z-index: 100; /* Ensure footer stays above other content if needed */
    }
</style>



<body>
    <div class="content">
        <!-- Your main content here -->
    </div>

    <footer>
        <div class="container">
            <p class="m-0">
                Copyright &copy; <?php echo date('Y');?> | <b><a href="https://docs.google.com/forms/d/e/1FAIpQLSeKr56P_MZBMEor6KGwbp3ZA6R0FNWObZhIOT3PGembJcNyAg/viewform?usp=sf_link" target="_blank">KCP MIS TEAM</a></b>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>

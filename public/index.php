<?php
// landing_page.php

// Include the header file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <!-- Bootstrap CSS for layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #222;
        }
        .navbar a {
            color: #029afe  !important;
            font-weight: bold;
            text-transform: uppercase;
        }
        .navbar a:hover {
            color: #f1c40f !important;
        }
            .hero {
        position: relative;
        background-image: url('https://www.dexerto.com/cdn-image/wp-content/uploads/2024/07/22/53946218718_8cb287dc71_k-1.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent black overlay */
        z-index: 1;
    }
    .hero img {
        min-height: 100px;
        width: auto;
        height: 150px; 
    }
    .hero div {
        position: relative;
        z-index: 2;
    }
        .hero h1 {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.2rem;
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #029afe;
            border-color: #029afe;
            color: white;
            padding: 12px 30px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #027bb5;
            border-color: #027bb5;
        }
        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>


    <!-- Hero Section -->
    <section class="hero">
        <div>
            <img src="../images/ogtt.svg" alt="" class="mb-3">
            <p>Your journey to success begins here.</p>
            <a href="home.php" class="btn btn-primary">Get Started</a>
            </div>
    </section>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Include the footer file

?>

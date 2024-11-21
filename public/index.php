<?php include('../includes/header.php'); ?>
<?php include('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGT Tournaments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #333;
            color: #f1c40f; 
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .categories-container {
            overflow-x: auto;
            white-space: nowrap;
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .category-item {
            display: inline-block;
            background-color: #444;
            color: #f1c40f; 
            padding: 10px 15px;
            margin-right: 10px;
            border-radius: 20px;
            text-align: center;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .category-item:hover {
            background-color: #f1c40f;
            color: #333;
        }

        .card {
            background-color: #444; 
            border: none;
            margin-bottom: 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
        }

        .card img {
            width: 300px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card-body {
            flex: 1;
            padding: 20px;
        }

        .card-title {
            color: #f1c40f; 
            font-size: 1.5rem;
        }

        .card-text {
            color: #fff; 
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #f1c40f;
            border: none;
            color: #333;
        }

        .btn-primary:hover {
            background-color: #e1b308;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">OGT Tournaments</h1>

        <!-- Categories -->
        <?php
        $categories = getTournamentCategories();
        ?>
        <div class="categories-container">
            <?php
            foreach ($categories as $category) {
                echo '
                <div class="category-item">' . htmlspecialchars($category['name']) . '</div>
                ';
            }
            ?>
        </div>

        <!-- Tournament Cards -->
        <?php
        $tournaments = getTournaments(); // Get tournaments from database

        // Loop thru tournaments, make card for each data tur
        foreach ($tournaments as $tournament) {
            $category = getTournamentCategoryById($tournament['category_id']);
            echo '
            <div class="card">
                <img src="' . htmlspecialchars($tournament['front_image']) . '" alt="' . htmlspecialchars($tournament['name']) . '">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($tournament['name']) . '</h5>
                    <p class="card-text">
                        ' . htmlspecialchars($tournament['schedule']) . '<br>
                        ' . htmlspecialchars($category['name']) . '<br>
                        ' . htmlspecialchars($tournament['schedule']) . '
                    </p>
                    <a href="tournament-detail.php?id=' . $tournament['id'] . '" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>';
        }
        ?>

    </div>
</body>
</html>

<?php include('../includes/footer.php'); ?>

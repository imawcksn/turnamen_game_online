<?php include('../includes/header.php'); ?>
<?php include('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #029afe;
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
            background-color: #151314;
            color: #029afe;
            padding: 10px 15px;
            margin-right: 10px;
            border-radius: 20px;
            text-align: center;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .category-item:hover {
            background-color: #029afe;
            color: #fff;
        }

        .category-item.active {
            background-color: #029afe;
            color: #fff;
            font-weight: bold;
        }

        .card {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: #151314;
            border: none;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .card img {
            width: 40%;
            max-width: 300px;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .card-body {
            flex: 1;
            padding: 20px;
        }

        .card-title {
            color: #029afe;
            font-size: 1.5rem;
        }

        .card-text {
            color: #fff;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #029afe;
            border: none;
            color: #333;
        }

        .btn-primary:hover {
            background-color: #029afe;
            color: #000;
        }

        a.btn.disabled {
            pointer-events: none;
            color: #fff;
            opacity: 0.25;
            background-color: #029afe;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-left mb-4">OGT Tournaments</h1>
        <div class="banner">
        <img src="https://yamisok-publicdata.obs.ap-southeast-4.myhuaweicloud.com/yamisok-publicdata/tournament/61e05ce7be4bf.jpeg" alt="' . htmlspecialchars($tournament['name']) . '">
        </div>

        <?php
        $categories = getTournamentCategories();
        ?>
        <div class="categories-container mt-4">
            <div class="category-item active" data-category-id="0">All Categories</div>
            <?php
            foreach ($categories as $category) {
                echo '
                <div class="category-item" data-category-id="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</div>
                ';
            }
            ?>
        </div>

        <div id="tournament-list">
            <?php
            $tournaments = getTournaments();
            foreach ($tournaments as $tournament) {
                $category = getTournamentCategoryById($tournament['category_id']);
                $isUserLoggedIn = isset($_SESSION['user']);

                echo '
                <div class="card" data-category-id="' . $tournament['category_id'] . '">
                    <img src="' . htmlspecialchars($tournament['front_image']) . '" alt="' . htmlspecialchars($tournament['name']) . '">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($tournament['name']) . '</h5>
                        <p class="card-text">
                            ' . htmlspecialchars('Prize Pool: ' . $tournament['prize']) . '<br>
                            ' . htmlspecialchars('Harga Pendaftaran: ' . ($tournament['price'] == 0 ? 'Gratis' : $tournament['price'])) . '<br>
                            ' . htmlspecialchars('Pendaftaran dimulai: ' . $tournament['start_date']) . '<br>
                            ' . htmlspecialchars('Pendaftaran ditutup: ' . $tournament['end_date']) . '<br>
                            ' . htmlspecialchars('Max Slot: ' . $tournament['max_slot']) .   '<br>
                            ' . htmlspecialchars('Game: ' . $category['name'] ?? 'None') . '
                        </p>
                        <a href="' . ($isUserLoggedIn ? 'tournament-detail.php?id=' . $tournament['id'] : '#') . '" 
                           class="btn btn-primary ' . ($isUserLoggedIn ? '' : 'disabled') . '" 
                           ' . ($isUserLoggedIn ? '' : 'aria-disabled="true"') . '>
                           Lihat Detail
                        </a>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.category-item').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const categoryId = this.getAttribute('data-category-id');
                filterTournaments(categoryId);
            });
        });

        function filterTournaments(categoryId) {
            const tournaments = document.querySelectorAll('.card');
            tournaments.forEach(tournament => {
                const tournamentCategoryId = tournament.getAttribute('data-category-id');
                if (categoryId == 0 || categoryId == tournamentCategoryId) {
                    tournament.style.display = 'flex';
                } else {
                    tournament.style.display = 'none';
                }
            });
        }

        filterTournaments(0);
    </script>
</body>
</html>

<?php include('../includes/footer.php'); ?>

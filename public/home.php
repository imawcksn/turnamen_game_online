<?php include('../includes/header.php'); ?>
<?php include('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="../assets/images/OGT.svg">
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
    max-width: 20%;
    margin: auto;
    padding: 10px;
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
    padding: 8px 12px;
    margin-right: 10px;
    border-radius: 20px;
    text-align: center;
    font-size: 12px;
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

.banner {
    width: 100%; 
    max-width: 800px; 
    max-height: 300px; 
    overflow: hidden;
    position: relative;
}

.banner img {
    width: 100%;
    height: 100%; 
    object-fit: cover; 
    object-position: center center; 
}

.card {
    max-width: 800px; 
    width: 100%; 
    display: flex;
    flex-direction: row;
    align-items: center;
    background-color: #151314;
    border: none;
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    padding: 10px;
}

.card img {
    width: 35%;
    max-width: 250px;
    height: auto;
    object-fit: cover;
    border-radius: 10px;
}

.card-body {
    flex: 1;
    padding: 10px;
}

.card-title {
    color: #029afe;
    font-size: 1.2rem;
}

.card-text {
    color: #fff;
    margin-bottom: 10px;
    font-size: 0.9rem;
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


@media (max-width: 1024px) {
    .card {
        flex-direction: column;
        align-items: flex-start;
    }

    .card img {
        width: 100%;
        max-width: 100%;
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 768px) {
    .category-item {
        font-size: 10px;
        padding: 6px 10px;
    }

    .card-title {
        font-size: 1rem;
    }

    .card-body {
        padding: 8px;
    }

    .card-text {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .category-item {
        font-size: 8px;
        padding: 5px 8px;
    }

    .card-title {
        font-size: 0.9rem;
    }

    .card-text {
        font-size: 0.75rem;
    }
}


    </style>
</head>
<body>
    <div class="main-content">
        <h1 class="text-left mb-4">Tournaments</h1>
        <div class="banner">
            <a href="https://valorantesports.com/id-ID/news/valorant-game-changers-championship-2024-everything-you-need-to-know">
                <img src="../images/banner2.jpg" alt="' . htmlspecialchars($tournament['name']) . '" width="100%" height="300">
            </a>
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
                            ' . htmlspecialchars('Prize Pool: ' . 'Rp. ' . number_format($tournament['prize'], 2, ',', '.')) . '<br>
                            ' . htmlspecialchars('Harga Pendaftaran: ' . ($tournament['price'] == 0 ? 'Gratis' : 'Rp. ' . number_format($tournament['price'], 2, ',', '.'))) . '<br>
                            ' . htmlspecialchars('Periode pendaftaran: ' . date('Y/m/d', strtotime($tournament['start_date'])) .' - ' . date('Y/m/d', strtotime($tournament['end_date']))) .  '<br>
                            <br>
                            ' . htmlspecialchars('Tournament Day Start: ' . date('Y/m/d H:i', strtotime($tournament['match_start']))) . '<br> 
                            ' . htmlspecialchars('Tournament Ends: ' . date('Y/m/d H:i', strtotime($tournament['match_end']))) . '<br> 
                            <br>                                    
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

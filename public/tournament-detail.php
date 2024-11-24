<?php 
include('../includes/header.php'); 
include('functions.php'); 

$tournamentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$tournamentDetails = getTournamentById($tournamentId); 
$category = getTournamentCategoryById($tournamentDetails['category_id']);
if (!$tournamentDetails) {
    die("Tournament not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tournamentDetails['name']); ?> - Tournament Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #333;
            color: #029afe;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 900px;
            margin-left: 200px;
            padding: 20px;
            border-radius: 10px;
            background-color: #222;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        h1, h3 {
            color: #029afe;
            font-weight: bold;
            font-size: 1.5rem;
        }
        p {
            color: #eee;
            margin-bottom: 10px;
            line-height: 1.4;
            font-size: 0.9rem;
        }
        .btn-live {
            display: inline-block;
            padding: 8px 15px;
            background-color: #029afe;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 0.9rem;
        }
        .btn-live:hover {
            background-color: #029afe;
            transform: scale(1.05);
            color: #000;
        }
        .row {
            margin-top: 20px;
        }
        .img-fluid {
            width: 100%;
            height: auto;
        }
        .card {
            background-color: #333;
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #029afe;
        }
        .card-text {
            color: #eee;
            font-size: 0.9rem;
        }
       
        .col-lg-4 {
            flex: 1;
        }
        .col-lg-4.card-column {
            padding-left: 30px;
        }
    </style>
</head>
<body>
    <div class="container main-content">
        <div class="row">

            <div class="col-lg-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($tournamentDetails['front_image']); ?>" 
                         alt="<?php echo htmlspecialchars($tournamentDetails['name']); ?>" 
                         class="img-fluid card-img-top">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($tournamentDetails['name']); ?></h2>
                        <p><strong>Pendaftaran dibuka:</strong> <?php echo htmlspecialchars($tournamentDetails['start_date']); ?></p>
                        <p><strong>Pendaftaran ditutup:</strong> <?php echo htmlspecialchars($tournamentDetails['end_date']); ?></p>
                        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($category['name'] ?? 'Tidak ada kategori'); ?></p>
                        <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($tournamentDetails['contact_person']); ?></p>
                        <p><strong>Livestream URL:</strong> <?php echo htmlspecialchars($tournamentDetails['livestream']); ?></p>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo htmlspecialchars($tournamentDetails['form_link']); ?>" class="btn btn-live w-100" role="button">Daftar untuk Turnamen</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="list-group">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Prize</h3>
                            <p class="card-text">
                                <?php 
                                if (isset($tournamentDetails['prize']) && is_numeric($tournamentDetails['prize'])) {
                                    echo 'Rp. ' . number_format($tournamentDetails['prize'], 2, ',', '.');
                                } else {
                                    echo 'No prize details available';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Price</h3>
                            <p class="card-text">
                                <?php 
                                if (isset($tournamentDetails['price']) && is_numeric($tournamentDetails['price'])) {
                                    echo 'Rp. ' . number_format($tournamentDetails['price'], 2, ',', '.');
                                } else {
                                    echo 'No price details available';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Max Slot</h3>
                            <p class="card-text"><?php echo htmlspecialchars($tournamentDetails['max_slot']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 card-column">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Deskripsi</h3>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($tournamentDetails['description'] ?? 'No description available')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('../includes/footer.php'); ?>

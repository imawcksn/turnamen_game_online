<?php
ob_start();
include('../includes/header.php');
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit;
}

// ambil dan pastikan tabel dan id
$currentTable = isset($_GET['table']) ? $_GET['table'] : 'tournaments';
$recordId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recordId === 0) {
    header('Location: admin-dashboard.php');
    exit;
}

// ambil record atau data berdasarkan id dan tabel
$record = getRecordById($currentTable, $recordId);

if (!$record) {
    header('Location: admin-dashboard.php');
    exit;
}

// handle form input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $uploadedFilePath = null;
    $errors = [];

    // handle front image
    if (isset($_FILES['front_image']) && $_FILES['front_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['front_image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['front_image']['name']);
        $fileSize = $_FILES['front_image']['size'];
        $fileType = mime_content_type($fileTmpPath);
        $allowedTypes = ['image/jpeg', 'image/png'];
        
        // Adjust the upload directory path to include the correct base folder
        $uploadDir = 'uploads/'; // relative path from the script
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/turnamen-game-online/' . $uploadDir; // full path

        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/turnamen-game-online";

        // Validate file type and size
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Invalid file type for front image. Only JPG and PNG are allowed.";
        } elseif ($fileSize > 2 * 1024 * 1024) { // Limit size to 2MB
            $errors[] = "File size for front image exceeds 2MB.";
        } else {
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $uploadedFilePath = $uploadPath . $fileName;
            if (!move_uploaded_file($fileTmpPath, $uploadedFilePath)) {
                $errors[] = "Failed to upload front image.";
            } else {
                // Set the correct URL for the uploaded image
                $data['front_image'] = $baseURL . '/' . $uploadDir . $fileName;
            }
        }
    }

    // If no errors, update the record
    if (empty($errors)) {
        updateRecord($currentTable, $recordId, $data);
        header('Location: admin-dashboard.php?table=' . $currentTable);
        exit;
    } else {
        // Show errors if any
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
    }
}

ob_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= ucfirst($currentTable) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #333;
            color: #029afe;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .btn-primary {
            background-color: #029afe;
            border-color: #029afe;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #007ace;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Edit <?= ucfirst($currentTable) ?></h1>
    <form method="POST" enctype="multipart/form-data">
        <?php foreach ($record as $field => $value): ?>
            <div class="mb-3">
                <label for="<?= $field ?>" class="form-label"><?= ucfirst($field) ?></label>
                <?php if ($field === 'front_image'): ?>
                    <input 
                        type="file" 
                        id="<?= $field ?>" 
                        name="<?= $field ?>" 
                        class="form-control"
                    >
                    <?php if ($value): ?>
                        <img src="<?= htmlspecialchars($value) ?>" alt="Current Image" style="max-width: 100px; margin-top: 10px;">
                    <?php endif; ?>
                <?php elseif ($field === 'description'): ?>
                    <textarea 
                        id="<?= $field ?>" 
                        name="<?= $field ?>" 
                        class="form-control" 
                        rows="4"><?= htmlspecialchars($value) ?></textarea>
                        <?php else: ?>
                        <input 
                            type="<?= in_array($field, ['start_date', 'end_date']) ? 'date' : (in_array($field, ['match_start', 'match_end']) ? 'datetime-local' : 'text') ?>" 
                            id="<?= $field ?>" 
                            name="<?= $field ?>" 
                            class="form-control" 
                            value="<?= htmlspecialchars(in_array($field, ['match_start', 'match_end']) ? date('Y-m-d\TH:i', strtotime($value)) : (in_array($field, ['start_date', 'end_date']) ? date('Y-m-d', strtotime($value)) : $value)) ?>" 
                            <?= $field === 'id' ? 'readonly' : '' ?>
                        >
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="admin-dashboard.php?table=<?= $currentTable ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

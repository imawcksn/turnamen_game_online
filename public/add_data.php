<?php
ob_start(); //untuk fix error headers already sent output
include('../includes/header.php');
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit;
}

$table = $_GET['table'] ?? 'tournaments';

// define fields based on the table name
$fields = [
    'tournaments' => [
        'name' => 'Tournament Name',
        'category_id' => 'Category',
        'description' => 'Description',
        'contact_person' => 'Contact Person (phone number)',
        'price' => 'Price',
        'prize' => 'Prize',
        'max_slot' => 'Max Slot',
        'front_image' => 'Front Image',
        'livestream' => 'Livestream URL',
        'form_link' => 'Google Form URL',
        'start_date' => 'Start Date (YYYY-MM-DD)',
        'end_date' => 'End Date (YYYY-MM-DD)',
        'match_start' => 'Match Start Date (YYYY-MM-DD)',
        'match_end' => 'Match End Date (YYYY-MM-DD)',
    ],
    'users' => [
        'username' => 'Username',
        'password' => 'Password',
        'email' => 'Email',
        'role' => 'Role',
    ],
    'tournament_categories' => [
        'name' => 'Game Title',
    ],
];

if (!isset($fields[$table])) {
    echo "<div class='alert alert-danger'>Unknown table: $table</div>";
    exit;
}

$tableFields = $fields[$table];
$tournamentCategories = $table === 'tournaments' ? getTournamentCategories() : [];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $uploadedFilePath = null;

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

    foreach ($tableFields as $field => $label) {
        if ($field !== 'front_image' && (!isset($data[$field]) || trim($data[$field]) === '')) {
            $errors[] = "Field '$label' is required.";
        }
    }

    if (empty($errors)) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $bindTypes = '';
    
        foreach ($data as $field => $value) {
            $bindTypes .= in_array($field, ['price', 'max_slot', 'prize']) ? 'i' : 's';
        }

        if (editRecord($table, $columns, $placeholders, $bindTypes, $data)) {
            header("Location: admin-dashboard.php?table=$table");
            exit;
        } else {
            $errors[] = "Failed to save record. Check logs for details.";
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
    <title>Add Record to <?= ucfirst($table) ?></title>
    <style>
        .mx-auto.mt-4 {
            padding: 10px;
            max-width: 400px;
        }

        input.form-control,
        select.form-control {
            padding: 6px;
            font-size: 12px;
            height: auto;
        }

        .form-label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        button.btn.btn-primary {
            padding: 6px 12px;
            font-size: 14px;
        }

        .alert {
            font-size: 14px;
            padding: 8px;
        }

        body {
            font-size: 14px;
            background-color: #222;
            color: #029afe;
        }

        h1 {
            font-size: 18px;
        }

        input[type="text"], input[type="number"], input[type="date"], select {
            height: 30px;
            padding: 5px;
            font-size: 12px;
        }

        input[type="file"] {
            padding: 4px;
            font-size: 12px;
        }

        table {
            font-size: 12px;
            padding: 5px;
        }

        table th, table td {
            padding: 6px;
        }

        /* Styling for the textarea */
        textarea.form-control {
            padding: 8px;
            font-size: 14px;
            height: 100px;
            resize: vertical;
        }
    </style>
</head>
<body>
<div class="mx-auto mt-4">
    <h1>Add Record to <?= ucfirst($table) ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <?php foreach ($tableFields as $field => $label): ?>
            <div class="mb-1">
                <label for="<?= $field ?>" class="form-label"><?= $label ?></label>
                
                <?php if ($field === 'category_id' && $table === 'tournaments'): ?>
                    <select class="form-control" id="<?= $field ?>" name="<?= $field ?>" required>
                        <option value="" disabled selected>Select a category</option>
                        <?php foreach ($tournamentCategories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif ($field === 'description'): ?>
                    <textarea class="form-control" id="<?= $field ?>" name="<?= $field ?>" placeholder="<?= $label ?>" required><?= $data['description'] ?? '' ?></textarea>
                <?php elseif ($field === 'front_image'): ?>
                    <input type="file" class="form-control" id="<?= $field ?>" name="<?= $field ?>" accept="image/*">
                <?php else: ?>
                    <input 
                    type="<?= in_array($field, ['price', 'max_slot', 'prize']) ? 'number' : (in_array($field, ['start_date', 'end_date']) ? 'date' : (in_array($field, ['match_start', 'match_end']) ? 'datetime-local' : 'text')) ?>"
                        class="form-control" 
                        id="<?= $field ?>" 
                        name="<?= $field ?>" 
                        placeholder="<?= $label ?>" 
                        required>
                <?php endif; ?>

                <div class="invalid-feedback">
                    Please provide a valid <?= strtolower($label) ?>.
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>

<?php include('../includes/footer.php'); ?>

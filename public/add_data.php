<?php
ob_start(); //untuk fix error headers already sent output
include('../includes/header.php');
include('../public/functions.php');

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
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

// cek if the table exists
if (!isset($fields[$table])) {
    echo "<div class='alert alert-danger'>Unknown table: $table</div>";
    exit;
}

$tableFields = $fields[$table];
$tournamentCategories = $table === 'tournaments' ? getTournamentCategories() : [];

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $uploadedFilePath = null;

    if (isset($_FILES['front_image'])) {
        $fileTmpPath = $_FILES['front_image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['front_image']['name']); //use uniqid biar gk bs duplicate nama file ny
        $fileSize = $_FILES['front_image']['size'];
        $fileType = mime_content_type($fileTmpPath); //ambil file type
        $allowedTypes = ['image/jpeg', 'image/png']; //allow images aja
        $uploadDir = '../uploads/'; //masukin ke folder uploads
    
        // ambil url server kita, cek apabila https atau http
        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    
        // validasi file type dan file size
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Invalid file type for front image. Only JPG and PNG are allowed.";
        } elseif ($fileSize > 2 * 1024 * 1024) { // Limit size to 2MB
            $errors[] = "File size for front image exceeds 2MB.";
        } else {
            //jika directory atau folder bernama variable uploadDir tidak ada, create directory atau folder dengan permissions 0777 yang membolehkan create file di dalam folder tsb
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            //gabungkan tempat folder dengan nama file
            $uploadedFilePath = $uploadDir . $fileName;

            //pindahkan file dari temporary folder ke folder yang diinginkan (upload)
            if (!move_uploaded_file($fileTmpPath, $uploadedFilePath)) {
                $errors[] = "Failed to upload front image."; //return error apabila pemindahan gagal
            } else {

                // save url fullnya apabila success
                $data['front_image'] = $baseURL . '/uploads/' . $fileName;
            }
        }
    }

    // validasi dan process apabila ada field yang belum di isi
    foreach ($tableFields as $field => $label) {
        // skip front image karena nullable
        if ($field !== 'front_image' && $field !== 'livestream' (!isset($data[$field]) || trim($data[$field]) === '')) {
            $errors[] = "Field '$label' is required.";
        }
    }

    // Save ke db apabila tidak ada error
    if (empty($errors)) {

        //pisahkan masing masing data dari array
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $bindTypes = '';
    
        // tentukan bind times (string atau int)
        foreach ($data as $field => $value) {
            $bindTypes .= in_array($field, ['price', 'max_slot', 'prize']) ? 'i' : 's';
        }
    
        // proses data di function editrecord
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

<body style="background-color: #222; color: #029afe;">  
<div class="mx-auto mt-4">
    <h1>Add Record to <?= ucfirst($table) ?></h1>
    
    <!-- display errors  -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <!-- create form  -->
        <?php foreach ($tableFields as $field => $label): ?>
            <div class="mb-3">
                <label for="<?= $field ?>" class="form-label"><?= $label ?></label>
                <!-- jika field punya nya tournaments dan adalah category_id, ambil dari table table_categories dan bisa select -->
                <?php if ($field === 'category_id' && $table === 'tournaments'): ?>
                    <select class="form-control" id="<?= $field ?>" name="<?= $field ?>" required>
                        <option value="" disabled selected>Select a category</option>
                        <?php foreach ($tournamentCategories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                        <!-- jika field nya si image, handle file type -->
                <?php elseif ($field === 'front_image'): ?>
                    <input type="file" class="form-control" id="<?= $field ?>" name="<?= $field ?>" accept="image/*">
                <?php else: ?>
                    <!-- jika field nya terima int, handle number, jika date handle date, selain itu text -->
                    <input 
                        type="<?= in_array($field, ['price', 'max_slot', 'prize']) ? 'number' : ($field === 'start_date' || $field === 'end_date' ? 'date' : 'text') ?>" 
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
<?php include('../includes/footer.php'); ?>

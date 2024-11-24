<?php
// sertakan header
ob_start();
include('../includes/header.php');
include('functions.php');
// periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// tangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $userId = $_SESSION['user']['id'];

    // validasi
    $errors = [];
    if (empty($username)) $errors[] = 'username diperlukan.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'format email tidak valid.';
    if (!empty($password) && strlen($password) < 6) $errors[] = 'password harus memiliki minimal 6 karakter.';

    // jika tidak ada kesalahan, perbarui informasi pengguna
    if (empty($errors)) {
        //hash dan masukkan ke func upduserprofile
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        $updateStatus = updateUserProfile($userId, $username, $email, $hashedPassword);

        if ($updateStatus) {
            // perbarui session dengan nilai baru
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['success'] = 'profil berhasil diperbarui.';
            header('Location: profile.php', false); // alihkan ke halaman profil
            exit;
        } else {
            $errors[] = 'gagal memperbarui profil. silakan coba lagi.';
        }
    }
}
ob_flush();
?>
<body style="background-color: #222">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- kartu edit profil -->
            <div class="card shadow-lg border-0 rounded-4" style="background-color: #000; color: #029afe;">
                <!-- header kartu -->
                <div class="card-header text-center" style="background-color: #029afe; color: #000; border-radius: 10px 10px 0 0;">
                    <h3 class="mb-0">edit profil</h3>
                </div>
                <!-- badan kartu -->
                <div class="card-body p-4">
                    <!-- tampilkan kesalahan -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- form edit profil -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label" style="color: #029afe;">username</label>
                            <input type="text" id="username" name="username" class="form-control" 
                                   style="background-color: #000; color: #029afe; border: 1px solid #029afe;" 
                                   value="<?php echo htmlspecialchars($_SESSION['user']['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label" style="color: #029afe;">email</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   style="background-color: #000; color: #029afe; border: 1px solid #029afe;" 
                                   value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="color: #029afe;">password baru (opsional)</label>
                            <input type="password" id="password" name="password" class="form-control" 
                                   style="background-color: #000; color: #029afe; border: 1px solid #029afe;">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn w-100 rounded-pill" 
                                    style="background-color: #029afe; color: #000; border: none;">simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php include('../includes/footer.php'); ?>

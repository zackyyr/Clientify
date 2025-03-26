<?php
session_start();
require_once "../config/config.php";
require_once "../controllers/auth_guard.php"; 


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clientify - Log in</title>
        <!-- CSS -->
        <link rel="stylesheet" href="../../public/css/auth.css?v=<?php echo time(); ?>">
    
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
        <!-- Icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="icon" href="../../public/assets/auth-logo.svg" type="image/x-icon">
    </head>
<body>
    <main>
        <div class="main-container">
            <div class="auth">
                <div class="auth-container">
                    <div class="auth-wrapper">
                        <div class="auth-form__return">
                            <a href=""><i class="ri-arrow-left-line"></i>Back</a>
                        </div>
                        <div class="auth-form">
                            <img src="../../public/assets/auth-logo.svg" alt="Clientify Logo">
                            <h2>Sign in</h2>
                            <p>Manage your leads, sends invoices, and streamline your workflow.</p>

                            <?php if (isset($_SESSION['status'])): ?>
                                <div class="status <?= $_SESSION['status']['type']; ?>">
                                    <div class="status-container">
                                        <?php if ($_SESSION['status']['type'] == 'success'): ?>
                                            <i class="ri-checkbox-circle-fill"></i>
                                        <?php elseif ($_SESSION['status']['type'] == 'warning'): ?>
                                            <i class="ri-alert-line"></i>
                                        <?php else: ?>
                                            <i class="ri-close-circle-fill"></i>
                                        <?php endif; ?>
                                        <h4><?= $_SESSION['status']['message']; ?></h4>
                                    </div>
                                </div>
                                <?php unset($_SESSION['status']); // Hapus session biar ga muncul terus ?>
                            <?php endif; ?>

                            <form action="../controllers/auth.php" method="POST">
                                <div class="form-group">
                                    <i class="ri-mail-line"></i>
                                    <input type="email" name="email" id="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <i class="ri-git-repository-private-line"></i>
                                    <input type="password" name="password" id="password" placeholder="Password" required>
                                    <button type="button" onclick="togglePassword('password')">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                
                                <input class="submit" type="submit" name="login" id="login" value="Sign in">
                            </form>
                            <strong class="forgot-pass">Don't have an account? <a href="register.php"> Sign up</a></strong>
                        </div>
                    </div>
                    <div class="auth-img">
                        <img src="../../public/assets/auth-img.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../public/js/auth.js?v=<?= time(); ?>"></script>
</body>
</html>
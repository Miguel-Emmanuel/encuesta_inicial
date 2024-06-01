<?php
session_start();
require '../../../database/db.php';


echo "Bienvenido";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../../../public/views/dashboard/dashboard.php');

        exit;
    } else {
        $error = 'Correo o contraseña incorrectos';
        header('Location: login.php?error=' . urlencode($error));
        echo $error;
        exit;
    }
}
?>

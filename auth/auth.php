<?php
session_start();
require_once '../config/config.php'; 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; 

    if(empty($username) || empty($password)){
        header("Location: ../index.php?error=empty_fields");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password, role, profile_image FROM user WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])){
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image'] ?? 'default_profile.png';

           
            if ($_SESSION['role'] === 'ADMIN') {
                header("Location: ../admin/dashboard.php");
            } else {
                
                header("Location: ../admin/point-of-sale/cashier_shift_start.php"); 
            }
            exit();

        } else {
           
            header("Location: ../index.php?error=invalid_credentials");
            exit();
        }

    } catch(PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../index.php?error=system_error");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
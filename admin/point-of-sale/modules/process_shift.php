<?php
session_start();
require_once '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $total_cash = $_POST['total_cash'];
    $denoms = $_POST['denom']; 

    try {
        $pdo->beginTransaction();

        // 1. Create the Shift record
        $stmt = $pdo->prepare("INSERT INTO shifts (user_id, starting_cash_total, status) VALUES (?, ?, 'OPEN')");
        $stmt->execute([$user_id, $total_cash]);
        $shift_id = $pdo->lastInsertId();

        // 2. Save each denomination count
        $stmtDenom = $pdo->prepare("INSERT INTO shift_denominations (shift_id, count_type, bill_value, quantity) VALUES (?, 'START', ?, ?)");
        
        foreach ($denoms as $value => $quantity) {
            if ($quantity > 0) {
                $stmtDenom->execute([$shift_id, $value, $quantity]);
            }
        }

        $pdo->commit();

        // Save shift_id in session so the POS knows which shift is active
        $_SESSION['active_shift_id'] = $shift_id;
        
        header("Location: ../pos_dashboard.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error starting shift: " . $e->getMessage());
    }
}
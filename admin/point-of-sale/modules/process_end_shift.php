<?php
session_start();
require_once '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shift_id = $_SESSION['active_shift_id'];
    $actual_total = $_POST['actual_total'];
    $expected_total = $_POST['expected_total'];
    $difference = $actual_total - $expected_total;
    $denoms = $_POST['denom'];

    try {
        $pdo->beginTransaction();

        // 1. Update the shift record with end data
        $stmt = $pdo->prepare("UPDATE shifts SET 
            ending_cash_total = ?, 
            cash_difference = ?, 
            end_time = CURRENT_TIMESTAMP, 
            status = 'CLOSED' 
            WHERE id = ?");
        $stmt->execute([$actual_total, $difference, $shift_id]);

        // 2. Save ending denominations
        $stmtDenom = $pdo->prepare("INSERT INTO shift_denominations (shift_id, count_type, bill_value, quantity) VALUES (?, 'END', ?, ?)");
        foreach ($denoms as $val => $qty) {
            if ($qty > 0) $stmtDenom->execute([$shift_id, $val, $qty]);
        }

        $pdo->commit();

        // Clear shift session but keep user session for a moment to show success
        unset($_SESSION['active_shift_id']);
        header("Location: ../../../index.php?msg=ShiftClosed");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
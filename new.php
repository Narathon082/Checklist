<?php
// new.php
// Starts a new evaluation by clearing the active submission session
session_start();
unset($_SESSION['current_submission_id']);
header('Location: dqa_combined.php');
exit;
?>

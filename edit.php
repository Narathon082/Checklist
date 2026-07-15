<?php
// edit.php
// Loads an existing submission by setting the session ID and redirecting to the form
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $_SESSION['current_submission_id'] = intval($_GET['id']);
}

header('Location: dqa_combined.php');
exit;
?>

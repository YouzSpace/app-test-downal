<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

$data = file_get_contents('../data/pages.json');
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="pages_backup.json"');
echo $data;
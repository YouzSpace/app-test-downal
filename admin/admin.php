<?php
session_start();

// 模拟用户数据
$validUsername = 'youz';
$validPassword = '123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['logged_in'] = true;
        header("Location: manage_pages.php");
        exit;
    } else {
        echo "用户名或密码错误";
    }
}
?>
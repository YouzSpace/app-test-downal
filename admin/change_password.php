<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $validPassword = '123';
    if ($oldPassword === $validPassword && $newPassword === $confirmPassword) {
        // 这里可以将新密码保存到文件或数据库
        echo "密码更改成功！";
    } else {
        echo "密码更改失败，请检查输入！";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更改密码</title>
</head>
<body>
    <form action="change_password.php" method="post">
        <label for="old_password">旧密码:</label>
        <input type="password" id="old_password" name="old_password" required><br>
        <label for="new_password">新密码:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <label for="confirm_password">确认新密码:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <input type="submit" value="更改密码">
    </form>
</body>
</html>
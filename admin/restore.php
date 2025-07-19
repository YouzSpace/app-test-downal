<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['backup_file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $backupData = file_get_contents($file['tmp_name']);
        file_put_contents('../data/pages.json', $backupData);
        echo "数据恢复成功！";
    } else {
        echo "文件上传失败！";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据恢复</title>
</head>
<body>
    <form action="restore.php" method="post" enctype="multipart/form-data">
        <label for="backup_file">选择备份文件:</label>
        <input type="file" id="backup_file" name="backup_file" required><br>
        <input type="submit" value="恢复数据">
    </form>
</body>
</html>
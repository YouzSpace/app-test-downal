<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

$pageId = $_GET['id'];
$data = json_decode(file_get_contents('../data/pages.json'), true);
$pageIndex = null;
foreach ($data as $index => $page) {
    if ($page['id'] === $pageId) {
        $pageIndex = $index;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remark = $_POST['remark'];
    $html = $_POST['html'];

    $fileName = '../pages/'.$pageId.'.html';
    file_put_contents($fileName, $html);

    $data[$pageIndex]['remark'] = $remark;
    $data[$pageIndex]['html'] = $html;
    file_put_contents('../data/pages.json', json_encode($data, JSON_PRETTY_PRINT));

    echo "页面更新成功！访问链接: <a href='".$fileName."'>".$fileName."</a><br>";
    echo "备用HTML代码: <textarea rows='10' cols='80'>".htmlspecialchars($html)."</textarea>";
} else {
    $page = $data[$pageIndex];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑手动生成页面</title>
</head>
<body>
    <form action="edit_manual_page.php?id=<?php echo $pageId; ?>" method="post">
        <label for="remark">页面备注:</label>
        <input type="text" id="remark" name="remark" value="<?php echo $page['remark']; ?>" required><br>
        <label for="html">HTML代码:</label><br>
        <textarea id="html" name="html" rows="10" cols="80" required><?php echo $page['html']; ?></textarea><br>
        <input type="submit" value="更新页面">
    </form>
</body>
</html>
<?php
}
?>
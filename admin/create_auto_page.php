<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remark = $_POST['remark'];
    $baiduLink = $_POST['baidu_link'];
    $quarkLink = $_POST['quark_link'];
    $lanzouLink = $_POST['lanzou_link'];
    $yun123Link = $_POST['yun123_link'];
    $moreLink = $_POST['more_link'];

    $html = '<div id="unique-photos-download" style="max-width:500px;margin:20px auto;background:white;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.1);overflow:hidden;">';
    $html .= '<div style="background:linear-gradient(135deg,#4285f4,#34a853);color:white;padding:30px 20px 20px;text-align:center;">';
    $html .= '<div style="width:80px;height:80px;border-radius:20px;background:white;margin:0 auto 15px;display:flex;align-items:center;justify-content:center;font-size:36px;">📷</div>';
    $html .= '<div style="font-size:22px;font-weight:600;margin-bottom:5px;">Photos - 谷歌相册</div>';
    $html .= '<div style="font-size:14px;opacity:0.9;">官方原版 · 安全下载</div>';
    $html .= '</div>';
    $html .= '<div style="padding:25px 20px;">';
    $html .= '<div style="color:#333;font-size:16px;margin-bottom:15px;padding-left:5px;border-left:3px solid #4285f4;">下载地址</div>';
    $html .= '<div style="display:flex;flex-direction:column;gap:12px;">';
    $html .= '<a href="'.$baiduLink.'" style="display:flex;align-items:center;padding:14px 18px;background:#f0f0f4;border-radius:12px;text-decoration:none;color:#333;transition:all 0.2s;" target="_blank">';
    $html .= '<div style="width:28px;height:28px;border-radius:6px;background:rgba(66,133,244,0.1);display:flex;align-items:center;justify-content:center;margin-right:15px;font-size:16px;">🅱️</div>';
    $html .= '<div style="flex:1;font-size:15px;">百度云盘 (密码:youz)</div>';
    $html .= '<div style="color:#999;font-size:18px;">→</div>';
    $html .= '</a>';
    $html .= '<a href="'.$quarkLink.'" style="display:flex;align-items:center;padding:14px 18px;background:#f0f0f4;border-radius:12px;text-decoration:none;color:#333;transition:all 0.2s;" target="_blank">';
    $html .= '<div style="width:28px;height:28px;border-radius:6px;background:rgba(66,133,244,0.1);display:flex;align-items:center;justify-content:center;margin-right:15px;font-size:16px;">🔍</div>';
    $html .= '<div style="flex:1;font-size:15px;">夸克网盘</div>';
    $html .= '<div style="color:#999;font-size:18px;">→</div>';
    $html .= '</a>';
    $html .= '<a href="'.$lanzouLink.'" style="display:flex;align-items:center;padding:14px 18px;background:#f0f0f4;border-radius:12px;text-decoration:none;color:#333;transition:all 0.2s;" target="_blank">';
    $html .= '<div style="width:28px;height:28px;border-radius:6px;background:rgba(66,133,244,0.1);display:flex;align-items:center;justify-content:center;margin-right:15px;font-size:16px;">⚡</div>';
    $html .= '<div style="flex:1;font-size:15px;">蓝奏云极速下载</div>';
    $html .= '<div style="color:#999;font-size:18px;">→</div>';
    $html .= '</a>';
    $html .= '<a href="'.$yun123Link.'" style="display:flex;align-items:center;padding:14px 18px;background:#f0f0f4;border-radius:12px;text-decoration:none;color:#333;transition:all 0.2s;" target="_blank">';
    $html .= '<div style="width:28px;height:28px;border-radius:6px;background:rgba(66,133,244,0.1);display:flex;align-items:center;justify-content:center;margin-right:15px;font-size:16px;">💾</div>';
    $html .= '<div style="flex:1;font-size:15px;">123云盘备份</div>';
    $html .= '<div style="color:#999;font-size:18px;">→</div>';
    $html .= '</a>';
    $html .= '<a href="'.$moreLink.'" style="display:flex;align-items:center;padding:14px 18px;background:#f0f0f4;border-radius:12px;text-decoration:none;color:#333;transition:all 0.2s;" target="_blank">';
    $html .= '<div style="width:28px;height:28px;border-radius:6px;background:rgba(66,133,244,0.1);display:flex;align-items:center;justify-content:center;margin-right:15px;font-size:16px;">📱</div>';
    $html .= '<div style="flex:1;font-size:15px;">更多软件</div>';
    $html .= '<div style="color:#999;font-size:18px;">→</div>';
    $html .= '</a>';
    $html .= '</div>';
    $html .= '<div style="margin-top:20px;font-size:12px;color:#999;text-align:center;">所有资源均来自网络，仅供测试使用</div>';
    $html .= '</div>';
    $html .= '</div>';

    $pageId = uniqid();
    $fileName = '../pages/'.$pageId.'.html';
    file_put_contents($fileName, $html);

    $data = json_decode(file_get_contents('../data/pages.json'), true);
    $newPage = [
        'id' => $pageId,
        'remark' => $remark,
        'type' => '自动生成',
        'link' => '../pages/'.$pageId.'.html',
        'html' => $html,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $data[] = $newPage;
    file_put_contents('../data/pages.json', json_encode($data, JSON_PRETTY_PRINT));

    echo "页面生成成功！访问链接: <a href='".$fileName."'>".$fileName."</a><br>";
    echo "备用HTML代码: <textarea rows='10' cols='80'>".htmlspecialchars($html)."</textarea>";
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自动生成下载页面</title>
</head>
<body>
    <form action="create_auto_page.php" method="post">
        <label for="remark">页面备注:</label>
        <input type="text" id="remark" name="remark" required><br>
        <label for="baidu_link">百度云盘链接:</label>
        <input type="text" id="baidu_link" name="baidu_link" required><br>
        <label for="quark_link">夸克网盘链接:</label>
        <input type="text" id="quark_link" name="quark_link" required><br>
        <label for="lanzou_link">蓝奏云链接:</label>
        <input type="text" id="lanzou_link" name="lanzou_link" required><br>
        <label for="yun123_link">123云盘链接:</label>
        <input type="text" id="yun123_link" name="yun123_link" required><br>
        <label for="more_link">更多软件链接:</label>
        <input type="text" id="more_link" name="more_link" required><br>
        <input type="submit" value="生成页面">
    </form>
</body>
</html>
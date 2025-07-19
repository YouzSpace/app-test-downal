<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

// 获取页面ID（如果是编辑模式）
$pageId = isset($_GET['id']) ? $_GET['id'] : '';
$pageData = [];

// 如果是编辑模式，读取现有页面数据
if ($pageId) {
    $pagesData = json_decode(file_get_contents('../data/pages.json'), true);
    foreach ($pagesData as $page) {
        if ($page['id'] === $pageId) {
            $pageData = $page;
            break;
        }
    }
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remark = $_POST['remark'];
    $content = $_POST['content'];
    $type = '手动编辑';
    $createdAt = date('Y-m-d H:i:s');
    
    // 生成唯一ID
    $newId = $pageId ?: uniqid();
    
    // 保存HTML文件
    $htmlFilePath = '../pages/' . $newId . '.html';
    file_put_contents($htmlFilePath, $content);
    
    // 更新JSON数据
    $pagesData = json_decode(file_get_contents('../data/pages.json'), true) ?: [];
    
    // 如果是编辑模式，先移除原有数据
    if ($pageId) {
        $newPagesData = [];
        foreach ($pagesData as $page) {
            if ($page['id'] !== $pageId) {
                $newPagesData[] = $page;
            }
        }
        $pagesData = $newPagesData;
    }
    
    // 添加新数据
    $pagesData[] = [
        'id' => $newId,
        'remark' => $remark,
        'type' => $type,
        'created_at' => $createdAt,
        'link' => '/pages/' . $newId . '.html'
    ];
    
    // 保存JSON文件
    file_put_contents('../data/pages.json', json_encode($pagesData, JSON_PRETTY_PRINT));
    
    // 重定向到管理页面
    header("Location: manage_pages.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageId ? '编辑' : '创建'; ?>手动页面</title>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #165DFF;
            --neutral-100: #F3F4F6;
            --neutral-200: #E5E7EB;
            --neutral-700: #374151;
            --neutral-800: #1F2937;
            --neutral-900: #111827;
            --transition: all 0.2s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background-color: var(--neutral-100);
            color: var(--neutral-800);
            line-height: 1.5;
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        
        /* 顶部导航栏 */
        header {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 12px 0;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title {
            display: flex;
            align-items: center;
        }
        
        .header-title i {
            margin-right: 8px;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
        }
        
        .header-actions a {
            color: white;
            margin-left: 16px;
        }
        
        /* 主内容区 */
        main {
            padding: 24px 0;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        
        .page-title i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        /* 表单卡片 */
        .form-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--neutral-200);
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
        }
        
        .form-textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--neutral-200);
            border-radius: 4px;
            font-size: 16px;
            min-height: 400px;
            resize: vertical;
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
        }
        
        .form-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #0E42B3;
        }
        
        .btn-secondary {
            background-color: var(--neutral-200);
            color: var(--neutral-800);
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: var(--neutral-300);
        }
        
        .btn i {
            margin-right: 4px;
        }
        
        /* 页脚 */
        footer {
            background-color: var(--neutral-800);
            color: white;
            padding: 16px 0;
            margin-top: 48px;
        }
        
        .footer-content {
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <!-- 顶部导航 -->
    <header>
        <div class="container header-content">
            <div class="header-title">
                <i class="fa fa-cogs"></i>
                <h1>网站后台管理</h1>
            </div>
            <div class="header-actions">
                <span>管理员</span>
                <a href="logout.php"><i class="fa fa-sign-out"></i></a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-title">
            <i class="fa fa-code"></i>
            <h2><?php echo $pageId ? '编辑' : '创建'; ?>手动页面</h2>
        </div>
        
        <div class="form-card">
            <form action="create_manual_page.php<?php echo $pageId ? '?id=' . $pageId : ''; ?>" method="post">
                <div class="form-group">
                    <label class="form-label" for="remark">页面备注</label>
                    <input type="text" id="remark" name="remark" class="form-input" 
                           placeholder="输入页面备注..." value="<?php echo htmlspecialchars($pageData['remark'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="content">HTML内容</label>
                    <textarea id="content" name="content" class="form-textarea" 
                              placeholder="输入HTML代码..."><?php echo htmlspecialchars($pageData['id'] ? file_get_contents('../pages/' . $pageData['id'] . '.html') : ''); ?></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> 保存页面
                    </button>
                    <a href="manage_pages.php" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> 返回列表
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <p>© 2025 网站后台管理系统. 保留所有权利.</p>
        </div>
    </footer>
</body>
</html>
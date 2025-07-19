<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: admin.html");
    exit;
}

// 开始性能计时
$startTime = microtime(true);

$data = json_decode(file_get_contents('../data/pages.json'), true);

if (isset($_GET['delete'])) {
    $pageId = $_GET['delete'];
    $newData = [];
    foreach ($data as $page) {
        if ($page['id'] !== $pageId) {
            $newData[] = $page;
        } else {
            unlink('../pages/'.$pageId.'.html');
        }
    }
    file_put_contents('../data/pages.json', json_encode($newData, JSON_PRETTY_PRINT));
    header("Location: manage_pages.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];
    $filteredData = [];
    foreach ($data as $page) {
        if (strpos($page['remark'], $keyword) !== false) {
            $filteredData[] = $page;
        }
    }
    $data = $filteredData;
}

// 计算处理时间
$processTime = microtime(true) - $startTime;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面统一管理</title>
    <!-- 简化版Tailwind配置 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4285f4',
                        secondary: '#34a853',
                    },
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
            .transition-custom {
                transition: all 0.2s ease;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">
    <!-- 顶部导航 -->
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fa fa-cogs mr-2"></i>
                <h1 class="text-lg font-bold">网站后台管理</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span>管理员</span>
                <a href="logout.php" class="hover:text-gray-200 transition-custom">
                    <i class="fa fa-sign-out"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i class="fa fa-th-list text-primary mr-2"></i>
                页面统一管理
            </h2>
            
            <!-- 搜索框 -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form action="manage_pages.php" method="post" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-grow">
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">备注关键词搜索</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" id="keyword" name="keyword" 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-custom"
                                placeholder="输入备注关键词...">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md transition-custom">
                            <i class="fa fa-search mr-2"></i>搜索
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- 页面列表 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left font-medium">备注</th>
                                <th class="py-3 px-4 text-left font-medium">创建时间</th>
                                <th class="py-3 px-4 text-left font-medium">类型</th>
                                <th class="py-3 px-4 text-left font-medium">访问链接</th>
                                <th class="py-3 px-4 text-left font-medium">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($data as $page): ?>
                                <tr class="hover:bg-gray-50 transition-custom">
                                    <td class="py-3 px-4 font-medium"><?php echo $page['remark']; ?></td>
                                    <td class="py-3 px-4 text-gray-600"><?php echo $page['created_at']; ?></td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $page['type'] === '自动生成' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo $page['type']; ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="<?php echo $page['link']; ?>" class="text-primary hover:underline flex items-center truncate max-w-xs">
                                            <i class="fa fa-external-link mr-1"></i>
                                            <span class="truncate"><?php echo $page['link']; ?></span>
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <?php if ($page['type'] === '自动生成'): ?>
                                                <a href="edit_auto_page.php?id=<?php echo $page['id']; ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded transition-custom text-sm">
                                                    <i class="fa fa-pencil mr-1"></i>编辑
                                                </a>
                                            <?php else: ?>
                                                <a href="edit_manual_page.php?id=<?php echo $page['id']; ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded transition-custom text-sm">
                                                    <i class="fa fa-pencil mr-1"></i>编辑
                                                </a>
                                            <?php endif; ?>
                                            <a href="manage_pages.php?delete=<?php echo $page['id']; ?>" onclick="return confirm('确定要删除这个页面吗？')" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded transition-custom text-sm">
                                                <i class="fa fa-trash mr-1"></i>删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- 空状态 -->
                <?php if (empty($data)): ?>
                    <div class="py-12 flex flex-col items-center justify-center text-gray-500">
                        <div class="w-12 h-12 mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fa fa-file-o text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium mb-1">暂无页面数据</h3>
                        <p class="text-sm">请点击下方按钮创建页面</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 功能按钮 -->
        <div class="flex flex-wrap gap-3">
            <a href="create_auto_page.php" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md transition-custom flex items-center">
                <i class="fa fa-magic mr-2"></i>自动生成下载页面
            </a>
            <a href="create_manual_page.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition-custom flex items-center">
                <i class="fa fa-code mr-2"></i>手动编辑HTML页面
            </a>
            <a href="backup.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition-custom flex items-center">
                <i class="fa fa-download mr-2"></i>数据备份
            </a>
            <a href="restore.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition-custom flex items-center">
                <i class="fa fa-upload mr-2"></i>数据恢复
            </a>
            <a href="change_password.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition-custom flex items-center">
                <i class="fa fa-lock mr-2"></i>更改密码
            </a>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-sm text-gray-400">
            <p>© 2025 网站后台管理系统. 保留所有权利.</p>
        </div>
    </footer>
</body>
</html>
<?php
/**
 * 将Clover格式的XML覆盖率报告转换为LCOV格式
 *
 * @param string $xmlPath Clover XML文件路径
 * @param string $lcovPath 输出的LCOV文件路径
 * @return bool 转换是否成功
 */
function cloverToLcov($xmlPath, $lcovPath) {
    // 检查文件是否存在
    if (!file_exists($xmlPath)) {
        throw new Exception("XML文件不存在: " . $xmlPath);
    }

    // 加载并解析XML
    $xml = simplexml_load_file($xmlPath);
    if ($xml === false) {
        throw new Exception("无法解析XML文件");
    }

    // 打开输出文件
    $handle = fopen($lcovPath, 'w');
    if (!$handle) {
        throw new Exception("无法打开输出文件: " . $lcovPath);
    }

    // 写入测试名称（留空）
    fwrite($handle, "TN:\n");

    // 处理每个文件
    foreach ($xml->project->file as $file) {
        $filePath = (string)$file['name'];
        fwrite($handle, "SF:" . $filePath . "\n");

        // 收集方法信息
        $methods = [];
        foreach ($file->line as $line) {
            if ((string)$line['type'] == 'method') {
                $methodName = (string)$line['name'];
                $count = (string)$line['count'];
                $lineNum = (string)$line['num'];

                if (!isset($methods[$methodName])) {
                    $methods[$methodName] = [
                        'count' => $count,
                        'line' => $lineNum
                    ];
                }
            }
        }

        // 写入函数信息
        foreach ($methods as $name => $method) {
            fwrite($handle, "FN:" . $method['line'] . "," . $name . "\n");
            fwrite($handle, "FNDA:" . $method['count'] . "," . $name . "\n");
        }

        // 写入函数统计
        $methodMetrics = $file->class->metrics;
        if ($methodMetrics) {
            $totalMethods = (string)$methodMetrics['methods'];
            $coveredMethods = (string)$methodMetrics['coveredmethods'];
            fwrite($handle, "FNF:" . $totalMethods . "\n");  // 函数总数
            fwrite($handle, "FNH:" . $coveredMethods . "\n"); // 命中的函数数
        }

        // 写入行覆盖率信息
        foreach ($file->line as $line) {
            if ((string)$line['type'] == 'stmt') {
                $lineNum = (string)$line['num'];
                $count = (string)$line['count'];
                fwrite($handle, "DA:" . $lineNum . "," . $count . "\n");
            }
        }

        // 写入行统计
        $lineMetrics = $file->metrics;
        if ($lineMetrics) {
            $totalLines = (string)$lineMetrics['statements'];
            $coveredLines = (string)$lineMetrics['coveredstatements'];
            fwrite($handle, "LF:" . $totalLines . "\n");  // 总行数
            fwrite($handle, "LH:" . $coveredLines . "\n"); // 命中的行数
        }

        // 结束当前文件
        fwrite($handle, "end_of_record\n");
    }

    // 关闭文件句柄
    fclose($handle);
    return true;
}

// 使用示例 - 从命令行参数获取路径
try {
    // 检查命令行参数
    if ($argc < 3) {
        throw new Exception("使用方法: php clover-to-lcov.php [输入的Clover XML文件] [输出的LCOV文件]");
    }

    $xmlFile = $argv[1];    // 从命令行参数获取输入的XML文件路径
    $lcovFile = $argv[2];   // 从命令行参数获取输出的LCOV文件路径

    if (cloverToLcov($xmlFile, $lcovFile)) {
        echo "转换成功！LCOV文件已生成: " . $lcovFile . "\n";
    }
} catch (Exception $e) {
    echo "转换失败: " . $e->getMessage() . "\n";
    exit(1);
}
?>
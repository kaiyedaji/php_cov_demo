<?php
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\CodeCoverage;

$filter = new Filter;
$filter->includeDirectory('../app');

$coverage = new CodeCoverage(
    (new Selector)->forLineCoverage($filter),
    $filter
);

$coverage->start('test');

// ...

register_shutdown_function('__coverage_stop', $coverage); #注册关闭方法

function __coverage_stop(CodeCoverage $coverage)
{
    $coverage->stop(); #停止统计
    $cov = '<?php return unserialize(' . var_export(serialize($coverage), true) . ');'; #获取覆盖结果，注意使用了反序列化
    file_put_contents(dirname(__FILE__) . '/report/cov/site.' . date('U') . '.' . uniqid() . '.cov', $cov); #将结果写入到文件中
}

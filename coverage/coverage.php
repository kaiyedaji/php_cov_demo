<?php
//var_dump(extension_loaded('pcov'));
//var_dump(ini_get('pcov.enabled'));

require_once dirname(__FILE__) . '/vendor/autoload.php'; # 在composer生成的vender同级目录
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReport;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade as XmlReport;
use SebastianBergmann\CodeCoverage\Report\PHP as TextReport;
use SebastianBergmann\CodeCoverage\Report\Clover;


// 检测是否是Web运行环境
if (!isset($_SERVER['REQUEST_METHOD'])) {
    return; // 跳过当前文件的执行
}


class ProcessInfo
{
    public $hostname;
    public $pid;
    public $cmdline;

    public function __construct($hostname, $pid, $cmdline)
    {
        $this->hostname = $hostname;
        $this->pid = $pid;
        $this->cmdline = $cmdline;
    }
}

function getRegisterInfo()
{
    // 获取主机名
    $hostname = gethostname();
    if ($hostname === false) {
        error_log("[php][Error] fail to get hostname");
        return [null, new Exception("Failed to get hostname")];
    }

    // 获取进程ID
    $pid = getmypid();
    if ($pid === false) {
        error_log("[php][Error] fail to get pid");
        return [null, new Exception("Failed to get process ID")];
    }

    // 获取命令行参数
    $cmdParts = [];
    // 添加脚本名称
    $cmdParts[] = $_SERVER['argv'][0] ?? '';
    // 添加所有参数
    if (!empty($_SERVER['argv']) && count($_SERVER['argv']) > 1) {
        $cmdParts = array_merge($cmdParts, array_slice($_SERVER['argv'], 1));
    }
    $cmdline = implode(' ', $cmdParts);

    return [new ProcessInfo($hostname, $pid, $cmdline), null];
}


list($processInfo, $error) = getRegisterInfo();
if ($error) {
    echo $error->getMessage();
}

$filter = new Filter;

$filter->includeDirectory(dirname(__FILE__) . '/app');

$coverage = new CodeCoverage(
    (new Selector)->forLineCoverage($filter),
    $filter
);

$coverage->start('<Site coverage>'); # 开始统计
register_shutdown_function('__coverage_stop', $coverage); # 注册关闭方法

$covdir = dirname(__FILE__) . '/coverage';

if (!is_dir($covdir)) {
    mkdir($covdir, 0777);
}

function __coverage_stop(CodeCoverage $coverage)
{
    $coverage->stop(); # 停止统计
//     // 直接生成HTML
//    (new HtmlReport)->process($coverage, dirname(__FILE__) . '/coverage' . '/coverage_html');
//    //  生成XML
//    $xml_report = new XmlReport("latest");
//    $xml_report->process($coverage, dirname(__FILE__).'/coverage'.'/coverage_xml');
//
//
//    $text_report = new TextReport();
//    $text_report->process($coverage, dirname(__FILE__).'/coverage'.'/coverage_text');

    $cov = '<?php return unserialize(' . var_export(serialize($coverage), true) . ');';#获取覆盖结果，注意使用了反序列化
    file_put_contents(dirname(__FILE__).'/coverage/site.' . date('U') .'.'.uniqid(). '.cov', $cov);#将结果写入到文件中

//    $cloverReport = new Clover();
//    $tempReportPath = dirname(__FILE__) . '/coverage/clover' . uniqid() . '.xml';
//    $cloverReport->process($coverage, $tempReportPath);
}
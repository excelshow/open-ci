<?php
/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 上午11:39
 */

$workspace = dirname(dirname(__DIR__));

$projectName = pathinfo(dirname(__DIR__), PATHINFO_FILENAME);

$logPath = $workspace . "/logs/supervisor/$projectName";
if (!is_dir($logPath)) {
	mkdir($logPath, 0777, true);
}

$fileName = 'open-contest-task.ini';

$files = array(
	"open_contest_task" => array(
		"batch/order/LoadExpired"    => 1,
		"batch/order/CheckPayResult" => 1,
		"batch/order/Close"          => 3,
		"batch/order/Fail"           => 3,
		"batch/invite_code/Create"   => 1,
		// "batch/order/PayResultCheck"                   => 1,
		// "batch/analysis/AnalysisContestPerDay"         => 3,
		// "batch/analysis/AnalysisContestItemPerDay"     => 3,
		// "batch/analysis/AnalysisContestItemPerDayCalc" => 3,
		// "batch/analysis/AnalysisOrderPerDay"           => 3,
		// "batch/analysis/AnalysisOrderPerDayCalc"       => 3,
		// "batch/order/PayCompleted"                     => 1,
		// "batch/order/CloseOrder"                       => 3,
	),
);

function echo_ini($file, $maxchild, &$programs, &$config)
{
	global $workspace, $projectName, $logPath;

	$programName = str_replace('/', '_', $file);
	$programs .= $programName . ',';
	$config .= "
[program:$programName]
command                 = /usr/bin/php $workspace/$projectName/webroot/index.php $file
redirect_stderr         = true
numprocs                = $maxchild
process_name            = %(program_name)s_%(process_num)d
stdout_logfile_maxbytes = 20MB
stdout_logfile          = $logPath/{$programName}_stdout.log
stderr_logfile_maxbytes = 20MB
stderr_logfile          = $logPath/{$programName}_stderr.log
autorestart             = true" . PHP_EOL . PHP_EOL;


}

foreach ($files as $group => $file) {

	$groupName = "[group:$group]" . PHP_EOL;
	file_put_contents($fileName, $groupName, FILE_APPEND);
	$programs = "programs=";
	$config   = '';
	foreach ($file as $fname => $maxchild) {
		echo_ini($fname, $maxchild, $programs, $config);
	}

	$programs = trim($programs, ',') . PHP_EOL;
	file_put_contents($fileName, $programs, FILE_APPEND);

	file_put_contents($fileName, $config, FILE_APPEND);
}

// if (file_exists($fileName) && filesize($fileName) > 0) {
// 	var_dump($fileName);
//
// 	$cmd_cat = 'cat ' . $fileName;
// 	system($cmd_cat);
//
// 	$cmd_mv = 'mv ' . $fileName . ' /etc/supervisord.d/';
// 	var_dump($cmd_mv);
// 	system($cmd_mv, $returnVar);
//
// 	var_dump($returnVar);
// }

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

$fileName = 'open-weixin-task-qywx.ini';

$files = array(
	"open_weixin_task_qywx" => array(
		"qywx/auth/LoadAuthNeedRefreshToken"   => 1,
		"qywx/auth/RefreshToken"               => 3,
		"qywx/corp/LoadCorpNeedRefreshTicket"  => 1,
		"qywx/corp/RefreshTicket"              => 3,
		"qywx/provider/RefreshToken"           => 1,
		"qywx/suite/LoadSuiteNeedRefreshToken" => 1,
		"qywx/suite/RefreshToken"              => 3,
		"qywx/suite/ConsumeCallbackEvent"      => 1,
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

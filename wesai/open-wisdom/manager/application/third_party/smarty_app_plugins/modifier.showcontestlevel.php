<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty cdnurl modifier plugin
 *
 * Type:     modifier<br>
 * Name:     cdnurl <br>
 * Purpose:  return an empty string
 *
 * @author   chenxingrui@wesai.com
 *
 * @param $level
 *
 * @return string with compiled code
 *
 * Examples : {$object->level|showcontestlevel}
 * @internal param array $params parameters
 *
 */

function smarty_modifier_showcontestlevel($level)
{
	try {
		GLOBAL $CONTEST_LEVEL_LIST;
		$level_string = '';
		$level        = intval($level);
		foreach ($CONTEST_LEVEL_LIST as $key => $val) {
			if (($level & $key) == true || $level == $key) {
				$level_string .= $val . '&nbsp;&nbsp;';
			}
		}

		return $level_string;

	} catch (SmartyException $e) {
		echo $e->getMessage();
	}
}

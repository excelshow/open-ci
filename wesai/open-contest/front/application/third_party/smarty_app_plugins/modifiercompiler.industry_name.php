<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty industry_name modifier plugin
 *
 * Type:     modifier<br>
 * Name:     industry_name <br>
 * Purpose:  return an empty string
 *
 * @author   zhaodc
 * @param array $params parameters
 * @return string with compiled code
 *
 * Examples : {1|industry_name}
 */


function smarty_modifiercompiler_industry_name( $params, $compiler ) {
    try {
        $corp_id = 0;
	    if (!empty($params[0])) {
		    $corp_id = $params[0];
	    }
        $contest_id = 0;
	    if (!empty($params[1])) {
		    $contest_id = $params[1];
	    }
        $position = '';
	    if (!empty($params[2])) {
		    $position = $params[2];
	    }

	    global $INDUSTRY_NAME_LIST, $INDUSTRY_CORP_IDS, $INDUSTRY_CONTEST_IDS;
        // 命中
        $industry_hit = INDUSTRY_BASE;
        foreach($INDUSTRY_CORP_IDS as $industry => $corp_ids){
		    if (in_array($corp_id, $corp_ids)) {
			    $industry_hit = $industry;
			    break;
		    }
        }

        foreach($INDUSTRY_CONTEST_IDS as $industry => $contest_ids){
		    if (in_array($contest_id, $contest_ids)) {
			    $industry_hit = $industry;
			    break;
		    }
        }

        //$position = str_replace("'", "", $position);
        $position = (string)$position;
        var_dump($corp_id,$contest_id,$position);
		if (!empty($INDUSTRY_NAME_LIST[$industry_hit])) {
			return '"'.$INDUSTRY_NAME_LIST[$industry_hit][$position].'"';
		}

		return '""';
    } catch ( SmartyException $e ) {
        echo $e->getMessage();
    }
}

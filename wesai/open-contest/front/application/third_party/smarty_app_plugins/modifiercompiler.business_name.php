<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty business_name modifier plugin
 *
 * Type:     modifier<br>
 * Name:     business_name <br>
 * Purpose:  return an empty string
 *
 * @author   zhaodc
 * @param array $params parameters
 * @return string with compiled code
 *
 * Examples : {1|business_name}
 */


function smarty_modifiercompiler_business_name( $params, $compiler ) {
    try {
    	$id = $params[0];

	    $type = 'contest';
	    if (!empty($params[1])) {
	    	$type = $params[1];
	    }

	    global $BUSINESS_NAME_LIST, $MAPPING_INDUSTRY_CONTEST, $MAPPING_INDUSTRY_CORP;
	    if ($type == 'contest') {
	    	$arr = $MAPPING_INDUSTRY_CONTEST;
	    } else {
			$arr = $MAPPING_INDUSTRY_CORP;
	    }

	    $industryId = 0;
		foreach ($arr as $industry => $ids) {
			if (in_array($id, $ids)) {
				$industryId = $industry;
				break;
			}
		}

		if (!empty($BUSINESS_NAME_LIST[$industryId])) {
			return $BUSINESS_NAME_LIST[$industryId];
		}

		return '报名';
    } catch ( SmartyException $e ) {
        echo $e->getMessage();
    }
}

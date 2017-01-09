<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty cdnurl modifier plugin
 *
 * Type:     modifier<br>
 * Name:     cdnurl <br>
 * Purpose:  return an empty string
 *
 * @author   xumenghe@gmail.com
 * @param array $params parameters
 * @return string with compiled code
 *
 * Examples : {"<appname>/<modulename>/[img]/banner.jpg"|cdnurl} {$object->fileid|cdnurl}
 */

if ( !defined( '_STATIC_RES_CDN_DOMAIN_' ) ) {
    define( '_STATIC_RES_CDN_DOMAIN_', 'qdj-static.oss-cn-hangzhou.aliyuncs.com');
}

if ( !defined( '_UPLOAD_RES_CDN_DOMAIN_' ) ) {
    define( '_UPLOAD_RES_CDN_DOMAIN_', 'qdj-upload.oss-cn-hangzhou.aliyuncs.com');
}

if ( !defined( 'RELEASE_VERSION' ) ) {
    define( 'RELEASE_VERSION', '0.0.1' );
}

function smarty_modifiercompiler_cdnurl( $params, $compiler ) {
    try {
	    $source = 'static';
	    if (!empty($params[1])) {
		    $source = $params[1];
	    }
	    switch (strtolower($source)) {
		    case 'static':
			    return '"http://'
			           . _STATIC_RES_CDN_DOMAIN_
			           . '/".' . $params[0] . '."'
			           . '?v=".@constant(\'RELEASE_VERSION\')';
			    break;
		    default:
			    return '"http://'
			           . _UPLOAD_RES_CDN_DOMAIN_
			           . '/".' . $params[0];
			    break;
	    }

    } catch ( SmartyException $e ) {
        echo $e->getMessage();
    }
}

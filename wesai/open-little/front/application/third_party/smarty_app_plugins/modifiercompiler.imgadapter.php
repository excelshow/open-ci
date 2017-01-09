<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */
/**
 * @ignore
 */
require_once( SMARTY_PLUGINS_DIR .'shared.literal_compiler_param.php' );

/**
 * Smarty imgadapter modifier plugin
 *
 * Type:     modifier<br>
 * Name:     imgadapter <br>
 *
 * @author   xumenghe@gmail.com 
 * @param array $params parameters
 * @return string with compiled code
 * 
 * Examples : {$file->fileid|imgadapter:"tiny"}
 */

if ( !defined( '_DEFAULT_IMG_DIMENSION_' ) ) {
    define( '_DEFAULT_IMG_DIMENSION_', 'small' );
}

function smarty_modifiercompiler_imgadapter( $params, $compiler ) {
    static $img_type_mapping = array(
        'tiny'   => '0101',
        'small'  => '0102',
        'medium' => '0103',
        'big'    => '0104',
        'huge'   => '0105',
    ); 


    try {
        $dimension = smarty_literal_compiler_param( $params, 1, _DEFAULT_IMG_DIMENSION_ );
        if( empty( $img_type_mapping[ $dimension ] ) ) {
            $dimension = _DEFAULT_IMG_DIMENSION_;
        }

        return "preg_replace("
            . "'/^(010[0-5])([0-9A-Fa-f]{28})$/',"
            . "'".$img_type_mapping[$dimension]."\${2}'," 
            . $params[0] . ")";

    } catch ( SmartyException $e ) {
        echo $e->getMessage();
    }
}

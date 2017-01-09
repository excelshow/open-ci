<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('load_pagination_config')) {
	function load_pagination_config($total, $page_size)
	{
		$scheme                          = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$pconfig                         = array();
		$pconfig['base_url']             = $scheme . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'])[0];// . '?page=' . $page;
		$pconfig['total_rows']           = $total;
		$pconfig['per_page']             = $page_size;
		$pconfig['page_query_string']    = true;
		$pconfig['enable_query_strings'] = true;
		$pconfig['reuse_query_string']   = true;
		$pconfig['use_page_numbers']     = true;
		$pconfig['full_tag_open']        = '<ul class="pagination pull-right">';
		$pconfig['full_tag_close']       = '</ul>';
		$pconfig['prev_tag_open']        = '<li>';
		$pconfig['prev_tag_close']       = '</li>';
		$pconfig['next_tag_open']        = '<li>';
		$pconfig['next_tag_close']       = '</li>';
		$pconfig['prev_link']            = '&lt;&lt;';
		$pconfig['next_link']            = '&gt;&gt;';
		$pconfig['first_tag_open']       = '<li>';
		$pconfig['first_tag_close']      = '</li>';
		$pconfig['last_tag_open']        = '<li>';
		$pconfig['last_tag_close']       = '</li>';
		$pconfig['num_tag_open']         = '<li>';
		$pconfig['num_tag_close']        = '</li>';
		$pconfig['cur_tag_open']         = '<li class="active"><a href="#">';
		$pconfig['cur_tag_close']        = '</a></li>';

		return $pconfig;
	}
}

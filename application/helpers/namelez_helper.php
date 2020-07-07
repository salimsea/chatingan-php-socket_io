<?php

if (!function_exists('auth_check')) {
	function auth_check()
	{
		$ci =& get_instance();
		return $ci->auth_model->is_logged_in();
	}
}

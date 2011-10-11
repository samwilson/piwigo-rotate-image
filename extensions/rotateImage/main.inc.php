<?php
/*
Plugin Name: Rotate Image
Version: 0.1
Description: enables to rotate images in batch processing
Plugin URI: http://www.phpwebgallery.net
*/
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

add_event_handler('loc_begin_element_set_global', 'rotate_image_set_template_data');
add_event_handler('ws_add_methods', 'add_image_rotate_method');

function add_image_rotate_method($arr)
{
  include_once('ws_functions.inc.php');
}

function rotate_image_set_template_data() {
	global $template,$lang;
    load_language('plugin.lang', dirname(__FILE__).'/');
	$template->set_filename('rotate_image', realpath(dirname(__FILE__).'/rotate_image.tpl'));
	$template->append('element_set_global_plugins_actions', array(
    'ID' => 'rotateImg',
    'NAME' => l10n('Rotate images'),
    'CONTENT' => $template->parse('rotate_image', true),
    )
  );
}



?>
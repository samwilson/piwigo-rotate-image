<?php
/*
Plugin Name: Rotate Image
Version: 0.4
Description: enables to rotate images in batch processing
Plugin URI: http://fr.piwigo.org/ext/extension_view.php?eid=578
*/
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

add_event_handler('loc_begin_element_set_global', 'rotate_image_set_template_data');
add_event_handler('ws_add_methods', 'add_image_rotate_method');
add_event_handler('element_set_global_action', 'rotate_image_element_action', 50, 2);

function add_image_rotate_method($arr)
{
 include_once('ws_functions.inc.php');
}

function rotate_image_set_template_data() {
  global $template,$lang;
  load_language('plugin.lang', dirname(__FILE__).'/');
  $angles = array (
    array('value' => 90, 'name' => l10n('90° left')),
    array('value' => 270, 'name' => l10n('90° right')),
    array('value' => 180, 'name' => l10n('180°'))
  );
  
  $template->assign(array(
    'RI_PWG_TOKEN' => get_pwg_token(),
    'angles' => $angles,
    'angle_value' => 90,
    'library' => pwg_image::get_library()
  ));
  $template->set_filename('rotate_image', realpath(dirname(__FILE__).'/rotate_image.tpl'));
  $template->append('element_set_global_plugins_actions', array(
    'ID' => 'rotateImg',
    'NAME' => l10n('Rotate images'),
    'CONTENT' => $template->parse('rotate_image', true))
  );
}

function rotate_image_element_action($action, $collection) {
  global $template;
  if ($action == 'rotateImg') {
    //flush thumbnails links by regenerating ramdom ids to uris
    $template->delete_compiled_templates();
  }
}

?>
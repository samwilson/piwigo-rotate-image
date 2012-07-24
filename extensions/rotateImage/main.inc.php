<?php
/*
Plugin Name: Rotate Image
Version: auto
Description: enables to rotate images in batch processing
Plugin URI: http://fr.piwigo.org/ext/extension_view.php?eid=578
*/
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

add_event_handler('ws_add_methods', 'add_image_rotate_method');
function add_image_rotate_method($arr)
{
 include_once('ws_functions.inc.php');
}

add_event_handler('loc_begin_element_set_global', 'rotate_image_set_template_data');
function rotate_image_set_template_data() {
  global $template,$lang;
  load_language('plugin.lang', dirname(__FILE__).'/');

  include_once(PHPWG_ROOT_PATH.'admin/include/image.class.php');

  $angles = array (
    array('value' => 270, 'name' => l10n('90° right')),
    array('value' =>  90, 'name' => l10n('90° left')),
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

add_event_handler('element_set_global_action', 'rotate_image_element_action', 50, 2);
function rotate_image_element_action($action, $collection) {
  if ($action == 'rotateImg') {
    add_event_handler('get_derivative_url', 'rotate_image_force_refresh', EVENT_HANDLER_PRIORITY_NEUTRAL, 4);
  }
}

function rotate_image_force_refresh($root_url, $params, $src_image, $rel_url)
{
  global $collection;

  if (in_array($src_image->id, $collection))
  {
    if (strpos($root_url, '?') === false)
    {
      $root_url.= '?';
    }
    else
    {
      $root_url.= '&amp;';
    }

    $root_url.= 'rand='.md5(uniqid(rand(), true));
  }

  return $root_url;
}
?>

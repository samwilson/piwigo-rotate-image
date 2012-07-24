<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once(dirname(__FILE__).'/functions.inc.php');

$service = &$arr[0];
$service->addMethod('pwg.image.rotate', 'ws_image_rotate',
  array(
  'image_id'=>array(),
  'angle'=>array('default'=>"90"),
  'pwg_token' => array(),
  'rotate_hd' => array('default'=>0)
  ),
  'Rotates a given image'
);

function ws_image_rotate($params, &$service)
{
  global $conf;
  
  if (!is_admin())
  {
    return new PwgError(401, 'Access denied');
  }

  if (empty($params['image_id']))
  {
    return new PwgError(403, "image_id or image_path is missing");
  }

  /* if (empty($params['pwg_token']) or get_pwg_token() != $params['pwg_token']) */
  /* { */
  /*   return new PwgError(403, 'Invalid security token'); */
  /* } */

  $image_id=(int)$params['image_id'];

  $query='
SELECT
    id
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$image_id.'
;';
  $row = pwg_db_fetch_assoc(pwg_query($query));
  if ($row == null)
  {
    return new PwgError(403, "image_id not found");
  }

  rotate_image($image_id, get_boolean($params['rotate_hd']), $params['angle']);
  
  return true;
}

?>
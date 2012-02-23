<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');


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
  if (!is_admin())
  {
    return new PwgError(401, 'Access denied');
  }

  if (empty($params['image_id']))
  {
    return new PwgError(403, "image_id or image_path is missing");
  }

  if (empty($params['pwg_token']) or get_pwg_token() != $params['pwg_token'])
  {
    return new PwgError(403, 'Invalid security token');
  }

  include_once(PHPWG_ROOT_PATH.'admin/include/functions_upload.inc.php');
  include_once(PHPWG_ROOT_PATH.'admin/include/image.class.php');
  $image_id=(int)$params['image_id'];
  $angle=(int)$params['angle'];
  $rotate_hd = get_boolean($params['rotate_hd']);
  $query='
SELECT id, path, tn_ext, has_high
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$image_id.'
;';
  $image = pwg_db_fetch_assoc(pwg_query($query));
  if ($image == null)
  {
    return new PwgError(403, "image_id not found");
  }

  $image_path = $image['path'];

    
  $thumb_path = get_thumbnail_path($image);

  $img = new pwg_image($image_path);
  $img->rotate($angle);
  $img->write($image_path);
  update_metadata(array($image_id=>$image_path));
  if ($rotate_hd) {
    $sizes = array('thumb','high');
  } else {
    $sizes = array('thumb');
  }
  
  foreach ($sizes as $size) {
    $resized_path = file_path_for_type($image_path,$size);
    if (file_exists($resized_path)) {
      $resized = new pwg_image($resized_path);
      $resized->rotate($angle);
      $resized->write($resized_path);
    }
  }
  return true;
}

?>
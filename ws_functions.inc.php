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
  global $conf;
  
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

  // rotation angle
  if ('auto' == $params['angle']) {
    $angle = $params['angle'];
  }
  else {
    $angle = (int)$params['angle'];
  }

  if (get_boolean($params['rotate_hd']) and get_boolean($image['has_high'])) {
    $to_rotate_path = file_path_for_type($image['path'], 'high');
    $quality = $conf['upload_form_hd_quality'];
    $regenerate_websize = true;

    if ('auto' == $angle) {
      $angle = pwg_image::get_rotation_angle($to_rotate_path);
    }
  }
  else {
    $to_rotate_path = $image['path'];
    $quality = $conf['upload_form_websize_quality'];
    $regenerate_websize = false;
  }

  $rotated = new pwg_image($to_rotate_path);
  $rotated->set_compression_quality($quality);
  $rotated->rotate($angle);
  $rotated->write($to_rotate_path);

  if ($regenerate_websize) {
    ws_images_resizewebsize(
      array(
        'image_id' => $params['image_id'],
        'maxwidth' => $conf['upload_form_websize_maxwidth'],
        'maxheight' => $conf['upload_form_websize_maxheight'],
        'quality' => $conf['upload_form_websize_quality'],
        'automatic_rotation' => $conf['upload_form_automatic_rotation'],
        'library' => $conf['graphics_library'],
        ),
      &$service
      );
  }

  ws_images_resizethumbnail(
    array(
      'image_id' => $params['image_id'],
      'maxwidth' => $conf['upload_form_thumb_maxwidth'],
      'maxheight' => $conf['upload_form_thumb_maxheight'],
      'quality' => $conf['upload_form_thumb_quality'],
      'crop' => $conf['upload_form_thumb_crop'],
      'follow_orientation' => $conf['upload_form_thumb_follow_orientation'],
      'library' => $conf['graphics_library'],
      ),
    &$service
    );

  $conf['use_exif'] = false;
  $conf['use_iptc'] = false;
  update_metadata(array($image['id'] => $image['path']));
  
  return true;
}

?>
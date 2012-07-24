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

  include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');
  include_once(PHPWG_ROOT_PATH.'admin/include/image.class.php');

  $image_id=(int)$params['image_id'];
  
  $query='
SELECT
    id,
    path,
    representative_ext,
    rotation
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$image_id.'
;';
  $row = pwg_db_fetch_assoc(pwg_query($query));
  if ($row == null)
  {
    return new PwgError(403, "image_id not found");
  }

  $base_angle = pwg_image::get_rotation_angle_from_code($row['rotation']);

  if (get_boolean($params['rotate_hd'])) {
    if ('auto' == $angle) {
      $angle = pwg_image::get_rotation_angle($row['path']);
      $rotation_code = 0;
    }
    else {
      // the angle is based on what the user sees (the thumbnail) and not on
      // the original, which may have a different angle
      $angle = ($base_angle + $params['angle']) % 360;

      // the derivatives must not be rotated
      $rotation_code = 4;
    }

    if (isset($conf['rotate_image_jpegtran']) and $conf['rotate_image_jpegtran']) {
      $angle = ($angle + 180) % 360;
      $command = 'jpegtran -copy all -rotate '.$angle.' -outfile '.$row['path'].' '.$row['path'];
      exec($command);
    }
    else {
      $image = new pwg_image($row['path']);
      $image->set_compression_quality(98);
      $image->rotate($angle);
      $image->write($row['path']);
    }

    $conf['use_exif'] = false;
    $conf['use_iptc'] = false;
    sync_metadata(array($row['id']));

    single_update(
      IMAGES_TABLE,
      array('rotation' => $rotation_code),
      array('id' => $row['id'])
    );
  }
  elseif ('auto' != $params['angle']) {
    $new_angle = ($base_angle + $params['angle']) % 360;
    $rotation_code = pwg_image::get_rotation_code_from_angle($new_angle);

    // to show that it's a manual rotation, we use 4,5,6,7 instead of 0,1,2,3
    $rotation_code+= 4;

    single_update(
      IMAGES_TABLE,
      array('rotation' => $rotation_code),
      array('id' => $row['id'])
    );
  }

  delete_element_derivatives($row);
  
  return true;
}

?>
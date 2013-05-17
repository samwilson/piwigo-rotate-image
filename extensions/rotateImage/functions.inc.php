<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');
include_once(PHPWG_ROOT_PATH.'admin/include/image.class.php');

/**
 * apply rotation and update the image.rotation column
 */
function rotate_image($image_id, $rotate_hd, $angle)
{
  /* echo '$image_id = '.var_export($image_id, true).'<br>'; */
  /* echo '$rotate_hd = '.var_export($rotate_hd, true).'<br>'; */
  /* echo '$angle = '.var_export($angle, true).'<br>'; */
  global $conf;

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
    return false;
  }

  $base_angle = pwg_image::get_rotation_angle_from_code($row['rotation']);

  if ($rotate_hd) {
    if ('auto' == $angle) {
      $angle = pwg_image::get_rotation_angle($row['path']);
      $rotation_code = 0;
    }
    else {
      // the angle is based on what the user sees (the thumbnail) and not on
      // the original, which may have a different angle
      $angle = ($base_angle + $angle) % 360;

      // the derivatives must not be rotated
      $rotation_code = 4;
    }

    if (isset($conf['rotate_image_jpegtran']) and $conf['rotate_image_jpegtran']) {
      $angle = 360 - $angle;
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
  elseif ('auto' != $angle) {
    $angle = ($base_angle + $angle) % 360;
    $rotation_code = pwg_image::get_rotation_code_from_angle($angle);

    // to show that it's a manual rotation, we use 4,5,6,7 instead of 0,1,2,3
    $rotation_code+= 4;

    single_update(
      IMAGES_TABLE,
      array('rotation' => $rotation_code),
      array('id' => $row['id'])
    );
  }

  delete_element_derivatives($row);  
}
?>
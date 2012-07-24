<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2011 Piwigo Team                  http://piwigo.org |
// | Copyright(C) 2003-2008 PhpWebGallery Team    http://phpwebgallery.net |
// | Copyright(C) 2002-2003 Pierrick LE GALL   http://le-gall.net/pierrick |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');
include_once(PHPWG_ROOT_PATH.'admin/include/image.class.php');
include_once(dirname(__FILE__).'/functions.inc.php');

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_ADMINISTRATOR);

// +-----------------------------------------------------------------------+
// | Basic checks                                                          |
// +-----------------------------------------------------------------------+

$_GET['image_id'] = $_GET['tab'];

check_input_parameter('image_id', $_GET, false, PATTERN_ID);

$admin_photo_base_url = get_root_url().'admin.php?page=photo-'.$_GET['image_id'];

// +-----------------------------------------------------------------------+
// | Process form                                                          |
// +-----------------------------------------------------------------------+

load_language('plugin.lang', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');
  
if (isset($_POST['rotate']))
{
  check_pwg_token();

  $rotate_hd = false;
  if (isset($_POST['rotate_hd']))
  {
    $rotate_hd = true;
  }

  rotate_image($_GET['image_id'], $rotate_hd, $_POST['angle']);

  array_push(
    $page['infos'],
    l10n('The photo was updated')
    );
}

// +-----------------------------------------------------------------------+
// | Tabs                                                                  |
// +-----------------------------------------------------------------------+

$tabsheet = new tabsheet();
$tabsheet->set_id('photo');
$tabsheet->select('rotate');
$tabsheet->assign();

// +-----------------------------------------------------------------------+
// |                             template init                             |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

// retrieving direct information about picture
$query = '
SELECT *
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$_GET['image_id'].'
;';
$row = pwg_db_fetch_assoc(pwg_query($query));

$angles = array(
  array('value' => 270, 'name' => l10n('90° right')),
  array('value' =>  90, 'name' => l10n('90° left')),
  array('value' => 180, 'name' => l10n('180°'))
);

$template->assign(
  array(
    'TN_SRC' => DerivativeImage::thumb_url($row),
    'TITLE' => render_element_name($row),
    'angles' => $angles,
    'angle_selected' => 90,
    'library' => pwg_image::get_library(),
    'PWG_TOKEN' => get_pwg_token(),
    )
  );

// +-----------------------------------------------------------------------+
// | sending html code                                                     |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
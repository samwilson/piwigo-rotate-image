{footer_script}{literal}
jQuery(document).ready(function() {
  function autoRotateOption() {
    if (jQuery("#rotate_hd").is(':checked')) {
      jQuery("#autoRotate").show();
      jQuery("#autoRotate input").attr("checked", "checked");
    }
    else {
      jQuery("#autoRotate").hide();
      jQuery("#angleSelection input[value={/literal}{$angle_selected}{literal}]").attr("checked", "checked");
    }
  }

  autoRotateOption();
  jQuery('#rotate_hd').click(function() {
    autoRotateOption();
  });
});
{/literal}{/footer_script}


<h2>{$TITLE} &#8250; {'Edit photo'|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{'Rotate'|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail">
        <img src="{$TN_SRC}" alt="{'Thumbnail'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:top">
        <form method="post" action="">
<input type="hidden" name="pwg_token" value="{$PWG_TOKEN}">

{if $library != 'gd'}
  <p style="text-align:left; margin-top:0;"><label>
    <input type="checkbox" name="rotate_hd" id="rotate_hd" checked="checked">
    <strong>{'Also rotate HD image'|@translate}</strong>
  </label></p>
{/if}
  <p style="text-align:left; margin-top:0;" id="angleSelection">
    <strong>{'Angle'|@translate}</strong>
    <br>
{foreach from=$angles item=angle}
      <label><input type="radio" name="angle" value="{$angle.value}"{if $angle.value == $angle_selected} checked="checked"{/if}> {$angle.name}</label><br>
{/foreach}
      <label id="autoRotate" style="display:none"><input type="radio" name="angle" value="auto"> {'auto (EXIF orientation tag)'|@translate}</label>
  </p>
          <p style="text-align:left"><input class="submit" type="submit" value="{'Rotate'|@translate}" name="rotate"></p>
        </form>
      </td>
    </tr>
  </table>
</fieldset>

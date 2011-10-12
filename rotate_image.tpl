{footer_script}
var rotateImagesMessage = "{'Images rotation in progress...'|@translate}";
var ri_pwg_token = '{$RI_PWG_TOKEN}';
{literal}
  jQuery('#applyAction').click(function(e) {
    if (elements.length != 0)
    {
      return true;
    }
    else if (jQuery('[name="selectAction"]').val() == 'rotateImg')
    {
      angle = jQuery('select[name="rotate_angle"]').val();
      e.stopPropagation();
    }
    else
    {
      return true;
    }
    jQuery('.bulkAction').hide();
    jQuery('#regenerationText').html(rotateImagesMessage);
    var maxRequests=1;

    var queuedManager = jQuery.manageAjax.create('queued', { 
      queue: true,  
      cacheResponse: false,
      maxRequests: maxRequests
    });
    if (jQuery('input[name="setSelected"]').attr('checked'))
      elements = all_elements;
    else
      jQuery('input[name="selection[]"]').each(function() {
        if (jQuery(this).attr('checked')) {
          elements.push(jQuery(this).val());
        }
      });
    progressBar_max = elements.length;
    todo = 0;

    jQuery('#applyActionBlock').hide();
    jQuery('select[name="selectAction"]').hide();
    jQuery('#regenerationMsg').show();
    jQuery('#progressBar').progressBar(0, {
      max: progressBar_max,
      textFormat: 'fraction',
      boxImage: 'themes/default/images/progressbar.gif',
      barImage: 'themes/default/images/progressbg_orange.gif'
    });

    for (i=0;i<elements.length;i++) {
      queuedManager.add({
        type: 'GET', 
        url: 'ws.php', 
        data: {
          method: "pwg.image.rotate",
          format: 'json',
          angle: angle,
          pwg_token: ri_pwg_token,
          image_id: elements[i]
        },
        dataType: 'json',
        success: ( function(data) { progress(++todo, progressBar_max, data['result']) }),
        error: ( function(data) { progress(++todo, progressBar_max, false) })
      });
    }
    return false;
  });

{/literal}{/footer_script}

<div id="rotate_image" class="bulkAction">
      <table style="margin-left:20px;">
        <tr>
          <th id="thumb_width_th">{'Angle'|@translate}</th>
          <td>
          <select name="rotate_angle">
            {foreach from=$angles item=angle}
              <option value="{$angle.value}" {if $saved_angle eq $angle.value}selected="selected"{/if}>{$angle.name}</option>
            {/foreach}
          </select>
          </td>
        </tr>
        <tr>
          <th><label for="rotate_hd">{'Also rotate HD image'|@translate}</label></th>
          <td><input type="checkbox" name="rotate_hd" id="rotate_hd" {if $upload_form_settings.thumb_crop}checked="checked"{/if}></td>
        </tr>
      </table>
</div>
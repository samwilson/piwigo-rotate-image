{footer_script}
var rotateImagesMessage = "{'Images rotation in progress...'|@translate}";
var autoRotateOptionText = "{'auto (EXIF orientation tag)'|@translate}";
var ri_pwg_token = '{$RI_PWG_TOKEN}';
{literal}
jQuery(document).ready(function() {
  function autoRotateOption() {
    if (jQuery("#rotate_hd").is(':checked')) {
      jQuery("<option/>")
        .attr("id", "autoRotateOption")
        .attr("value", "auto")
        .attr("selected", "selected")
        .text(autoRotateOptionText)
        .appendTo('select[name="rotate_angle"]')
      ;
    }
    else {
      jQuery("#autoRotateOption").remove();
    }
  }

  autoRotateOption();
  jQuery('#rotate_hd').click(function() {
    autoRotateOption();
  });

  jQuery('#applyAction').click(function(e) {
    if (typeof(elements) != "undefined") {
      return true;
    }
    if (jQuery('[name="selectAction"]').val() == 'rotateImg')
    {
      angle = jQuery('select[name="rotate_angle"]').val();
      rotate_hd = jQuery("#rotate_hd").is(':checked');
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

    elements = Array();
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
          rotate_hd: rotate_hd,
          pwg_token: ri_pwg_token,
          image_id: elements[i]
        },
        dataType: 'json',
        success: ( function(data) { progressRotate(++todo, progressBar_max, data['result']) }),
        error: ( function(data) { progressRotate(++todo, progressBar_max, false) })
      });
    }
    return false;
  });

  function progressRotate(val, max, success) {
    jQuery('#progressBar').progressBar(val, {
      max: max,
      textFormat: 'fraction',
      boxImage: 'themes/default/images/progressbar.gif',
      barImage: 'themes/default/images/progressbg_orange.gif'
    });
    type = success ? 'regenerateSuccess': 'regenerateError'
    s = jQuery('[name="'+type+'"]').val();
    jQuery('[name="'+type+'"]').val(++s);

    if (val == max)
      jQuery('#applyAction').click();
  }

});
{/literal}{/footer_script}

<div id="rotate_image" class="bulkAction">
{if $library != 'gd'}
  <p><label>
    <input type="checkbox" name="rotate_hd" id="rotate_hd" checked="checked">
    <strong>{'Also rotate HD image'|@translate}</strong>
  </label></p>
{/if}
  <p><label>
    <strong>{'Angle'|@translate}</strong>
    <br>
    <select name="rotate_angle">
{foreach from=$angles item=angle}
      <option value="{$angle.value}" {if $saved_angle eq $angle.value}selected="selected"{/if}>{$angle.name}</option>
{/foreach}
    </select>
  </label></p>
</div>
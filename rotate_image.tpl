{footer_script}
var rotateImagesMessage = "{'Images rotation in progress...'|@translate}";

{literal}
  jQuery('#applyAction').click(function(e) {
    if (elements.length != 0)
    {
      return true;
    }
    else if (jQuery('[name="selectAction"]').val() == 'rotateImg')
    {
      angle = jQuery('input[name="rotate_angle"]:checked').val();
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
  <p>{'Select angle :'|@translate}</p>
  <label><input type="radio" name="rotate_angle" value="90" checked="checked"> {'Rotate 90° left'|@translate}</label>
  <label><input type="radio" name="rotate_angle" value="270"> {'Rotate 90° right'|@translate}</label>
  <label><input type="radio" name="rotate_angle" value="180"> {'Rotate 180°'|@translate}</label>
</div>
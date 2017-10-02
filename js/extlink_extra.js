//colorbox-inline
(function ($, Drupal, drupalSettings) {
  
Drupal.behaviors.extlink_extra = {
  attach: function(context){
    //Unbind extlink's click handler and add our own
    jQuery('a.ext').unbind('click').not('.ext-override').click(function(e){
      //This is what extlink does by default (except
      if(drupalSettings.extlink_extra.extlink_alert_type == 'confirm') {
        return confirm(drupalSettings.extlink.extAlertText.value);
      }
      
      var external_url = jQuery(this).attr('href');
      var back_url = window.location.href;//window.location.protocol + window.location.hostname + window.location.pathname;
      var alerturl = 'now-leaving';//'now-leaving?external_url='+external_url+'&back_url='+back_url;
      $.cookie("external_url", external_url, { path: '/' });
	    $.cookie("back_link", back_url, { path: '/' });
      
	    if(drupalSettings.extlink_extra.extlink_alert_type == 'colorbox') {
        jQuery.colorbox({
          href:alerturl+'?js=1 .extlink-extra-leaving', 
          height: '50%', 
          width:'50%',
          initialWidth:'50%',
          initialHeight:'50%',
          onComplete:function(){
            //Allow our cancel link to close the colorbox
            jQuery('div.extlink-extra-back-action a').click(function(e){jQuery.colorbox.close(); return false;})
            extlink_extra_timer();
          },
          onClosed:extlink_stop_timer
        });
        return false;
	    }
	    
	    if(drupalSettings.extlink_extra.extlink_alert_type == 'page') {
	      //If we're here, alert text is on but pop-up is off; we should redirect to an intermediate confirm page
	      window.location = alerturl;
	      return false;
	    }
    });
  }
}

})(jQuery, Drupal, drupalSettings);

//Global var that will be our JS interval
var extlink_int;

function extlink_extra_timer() {
  if(drupalSettings.extlink_extra.extlink_alert_timer == 0 || drupalSettings.extlink_extra.extlink_alert_timer ==  null) {
    return;
  }
  extlink_int = setInterval(function(){
    var container = jQuery('.automatic-redirect-countdown');
    var count = container.attr('rel');
    if(count == null) {
      count = drupalSettings.extlink_extra.extlink_alert_timer;
    }
    if(count >= 0) {
      container.html('<span class="extlink-timer-text">Automatically redirecting in: </span><span class="extlink-count">'+count+'</span><span class="extlink-timer-text"> seconds.</span>');
      container.attr('rel',--count);
    }
    else {
      extlink_stop_timer();
      container.remove();
      var href = jQuery('div.extlink-extra-go-action a').attr('href');  
      window.location = href;
    }
  },1000);
}

function extlink_stop_timer() {
  clearInterval(extlink_int);
}
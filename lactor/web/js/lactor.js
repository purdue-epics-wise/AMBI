lactor = [];
(function(L, $) {
  $(function( ) {
    L.init();
    // set up the notification animation.
    $('#warnmessage').css('right', '-1024px');
    $('#warnmessage').animate({right:0});
    $('#warnmessage').click(function( ) {
      var $this = $(this);
      if ($this.hasClass('collapsed')) {
        $this.animate({ right:0}, 250);
      } else {
        $this.animate({ right: 18 - $this.outerWidth( ) }, 250);
      }
      $this.toggleClass('collapsed');
    });

    // get the unread message count
    $.get('services/messages.php?count', function(data){
      if (data.count.unread > 0) {
        var inboxTab = $('a.inbox')
        inboxTab.html(inboxTab.html() + " (" + data.count.unread + ")");
      }
    })
  });

  L.handleMessage = function(dataObject) {
    if (dataObject.message)
      $('.successMessage').html(dataObject.message).css('display','');
    else
      $('.successMessage').css('display','none');
    if (dataObject.error)
      $('.errorMessage').html(dataObject.error).css('display','');
    else
      $('.errorMessage').css('display','none');
  }

  L.init = function() {
    // initialize translations
    var lang = $.cookie('lang');
    if (lang && lang != 'en')
      $.translate.load('includes/locale/'+lang+'/LC_MESSAGES/lactor.json');
//    // set up the form storage area
//    if (localstorage['forms'][location.pathname]) {
//      L.formData = JSON.parse(localstorage['forms'])[location.pathname]
//    } else {
//      L.formData = {}
//    }
  }

  L.syncStorage = function() {
//    localstorage['forms'][location.pathname] = JSON.stringify(L.formData)
  }

})(lactor, jQuery);


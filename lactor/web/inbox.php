<!DOCTYPE html>
<?php 
include_once("includes/general.php");
include_once("includes/db.include.php");

initialize();
loggedIn();
db_connect();

loadVocabulary();

?>


<head>
<?php head_tag("LACTOR - "._("Inbox")); ?>
</head>

<body>
<div id="maincontainer">

<?php page_header(); ?>
<?php page_menu(PAGE_INBOX); ?>

		
<!-- Page Content -->
<div id="pagecontent">
<div class='message dynamic' style='display:none'></div>
<?php if(isset($_SESSION['s_mid'])) {
	$_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
	displayMessage('Smessage', 'Sdetail', 'Stype');
} ?>
<!-- form box -->
<br />
<div id="registercontent">
<div id="container">
<div class="tabs">
<ul class="menu">
<li class="active"><a href="#current"><?php echo _("New") ?></a></li>
<li ><a href="#past"><?php echo _("Archived") ?></a></li>
<li ><a id='composeTab' href="#compose"><?php echo _("Compose") ?></a></li>
</ul>

<div id="current" class="content">
<h1><img src="image/mail-closed.png" width=48 height=35 alt=""/> <?php echo _("Unread Messages") ?></h1>
<div class='mailContainer' id='unreadMail'></div>
</div>

<div id="past" class="content">
<h1><img src="image/mail-message.png" width=48 height=48 alt=""/> <?php echo _("Read Messages") ?></h1>
<div class='mailContainer' id='readMail'></div>
</div>

<div id="compose" class="content">
<h1><img src="image/text_generic_with_pencil.png" width=48 height=48 alt=""/> <?php echo _("Compose Message") ?></h1>
<!--
<div>
  <?php echo _("To") ?>: 
  <span id='scienceEmail'>
  </span></div><br />
-->
<form name='composeMessage' method='post' action='services/send_message.php'>
<textarea style='width:80%;height:15em;padding:1em;' id='messageBody' name='message'></textarea><br /><br />
<button type='submit'><?php echo _('Send Message'); ?></button>
</form>
</div>
</div>

</div>
<p>
<input type="hidden" id="mothersubmit" value="Add Entry" name="breast" tabindex="12"  accesskey="u" />
</p>



</div>
</div>

<?php page_footer(); ?>

</div>
<script type='text/javascript'>
$(function() {

  var getMail = function() {
    $.get("services/messages.php", function(data) {
      for (i in data.messages) {
        var messageData = "";
        var message = data.messages[i];
        if (!message.sent) {
          var date = new Date(message.timestamp * 1000);
          messageData += "<div class='mail' id='mailDiv" + message.id +
                  "' data-timestamp='" + message.timestamp + "'>" + 
               "<div class='mailFrom'><?php echo _("From") ?>: "+
               "<span class='mailFrom'>"+message.from+"</span></div>" + 
               "<div class='mailDate'>"+date.smartFormat()+"</div>" + 
               "<div class='mailContent'>"+message.message+"</div>";

          if (!message.seen) {
            messageData += "<button onclick='markRead(this)'><?php echo _("Mark as read") ?></button>";
          }
          messageData += "&nbsp;<button onclick='mailReply(this)'><?php echo _("Reply") ?></button></div>";
          var mailTab = $('#'+(message.seen ? 'read' : 'unread')+'Mail')
          mailTab.html(mailTab.html() + messageData);
        }
      }
    }, 'json');
  };
  getMail(); 
//  setInterval(function(){ getMail(); }, 10000);

  $('[name="composeMessage"]').submit( function () {
    $.post($(this).attr('action'), "message=" + $('#messageBody').val(),
       function(data) {
         var statusbox = $('.dynamic.message')
         statusbox.html(statusbox.html() + data.message + '<br />').css('display','');
        $('#messageBody').val('');
       },
       'json');
    return false;
  });
});

// a function for marking mail as read
function markRead(element) {
  var mailDiv = $(element).parent('.mail');
  var id = /mailDiv([0-9]+)/.exec(mailDiv.attr('id'))[1];
  $.get("services/messages.php?markRead="+id, function(data) {
    mailDiv.animate( { 'height' : 0 }, 200, function() {
      $(element).remove();
      $('#readMail').prepend(mailDiv);
      mailDiv.css('height', '');
    });
  }, 'json');
};

function mailReply(element) {
  $('#composeTab').click(); 
  $('#messageBody').select();
};

</script>
</body>
</html>
	

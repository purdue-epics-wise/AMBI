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
<?php head_tag("LACTOR - "._("Notifications")); ?>
</head>

<body>
  <div id="maincontainer">

    <?php page_header(); ?>
    <?php page_menu(PAGE_NOTIFICATIONS); ?>
    <!-- Page Content -->
    <div id="pagecontent">
      <?php if(isset($_SESSION['s_mid'])) {
        $_SESSION['Smessage']="Logged in as scientist.";$_SESSION['Stype']=2;$_SESSION['Sdetail']="";
        displayMessage('Smessage', 'Sdetail', 'Stype');
      } ?>
      <!-- form box -->
      <br />

      <div id="registercontent">
        <div class='errorMessage' style='display:none'></div>
        <div class='successMessage' style='display:none'></div>
        <div id="container">
          <div class="tabs">
          <ul class="menu">
            <li id="breastfeeding" class="active"><a href="#current"><?php echo _("Current") ?></a></li>
            <li id="supplement"><a href="#past"><?php echo _("Past") ?></a></li>
          </ul>
          <div id="current" class="content breastfeeding">
            <h1><img src="image/notification.gif" width=48 height=48 alt=""/> <span data-translate="true">Current Notifications</span></h1>
            <div class='notificationText'>
            </div>
            <!--
            <?php
              $query = "SELECT * FROM Notifications WHERE mid = " . $_SESSION['mid'] . " AND status = 1 ORDER BY NotificationIssued DESC;";
              $result = mysql_query($query);
              while($row = mysql_fetch_array($result))
              {
                echo "<b>"._("Date &amp; Time").":</b> " . getDateTime($row['NotificationIssued']) . "    <br /><b>"._("Problem").":</b> ".notificationTitle($row['ntype'])."<br />";
                echo notificationText($row['ntype']);
                echo '<br />';
                echo "<form method=\"post\" action=\"post/notifications.post.php\"><input type=\"hidden\" name=\"MarkRead\" value=\"" . $row['nid'] . "\"/><input value=\""._("Mark As Read")."\" type=\"submit\"></form><br/>";
              }

            ?>
            -->
          </div>

          <div id="past" class="content supplement">
            <h1><img src="image/rnotification.gif" width=48 height=48 alt=""/> <?php echo _("Read Notifications") ?></h1>
            <div class='notificationText'>
            </div>
            <!--
            <?php
            $query = "SELECT * FROM Notifications WHERE mid = " . $_SESSION['mid'] . " AND status = 2 ORDER BY NotificationIssued DESC;";
            $result = mysql_query($query);
            while($row = mysql_fetch_array($result))
            {
              echo "<b>"._("Date &amp; Time").":</b> " . getDateTime($row['NotificationIssued']) . "    <br /><b>"._("Problem").":</b> ".notificationTitle($row['ntype'])."<br />";
              echo notificationText($row['ntype']);
            }

            ?>
            -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php page_footer(); ?>
  </div>
  <script type='text/javascript' src='js/notifications.js'></script>
  <script type='text/javascript'>
    $(function() {
      // sort notifications based on read status and put them in the 
      // appropriate tabs
      var current = $('#current .notificationText');
      var past = $('#past .notificationText');
      $.get('services/notifications.php?status=3', function(data) {
        for (var i in data) {
          // grab the text for each notificaton from this array
          var text = lactor.notificationText[data[i].type - 1];
          var messageHTML = '<b><span>' + jQuery.translate('Date &amp; Time') + 
              '</span>: </b>' + 
              new Date(data[i].issued * 1000).smartFormat() +  '<br>' + 
              '<b><span>' + $.translate('Problem') + '</span>: </b>' + 
              '<span>' + $.translate(text.title) + "</span><br>";
          messageHTML += '<ol>';
          for (var j in text.steps) {
            messageHTML += '<li>'+ jQuery.translate(text.steps[j])+'</li>';
          }
          messageHTML += '</ol>';
          if (data[i].status == 1) { // unread
            current.append($('<div data-id="'+data[i].id+'">' + messageHTML + 
              '<button type="button" data-id="' + data[i].id +
              '" class="mark">' + $.translate('Mark as read') + 
              '</b></button><br><br></div>'));
          } else {
            past.append($('<div>' + messageHTML + '<br></div>'));
          }
          // translate the text if needed.
        }
        $('button.mark').click(function() {
          var msgId = $(this).attr('data-id');
          $.get('services/notifications.php?markRead=' + msgId,
            (function(id) { return function(response) {
              lactor.handleMessage(response);
              if (!response.error) {
                var past = $('#past .notificationText');
                $('button[data-id="'+id+'"]').detach();
                past.prepend($('div[data-id="'+id+'"]'));
              }
            }})(msgId), 'json');
        });
      }, 'json');
    });
  </script>
</body>
</html>
	
	

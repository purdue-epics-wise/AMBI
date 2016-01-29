<?php 
include_once("includes/general.php");
include_once("includes/db.php");

initialize();
loggedIn();
$db = db_connect();

loadVocabulary();

?>

<head>
<?php head_tag("LACTOR - "._("Add Entry")); ?>


</head>



<body>
<div id="maincontainer">


<?php page_header(); ?>
<?php page_menu(PAGE_ADD_ENTRY); ?>

<div id="pagecontent">
<div id="registercontent">

<?php 

$error_message = "";
$success_message = "";
$active_tab = "breastfeeding";
$hour = isset($_POST[ 'entryhour' ]) ? $_POST[ 'entryhour' ] : date( 'g' );
$minute = isset( $_POST[ 'entryminute' ]) ? $_POST[ 'entryminute' ] : date( 'i' );
$minute -= $minute % 5; // round down the the closest 5 minute increment.
$ampm = isset( $_POST[ 'entryam' ]) ? ($_POST[ 'entryam' ] == "01" ? "AM" : "PM") : date( 'A' );
$today = "SELECTED";
$yesterday = "";
if(@$_POST['which'] == "00") {
	$today = "";
	$yesterday = "SELECTED";
}

displayNotification();

?>


<div class="tabs">
  <ul class="menu">
    <li class="active"><a href="#time"><?php echo _("Time") ?></a></li>
  </ul>
  <div id="time" class="content time">
    <div id="entrycontent">
      <div id="entryinput">
        <form action="add_entry.php" method="post" name='timeEntry'>
          <br/>
            <?php
            echo "<span style='margin-left: 100px;'>"._("Time")."</span>: <select name=\"entryhour\">";
            for ( $i=1; $i <= 12; $i++ ) {
              echo "<option " . (( $hour ==  $i ) ? "selected" : "" ) . " value=\"$i\">$i</option>";
            }
            echo "</select>:";

            echo "<select name=\"entryminute\">";
            for ( $i=0; $i <= 60; $i+=5 ) {
              echo "<option " . (( $minute == $i ) ? "selected" : "" ) . 
                " value=\"$i\">" . (( $i < 10 ) ? "0" : "" ) . "$i</option>";
            }
            echo "</select> ";

            echo "<select name=\"entryam\">";
            echo "<option " . ( $ampm == 'AM' ? 'selected' : '' ) . " value=\"01\">"._("AM")."</option>";
            echo "<option " . ( $ampm == 'PM' ? 'selected' : '' ) . " value=\"00\">"._("PM")."</option>";
            echo "</select> ";




            echo "<span style='margin-left:100px;'>"._("Day").":</span> <select name=\"which\">";
            echo "<option " . $today . " value=\"01\">"._("Today")."</option>";
            echo "<option " . $yesterday . " value=\"00\">"._("Yesterday")."</option>";
            echo "</select> ";



          ?>
        </form>
      </div>
    </div>
  </div>
</div>

<br />
<div class='errorMessage' style='display:none'></div>
<div class='successMessage' style='display:none'></div>
<?php
//if ( !empty( $error_message )) {
//	echo "<div class='errorMessage'>$error_message</div><br />";
//}
//if ( !empty( $success_message )) {
//	echo "<div class='successMessage'>$success_message</div><br />";
//}
?>
<br />

<div id="entryData" class="tabs">
<ul class="menu tabs">
	<li id="breastfeedingTab"><a href="#breastfeeding" data-translate='true'><?php echo _("Breastfeeding") ?></a></li>
	<li id="pumpingTab"><a href="#pumping" data-translate='true'><?php echo _("Pumping") ?></a></li>
	<li id="supplementTab"><a href="#supplement" data-translate='true'><?php echo _("Supplement") ?></a></li>
	<li id="outputTab"><a href="#output" data-translate='true'><?php echo _("Output") ?></a></li>
	<li id="weghtTab"><a href="#weight" data-translate='true'><?php echo _("Infant Weight") ?></a></li>
	<li id="healthTab"><a href="#health" data-translate='true'><?php echo _("Health Issues") ?></a></li>
</ul>
<!-- =================================== BREASTFEEDING FORM ================================================ -->
<div id="breastfeeding" class="content breastfeeding">
  <h1><img src="image/babyface.gif" width=48 height=48 alt=""/> 
   <span data-translate='true'><?php echo _("Breastfeeding Entry") ?></span>
  </h1>
  <form action='' name='breastfeedingForm'>
    <table border='0'><tbody>
      <tr><td><?php echo _("Breastfeeding duration") ?>:</td><td><?php echo _("Left") ?>:<select class="standardselect" name="duration_left">
      <?php 
        echo selectControlledVocabulary("duration", @$_POST[ 'duration_left' ]);
      ?></select> <?php echo _("Right") ?>: <select class="standardselect" name="duration_right">
      <?php
        echo selectControlledVocabulary("duration", @$_POST[ 'duration_right' ]);
      ?>
      </select></td></tr>

      <tr><td><?php echo _("Latching") ?>:</td><td><select class="standardselect" name="latching">
      <?php 
        echo selectControlledVocabulary("latching", @$_POST[ 'latching' ]);
      ?>
      </select></td></tr>

      <tr><td><?php echo _("Infant alertness") ?>:</td><td><select class="standardselect" name="infant_state">
      <?php 
        echo selectControlledVocabulary("infant-state", @$_POST[ 'infant_state' ]);
      ?>
      </select></td></tr>

      <tr><td><?php echo _("Maternal breastfeeding problems") ?>:</td><td><select class="standardselect" name="maternal_problems">
      <?php 
        echo selectControlledVocabulary("maternal-problems", @$_POST[ 'maternal_problems' ]);
      ?>
      </select></td></tr>
    </tbody></table>

    <p>
      <input type='hidden' name='breast' value='1'>
      <button type="submit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

<!-- =================================== PUMPING FORM ================================================ -->
<div id="pumping" class="content pumping">
  <h1><img src="image/babyface.gif" width=48 height=48 alt=""/>
    <span data-translate='true'><?php echo _("Pumping"); ?></span>
  </h1>
  <form action='' name='pumpingForm'>
    <table border='0'><tbody>

    <tr><td><?php echo _("Pumping method") ?>:</td><td><select class="standardselect" name="pumping_method">
    <?php echo selectControlledVocabulary("pumping-method", @$_POST[ 'pumping_method' ]); ?>
    </select></td></tr>

    <tr><td><?php echo _("Pumping duration") ?>:</td><td><select class="standardselect" name="pumping_duration">
    <?php 
      echo selectControlledVocabulary("duration", @$_POST[ 'pumping_duration' ]);
    ?>
    </select></td></tr>

    <tr><td><?php echo _("Pumping Amount"); ?>:</td><td><select class="standardselect" name="pumping_amount">
    <?php 
      echo selectControlledVocabulary("TotalAmount", @$_POST[ 'pumping_amount' ]);
    ?>
    </select></td></tr>
    </tbody></table>
    <p>
      <input type='hidden' name='pump' value='1'>
      <button type="submit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

<!-- =================================== SUPPLEMENT FORM ================================================ -->
<div id="supplement" class="content supplement">
  <h1><img src="image/supplement.gif" width=48 height=48 alt=""/> <?php echo _("Supplement Entry") ?></h1>
  <form action='' name='supplementForm'>
    <table border='0'><tbody>
      <tr>
        <td data-translate='true'><?php echo _("Type") ?>:</td>
        <td><select class="standardselect" name="sup_type">
          <?php 
            echo selectControlledVocabulary("sup-type", @$_POST[ 'sup_type' ]);
          ?>
          </select>
        </td>
      </tr>

      <tr>
        <td data-translate='true'><?php echo _("Method") ?>:</td>
        <td><select class="standardselect" name="sup_method">
          <?php 
            echo selectControlledVocabulary("sup-method", @$_POST[ 'sup_method' ]);
          ?>
          </select>
        </td>
      </tr>

      <tr>
        <td data-translate='true'><?php echo _("Frequency") ?>:</td>
        <td><select class="standardselect" name="NumberTimes">
          <?php 
            echo selectControlledVocabulary("NumberTimes", @$_POST[ 'NumberTimes' ]);
          ?>
          </select>
        </td>
      </tr>

      <tr>
        <td data-translate='true'><?php echo _("Total Amount Today") ?>:</td>
        <td><select class="standardselect" name="TotalAmount">
          <?php 
            echo selectControlledVocabulary("TotalAmount", @$_POST[ 'TotalAmount' ]);
          ?>
          </select>
        </td>
      </tr>
    </tbody></table>

    <p>
      <input type='hidden' name='sup' value=1'>
      <button type="submit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

<!-- =================================== OUTPUT FORM ================================================ -->
<div id="output" class="content output">
  <h1><img src="image/output.gif" width=48 height=48 alt=""/> <?php echo _("Output Entry") ?></h1>
  <span data-tranlsate='true'><?php echo _("NOTE: Please add different entries for different forms of output.") ?></span><br />
  <form action='' name='outputForm'>
    <table border='0'><tbody>
      <tr><td data-translate='true'><?php echo _("Number of Diapers") ?>:</td><td><select class="standardselect" name="NumberDiapers">
      <?php 
        echo selectControlledVocabulary("NumberDiapers", @$_POST[ 'NumberDiapers' ]);
      ?>
      </select></td></tr>

      <br />
      <tr><td><?php echo _("Urine Color") ?>:</td><td><select class="standardselect" name="out_u_color">
      <?php 
        echo selectControlledVocabulary("out-u-color", @$_POST[ 'out_u_color' ]);
      ?>
      </select></td></tr>

      <tr><td><?php echo _("Urine Saturation") ?>:</td><td><select class="standardselect" name="out_u_saturation">
      <?php 
        echo selectControlledVocabulary("out-u-saturation", @$_POST[ 'out_u_saturation' ]);
      ?>
      </select></td></tr>

      <br/>
      <tr><td><?php echo _("Stool Color") ?>:</td><td><select class="standardselect" name="out_s_color">
      <?php 
        echo selectControlledVocabulary("out-s-color", @$_POST[ 'out_s_color']);
      ?>
      </select></td></tr>

      <tr><td><?php echo _("Stool Consistency") ?>:</td><td><select class="standardselect" name="out_s_consistency">
      <?php 
        echo selectControlledVocabulary("out-s-consistency", @$_POST[ 'out_s_consistency' ]);
      ?>
      </select></td></tr>
    </tbody></table>

    <p>
      <input type='hidden' name='out' value='1'>
      <button type="submit" id="mothersubmit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

<!-- =================================== INFANT WEIGHT FORM ================================================ -->
<div id="weight" class="content weight">
  <h1><img src="image/output.gif" width=48 height=48 alt=""/> <?php echo _("Infant Weight") ?></h1>
  <form action='' name='weightForm'>
    <table border='0'><tbody>
      <tr><td><?php echo _("Pounds") ?>:</td><td><select id="weight-lbs" name="weight-lbs">
      <?php 
        $default = 8;
        for ($i=0; $i <= 25; $i++) {
          $selected = ($i == $default) ? "selected" : "";
          echo "<option $selected value='$i'>$i</option>";
        }
      ?>
      </select></td>
      <td data-translate='true'><?php echo _("Ounces") ?>:</td><td><select id="weight-oz" name="weight-oz">
      <?php 
        $default = 0;
        for ($i=0; $i < 16; $i++) {
          $selected = ($i == $default) ? "selected" : "";
          echo "<option $selected value='$i'>$i</option>";
        }
      ?>
      </select></td></tr>
    </tbody></table>

    <p>
      <input type='hidden' name='weight' value='1'>
      <button type="submit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

<!-- =================================== HEALTH FORM ================================================ -->
<div id="health" class="content health">
  <h1><img src="image/morbidity.gif" width=48 height=48 alt=""/>
    <span data-translate='true'><?php echo _("Health Issue Entry") ?></span>
  </h1>
  <form action='' name='healthForm'>
    <table border='0'><tbody>
      <tr><td><?php echo _("Type") ?>:</td><td><select id="morb-type" name="morb_type">
      <?php 
        echo selectControlledVocabulary("morb-type", @$_POST[ 'morb_type' ]);
      ?>
      </select></td></tr>
    </tbody></table>

    <p>
      <input type='hidden' name='morb' value='1' />
      <button type="submit" id="mothersubmit" data-translate='true'><?php echo _("Add Entry") ?></button>
    </p>
  </form>
</div>

</div>
</div>


</form>

<script type="text/javascript"><!--
$(function() {
	$('#entryData > ul > li > a').click( function() {
    location.hash = $(this).attr('href');
//		$('form.long')[0].reset( );
	});
  $('#<?php echo $active_tab; ?>Tab').click();

  $('form').submit(function(evt) {
    evt.preventDefault();
    args = $(this).serialize() + '&' + $('form[name="timeEntry"]').serialize();
    $.get('services/add_entry.php', args, function(data) {
      if (data.message || data.error) {
        lactor.handleMessage(data)
      }
    });
  });
});
//--></script>

</div>


<?php 
	page_footer(); 
?>

</div>
</body>
</html>



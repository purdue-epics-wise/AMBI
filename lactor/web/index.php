<?php 

include_once("includes/general.php");
initialize();
 
$mobile_browser = '0';
 
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}
 
if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
}    
 
$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
//$mobile_agents = array(
//    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
//    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
//    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
//    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
//    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
//    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
//    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
//    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
//    'wapr','webc','winw','winw','xda ','xda-');
 
$mobile_agents = array(
  'iPhone', 'Mobile Safari', 'Windows Phone'
);

if (in_array($mobile_ua,$mobile_agents)) {
    $mobile_browser++;
}
 
if (strpos(strtolower(@$_SERVER['ALL_HTTP']),'OperaMini') > 0) {
    $mobile_browser++;
}
 
if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
    $mobile_browser = 0;
}
 
if ($mobile_browser > 0 && (!isset($_SESSION['mobile']) || $_SESSION['mobile'] != 0)) {
  header("Location: https://www.lactor.org".HTTP_ROOT."/app"); 
  header("HTTP/1.1 302 Found"); 
  exit;
}
 
if (loggedIn(false)) {
  go('add_entry.php');
}



?>

<head>
<?php head_tag("LACTOR - "._("Login")); ?>
</head>
<style type="text/css" media="screen">
#loginBox { 
  width: 40%;
}
#loginContainer {
  position: relative;
}
#messageBox {
  width: 50%;
  border: 1px solid #444;
  font-size: 1.2em;
  padding: 1em;
  padding-bottom: 1.5em;
  position: absolute;
  top: 0;
  right: 0;
  color: #666;
  border-radius: 3px;
  background-color: #eee;
}
#messageBox h1 {
  margin-top: 0;
  color: #333;
}
</style>
<body>
<div id="maincontainer">
  <?php page_header(); ?>

  <div id="pagecontent">
    <?php displayMessage('LoginMessage','LoginDetails', 'LoginType'); ?> 

    <div id="loginContainer">
      <div id="messageBox">
        <h1><?php echo _("Welcome to LACTOR") ?></h1>
        <?php echo _("LACTOR is an innovative, interactive web-based breastfeeding monitoring system.  LACTOR was created to help breastfeeding mothers monitor their breastfeeding patterns and detect early breastfeeding problems.  The lactation consultant can also monitor mothers' data and intervene immediately in case of a breastfeeding problem.  Mothers also receive notifications if they have any breastfeeding problem with suggestions to help solve the problem.") ?> 
      </div>
      <div id="loginBox" class="tabs">
        <ul class="menu">
        <li id="breastfeeding" class="active"><a href="#login"><?php echo _("Login") ?></a></li>
        </ul>
        <div class="login" id="login">
          <h1><img src="image/lock.gif" alt =""/><?php echo _("Credentials") ?></h1>
          <div id="standardinput">
          <form id="standardform" action="post/login.post.php" method="post">
            <table border='0'><tbody>
            <tr><td><?php echo _("Email") ?>:</td><td><input id="standardtextform" type="text" name="user" /></td>
            <tr><td><?php echo _("Password") ?>:</td><td><input id="standardtextform" type="password" name="pass" /></td>
            </tbody></table><br />
            <input id="standardsubmit" type="submit" value="<?php echo _("Log In") ?>" />
            <span style='float:right;'>
            <?php echo languageSwitcher(); ?>
            </span>
          </form>
        </div>
      </div>
    </div>   
  </div>


  <br/>
  <p>
    <a href="reset_pass.php"><?php echo _("Forgot your password?") ?></a> |
    <a href="where_info.php"><?php echo _("Where is this information?") ?></a><br />
    <br /><br />
  </p>
  <br />
</div>

<?php page_footer(); ?>
</div>
</body>
</html>

<?php 
include_once("includes/general.php");
initialize();

?>

<head>
<?php head_tag("LACTOR - Security Information"); ?>
<link rel="stylesheet" href="css/login.css" type="text/css" media="all" />
</head>

<body>
<div id="maincontainer">
<?php page_header(); ?>

<div id="pagecontent">

<h1>How we keep your information secure:</h1>
<p> We never store your password unencrypted, instead the password is encrypted 
by 
<a href="http://en.wikipedia.org/wiki/Salt_(cryptography)">salting</a> 
it and hashing it with 
<a href="http://en.wikipedia.org/wiki/SHA-2">SHA-256</a>, which is irreversible. 
This is why we have to reset your password if it is lost or forgotten, 
as even we have no way of seeing what your password is. This ensures that even
in the event of a data breach, your password is safe.<p>
<p> 
All of your information is stored on servers at Purdue's 
<a href='http://www.purdue.edu/discoverypark/bioscience/'>Bindley Bioscience 
Center</a>.  Access to these server is restricted. They are kept in a locked 
room, and only necessary personnel are able to access it. Remote access to the 
servers is available only using pre-shared 
<a href="http://en.wikipedia.org/wiki/RSA_(algorithm)">RSA</a> 
encryption keys; password-only access is disallowed. All data backups from the 
server database are encrypted with 
<a href="http://en.wikipedia.org/wiki/Advanced_Encryption_Standard">AES 256-bit</a>
encryption to ensure no information can be gleaned from them without the 
password.
</p>
<p> Access to your personal information within the web site is only visible to 
your lactation consultant and The Lactor administrative staff, 
who must be fully versed in 
<a href="http://www.hhs.gov/ocr/privacy/hipaa/understanding/consumers/index.html">HIPAA compliance</a> 
before access is permitted to the system.</p>
<p>
Information transfer from your computer is available using SSL encryption only
to ensure that your interaction with Lactor cannot be intercepted by a third 
party. LACTOR currently receives an 
<a href="https://www.ssllabs.com/ssltest/analyze.html?d=lactor.org">&quot;A-&quot; rating</a>
for web security from ssllabs. We are working to increase this rating to an 
&quot;A&quot;.
</p>
<p style="border: 1px solid blue; background-color: #aaaaff; padding: 1em;">
In response to the security vulnerability in OpenSSL discovered on April 7, 2014
(<a href="https://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2014-0160">detailed here</a>), 
We have installed a new certificate and revoked the old
server certificate in order to best keep your data safe. The server was patched
within hours of the release of the vulnerability details, and to the best of our
knowledge no data was stolen, however, if you logged in to lactor on the
evening of April 7, we recommend you change your password to ensure your safety.
</p>
</div>
<?php page_footer(); ?>
</div>
</body>
</html>

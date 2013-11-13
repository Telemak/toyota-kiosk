<?php

if (isset($_GET['action'])&&$_GET['action']=='send') {
	im_sendEmailToEleven($_GET);
}


function im_sendEmailToEleven($data) {
    
	ini_set ( 'sendmail_from' , 'noreply@telemak.com' );
	$eol="\r\n";
    
    /* Setup Subject and Message Body */
    $subject = $data['subject'];
    
    //$destination = '';
	$destination = 'ianmantripp@telemak.com<ianmantripp@telemak.com>';
	$destination = $data['to'];
    
  $fromname = 'Toyota kiosk';
  $fromaddress = 'noreply@telemak.com';
  
  $headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
//  $headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
//  $headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol;    // these two to set reply address
  $headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;
  $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
  
    
    $body = $data['content'];
  /*  echo "Headers: ".$headers;
    echo "To: ".$destination;
    echo "Subject: ".$subject;
    echo "Content: ".$body;*/
    mail ($destination, $subject, $body, $headers);
    
echo 'Sent email to: '.$destination . ', subject: '.$subject . ', body: ' . $body;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>untitled</title>
	
</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" accept-charset="utf-8">
	
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>to</td><td><input type="text" name="to" value="" id="to"></td></tr>
		<tr><td>from</td><td><input type="text" name="from" value="noreply@telemak.com" id="from"></td></tr>
		<tr><td>subject</td><td><input type="text" name="subject" value="this is the subject" id="subject"></td></tr>
		<tr><td>content</td><td><input type="text" name="content" value="... and this is some content" id="subject"></td></tr>
	</table>
	<input type="hidden" name="action" value="send" id="action">
	<p><input type="submit" value="Send"></p>
</form>

</body>
</html>

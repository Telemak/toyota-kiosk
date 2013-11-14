<?php
/* Test script to send HTML email */
require_once 'config/toyotaactions.php'; 
		
if (isset($_GET['action'])&&$_GET['action']=='send') {
	im_sendEmailToClient("MALE", "Anonymous", "Developer", $_GET['to']);
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
	</table>
	<input type="hidden" name="action" value="send" id="action">
	<p><input type="submit" value="Send"></p>
</form>

</body>
</html>

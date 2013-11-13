<?php
require_once 'config.php';

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case 'insert':
			if ($debug) {
				echo 'action is insert...<br />';
			}
			global $pconnect;
			$query = sprintf('INSERT INTO entries ( gender, lastname, firstname, street, number, box, zip, city, country, telephone, email) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
				GetSQLValueString($_GET['gender'],'text'),
				GetSQLValueString($_GET['lastname'],'text'),
				GetSQLValueString($_GET['firstname'],'text'),
				GetSQLValueString($_GET['street'],'text'),
				GetSQLValueString($_GET['number'],'text'),
				GetSQLValueString($_GET['box'],'text'),
				GetSQLValueString($_GET['zip'],'text'),
				GetSQLValueString($_GET['city'],'text'),
				GetSQLValueString($_GET['country'],'text'),
				GetSQLValueString($_GET['telephone'],'text'),
				GetSQLValueString($_GET['email'],'text'));
				
				//echo $query;
		$rs = mysql_query($query, $pconnect) or die(mysql_error());
		
		$insertID = mysql_insert_id();
		
		echo $insertID;		
			global $pconnect;
			$query = sprintf('SELECT * from entries WHERE id=%s', $insertID);

			$rs = mysql_query($query, $pconnect) or die(mysql_error());
			
			if ($record = mysql_fetch_assoc($rs)) {
				//echo $record['lastname'];
				im_sendEmailToEleven($record);
			}
		
		break;
		
	}
}


function im_sendEmailToEleven($data) {
    
	ini_set ( 'sendmail_from' , 'noreply@telemak.com' );
	$eol="\r\n";

	/* Setup Subject and Message Body */
	$subject='New entry from Toyota kiosk (Brussels airport)';

	//$destination = '';
	//$destination = 'toyotabrusselsairport@eleven-intl.com<toyotabrusselsairport@eleven-intl.com>';
	$destination = 'toyotabrusselsairport@telemak.com,support@telemak.com';

	$fromname = 'Toyota kiosk (Brussels airport)';
	$fromaddress = 'noreply@telemak.com';

	$headers = "From: ".$fromname."<".$fromaddress.">".$eol;
	//  $headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
	//  $headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol;    // these two to set reply address
	//$headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;
	//$headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters


	$body = '';
	foreach ($data as $key => $value) {
	$body .= $key.': '.$value.$eol;
	}
	/*  echo "Headers: ".$headers;
	echo "To: ".$destination;
	echo "Subject: ".$subject;
	echo "Content: ".$body;*/
	mail ($destination, $subject, $body, $headers);
	//echo $body;

}
?>
<?php
require_once 'config.php';

/**
 * Ian Mantripp
 * 2013-11-13
 * Adding Sanitisation
 **/
$gender		= isset($_GET['gender']) ? filter_input(INPUT_GET, 'gender', FILTER_SANITIZE_STRING) : '';
$lastname	= isset($_GET['lastname']) ? filter_input(INPUT_GET, 'lastname', FILTER_SANITIZE_STRING) : '';
$firstname	= isset($_GET['firstname']) ? filter_input(INPUT_GET, 'firstname', FILTER_SANITIZE_STRING) : '';
$street		= isset($_GET['street']) ? filter_input(INPUT_GET, 'street', FILTER_SANITIZE_STRING) : '';
$number		= isset($_GET['number']) ? filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRING) : '';
$box		= isset($_GET['box']) ? filter_input(INPUT_GET, 'box', FILTER_SANITIZE_STRING) : '';
$zip		= isset($_GET['zip']) ? filter_input(INPUT_GET, 'zip', FILTER_SANITIZE_STRING) : '';
$city		= isset($_GET['city']) ? filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING) : '';
$country	= isset($_GET['country']) ? filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING) : '';
$telephone	= isset($_GET['telephone']) ? filter_input(INPUT_GET, 'telephone', FILTER_SANITIZE_STRING) : '';
$email		= isset($_GET['email']) ? filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING) : '';
$model		= isset($_GET['model']) ? filter_input(INPUT_GET, 'model', FILTER_SANITIZE_STRING) : '';
/**
 * end Adding Sanitisation
 **/

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case 'insert':
			if ($debug) {
				echo 'action is insert...<br />';
			}
			global $pconnect;
			$query = sprintf('INSERT INTO entries ( gender, lastname, firstname, street, number, box, zip, city, country, telephone, email, model) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
			GetSQLValueString($gender,'text'),
			GetSQLValueString($lastname,'text'),
			GetSQLValueString($firstname,'text'),
			GetSQLValueString($street,'text'),
			GetSQLValueString($number,'text'),
			GetSQLValueString($box,'text'),
			GetSQLValueString($zip,'text'),
			GetSQLValueString($city,'text'),
			GetSQLValueString($country,'text'),
			GetSQLValueString($telephone,'text'),
			GetSQLValueString($email,'text'),
			GetSQLValueString($model,'text'));
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
				
				/**
				 * Ian Mantripp
				 * 2013-11-13
				 * check client country, if DE, send email to client
				 **/
				if($country=="Germany"&&(strlen($email)>4)) im_sendEmailToClient($gender, $lastname, $firstname, $email);
				/**
				 * end check client country
				 **/
			}
		
		break;
		case 'test':
			im_sendEmailToClient($gender, $lastname, $firstname, $email);
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

/**
 * im_sendEmailToClient function
 *
 * @return void
 * @author Ian Mantripp
 **/
function im_sendEmailToClient($gender, $lastname, $firstname, $email)
{
	//ini_set ( 'sendmail_from' , 'Toyota.Datenschutz@toyota.de' );
	ini_set ( 'sendmail_from' , 'noreply@telemak.com' );
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	$eol="\r\n";

	$subject='Toyota information – Ihre Anfrage';

	$destination = $email;

	$fromname = 'Toyota Motor Europe';
	//$fromaddress = 'toyota.datenschutz@toyota.de';
	$fromaddress = 'noreply@telemak.com';
	
	$title		= $gender=="MALE" ? "Herr" : "Frau";
	$name		= $lastname;

	$headers = "From: ".$fromname."<".$fromaddress.">".$eol."Reply-To: "."toyota.datenschutz@toyota.de".$eol;
	
	// To send HTML mail, the Content-type header must be set
	$headers .= 'MIME-Version: 1.0' .$eol;
	$headers .= 'Content-type: text/html; charset=utf-8' .$eol;
	
	$body = <<<EOD
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css" media="screen">@font-face {font-family: 'toyota_displayregular';src: url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_rg-webfont.eot');src: url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_rg-webfont.eot?#iefix') format('embedded-opentype'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_rg-webfont.woff') format('woff'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_rg-webfont.ttf') format('truetype'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_rg-webfont.svg#toyota_displayregular') format('svg');font-weight: normal;font-style: normal;}@font-face {font-family: 'toyota_displaybold';src: url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_bd-webfont.eot');src: url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_bd-webfont.eot?#iefix') format('embedded-opentype'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_bd-webfont.woff') format('woff'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_bd-webfont.ttf') format('truetype'),url('http://toyota.clients.telemak.com/80151_kiosk/resources/fonts/toyotadisplaybeta_bd-webfont.svg#toyota_displaybold') format('svg');font-weight: normal;font-style: normal;}</style></head><body style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; "  bgcolor="#ffffff"> <table width="100%" cellspacing="0" cellpadding="0" id="page-wrap" align="center" bgcolor="#ffffff"><tbody><tr><td><table class="email-body-wrap" width="900" cellspacing="0" cellpadding="0" id="email-body" align="center"><tbody><tr><td width="30" eqfixedwidth="true">&nbsp;<!-- Left page bg show-thru --></td><td width="840" bgcolor="#ffffff"><!-- Begin of layout container --><div id="eqLayoutContainer"><div eqlayoutblock=""><table width="840" cellpadding="0" cellspacing="0" align="center"><tbody><tr><td width="30">&nbsp;</td><td width="780" align="center"><div class="heading" apple-content-name="title" style="font-size: 16px; font-family: 'toyota_displaybold'; "><div style="text-align: left; font-size: 30px; "><font face="toyota_displaybold,arial">Sehr geehrte $title $name,</font></div></div></td><td width="30">&nbsp;</td></tr><tr height="8"><td width="30"><div class="spacer"></div></td><td width="780"><div class="spacer"></div></td><td width="30"><div class="spacer"></div></td></tr><tr><td width="30">&nbsp;</td><td width="780" align="left"><div class="text" apple-content-name="body" style="font-size: 16px; font-family: 'toyota_displayregular'; "><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">herzlichen Dank für Ihr Interesse an unseren Toyota Modellen. Das gewünschte Prospektmaterial werden wir Ihnen umgehend zukommen lassen.</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">&nbsp;</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">Damit wir Sie auch in Zukunft über unsere Produkte informieren und Sie individuell beraten können,</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">&nbsp;</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">antworten Sie bitte auf diese E-Mail mit der Angabe:</font></span></div></div></td><td width="30">&nbsp;</td></tr></tbody></table></div><div eqlayoutblock=""><table width="840" cellspacing="0" cellpadding="0"><tbody><tr><td width="65">&nbsp;</td><td width="745" valign="top" align="left"><div class="text" style="font-size: 16px; font-family: 'toyota_displayregular'; "><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial"><font color="#ff2600" face="">&#x25CF;</font>&nbsp;Kontakt per Telefon (bitte geben Sie uns Ihre Telefonnummer an) und/oder</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial"><font color="#ff2600" face="">&#x25CF;</font> Kontakt per E-Mail gewünscht.</font></span></div></div></td><td width="30">&nbsp;</td></tr></tbody></table></div><div eqlayoutblock=""><table width="840" cellspacing="0" cellpadding="0"><tbody><tr><td width="30">&nbsp;</td><td width="780" valign="top" align="left"><div class="text" style="font-size: 16px; font-family: 'toyota_displayregular'; "><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial"><br></font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">Der Toyota Info-Service meldet sich dann umgehend bei Ihnen.</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">&nbsp;</font></span></div><div style="line-height: 24px; "><span style="line-height: 24px; "><font face="toyota_displayregular,arial">Die Einwilligungserklärung haben Sie gelesen und sind damit einverstanden.</font></span></div></div></td><td width="30">&nbsp;</td></tr></tbody></table></div><div eqlayoutblock=""><table width="840" cellspacing="0" cellpadding="0"><tbody><tr><td width="30">&nbsp;</td><td width="780" valign="top" align="left"><div class="text" style="font-size: 16px; font-family: 'toyota_displayregular'; "><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial">*<u>Datenschutzrechtliche Einwilligungserklärung</u></font></div><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial">Ich bin damit einverstanden, dass die im Rahmen meiner Anfrage erhobenen Daten zur Übermittlung von Informationen per Telefon, elektronischer Post (E-Mail, SMS, MMS) oder Post zu den von mir gewünschten Modellreihen und Themen von der Toyota Deutschland GmbH und mit ihr verbundene Unternehmen genutzt werden können. Die Weitergabe meiner Daten an Dritte ist grundsätzlich ausgeschlossen. Ausgenommen hiervon ist die Weitergabe meiner Daten an die oben genannten Gesellschaften und an die TOYOTA Händler sowie an mit der Kundenbetreuung beauftragte Agenturen.&nbsp;</font></div><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial"><br></font></div><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial"><u>Widerrufsrecht</u>:</font></div><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial">Sie können auf Anfrage Auskunft über Ihre bei der Toyota Deutschland GmbH gespeicherten Daten und den Inhalt Ihrer Einwilligungserklärung bekommen. Eine Berichtigung, Löschung oder Sperrung der Daten ist auf Wunsch problemlos möglich. Sollten Sie im Nachhinein Einwände gegen die Verarbeitung Ihrer Daten haben, können Sie Ihr Einverständnis jederzeit per Email (<a href="mailto:&#x54;&#x6F;&#x79;&#x6F;&#x74;&#x61;&#x2E;&#x44;&#x61;&#x74;&#x65;&#x6E;&#x73;&#x63;&#x68;&#x75;&#x74;&#x7A;&#x40;&#x74;&#x6F;&#x79;&#x6F;&#x74;&#x61;&#x2E;&#x64;&#x65;">Toyota.Datenschutz@toyota.de</a>) oder telefonisch widerrufen</font></div><div style="line-height: 16px; "><font style="line-height: 16px; font-size: 13px; " face="toyota_displayregular,arial">(Hotline: 01 80 – 5 35 69 69, Kosten: &euro; 0,14/Min. aus dem deutschen Festnetz, Mobilfunknetz max. &euro; 0,42/Min.).</font></div><div><br></div></div></td><td width="30">&nbsp;</td></tr></tbody></table></div></div><!-- End of layout container --></td><td width="30" eqfixedwidth="true">&nbsp;<!-- Right page bg show-thru --></td></tr></tbody></table><!-- email-body --></td></tr><tr><td height="30">&nbsp;<!-- Bottom page bg show-thru --></td></tr></tbody></table><!-- page-wrap --></body></html>
EOD;
	mail ($destination, $subject, $body, $headers);
}

?>

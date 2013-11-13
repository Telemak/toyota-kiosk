<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>untitled</title>
	<link rel="stylesheet" href="css/toyotatouch.css" type="text/css" />
	<script src="js/prototype.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
	
	var insertID;

	document.observe("dom:loaded", function() {
		insert = new Ajax.Request('modules/config/toyotaactions.php',
		{ 
			method: 'get', 
			parameters: window.location.search.parseQuery(),
			onComplete: checkInsert
		});
	});
	
	function checkInsert(req) {
		if (req.responseText*1 < 0) {
			document.getElementById('thanks').innerHTML = '<p>Sorry, an error has occured.<br />Your details have not been sent.</p><p>Please try again.</p>';
		} else {
			$('spinner').hide();
		};
		restart();
	}

	function restart() {
		setTimeout( "window.location='index.html'" , 5000 );
	}
	
		
	</script>
</head>

<body id="body">
	<div id="main">
		<div id="window">
			<div id="view">
				<div id="scene0" class="scene">
					<div class="redText" id="thanks">
						<p>Thank you for your time!<p><p>We appreciate your interest for Toyota</p><p>A Toyota representative from your country will contact you soon.</p><p>Have a safe journey</p>
						<div id="spinner">
							<img src="img/spinner_100*100.gif" width="100" height="100" alt="Spinner 100*100" />
						</div>
					</div>
				</div>
			</div><!--#view-->
		</div><!--#window-->
	</div><!--#main-->
</body>
</html>

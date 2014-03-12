document.observe('dom:loaded', function(){
	if ($('countries')) {
		$('countries').hide();
	}
	/*$('keyboard').hide();*/
	});



function submitData() {
	query = $H(data).toQueryString()
	new Ajax.Request("modules/config/toyotaactions.php",
		{ 
		method: 'post', 
		postBody: query,
		onComplete: showResponse 
		});
}

function showResponse(req) {
	location.href = "thanks.php";
}





function buttonDown(button) {
	resetTimer();
	var img;
	img=button.alt
	button.src = "img/"+img+'_over.png';
}

function buttonUp(button, action) {
	// update the data model from the focusfield view
	updateModelAndView();
	
	// initialise vars
	var allFieldsAreValid = true;
	var img;
	img=button.alt
	button.src = "img/"+img+'.png';
	switch(button.alt) {
	case 'next':
		//validation goes here
		validation[currentStep].each(function(element){
			if (allFieldsAreValid) {
				if(!validate(element.key,element.value)) {
					//alert(element.key+' is not valid');
					allFieldsAreValid = false;
					errorField = element.key;
				}
			}
		})
		if (allFieldsAreValid) {
			if (currentStep<lastStep) {
				moveDivBy(++currentStep);
				if (currentStep==lastStep) {new Effect.Fade('keyboard', {duration: 0.5});};
			}
		} else {
			// give focus to the problem field
			giveFieldFocus($(errorField),false);
			// show errors
		}
	break;
	
	case 'back':
		if (currentStep>0) {
			moveDivBy(--currentStep);
		} else {
			location.href = "index.html";
		}
		break;
	
	case 'confirm':
		location.href = "thanks.php?"+$H(data).toQueryString();
		break;
	
	case 'start_enter':
		location.href = "data.html";
		break;
		
	default:
	}
}

function emailValidation(email) {
	var emailRE = /(@\w[-._\w]*\w\.\w{2,3})$/;
	if (!emailRE.test(email)) {
		return false;
        //errMsg = "Please enter your email address\.";
		//removeFocusFromAllFields();
		//giveFieldFocus($('email'),false);
    } else {
		return true;
	}
}

function validate(field, type) {
	thedata = data[field];
	switch(type) {
		case 'text':
			if (field=='telephone'){
				if (thedata.length>1) {return true};
				break
			} else {
				if (thedata.length>0) {return true};
				break;
			}
		case 'email':
			if (thedata.length>0) {  // if an email has been entered, make sure it is correctly formatted
				if (emailValidation(thedata)) {return true};
				break;
			}
			return true;
			break;
		case 'none':
			return true;
			break;
	}
	return false;
}

function updateModelFromViewForScene(scene) {
	
}

function keyDown(key, action) {
	resetTimer();
	if (focusField.id=='country') {
		return;
	}
	
	var img;
	if (!action) {
		img=key.alt;
	} else {
		img=action;
	}
	key.src = "img/"+img+"_over.png";
	switch(action) {
		case 'delete':
		// $('gender').innerHTML=$('gender').innerHTML.slice(0,$('gender').innerHTML.length-1)
		if ($(focusField).innerHTML.length>0) {
			if (focusField!=$('telephone')) {
				$(focusField).innerHTML=$(focusField).innerHTML.slice(0,$(focusField).innerHTML.length-1)
			} else {
				if ($(focusField).innerHTML.length>1) {
					$(focusField).innerHTML=$(focusField).innerHTML.slice(0,$(focusField).innerHTML.length-1)
				}
			}
			break;
		}
		break;
		
		default:
		var k = key.alt
		if (focusField.id=='email') { k = k.toLowerCase()};
		$(focusField).innerHTML = $(focusField).innerHTML+k;
		break;
	}
}
function keyUp(key, action) {
	var img;
	if (!action) {
		img=key.alt;
	} else {
		img=action;
	}
	key.src = "img/"+img+".png";
}

function giveFieldFocus(theField, updateDataArray) {
	
	resetTimer();
	
	if(countriesAreShown) {
		showKeyboard();
	}

	// remove focus css from any other field that has it.
	if (updateDataArray) {
		updateModelAndView();
	}
	focusField = theField;
	theField.className = "currentData";
	
	if (theField.id=='country') {
		hideKeyboard();
	};
}

function updateModelAndView() {
	$$('div.currentData').each(function(element) {
		element.className="data";
		// update the data array if required
			updateDataArrayItem(element);
		}
	);
}

function removeFocusFromAllFields() {
	$$('div.currentData').each(function(element) {
		element.className="data";
		}
	);
}

function updateDataArrayItem(element) {
	// update model(array)
	data[element.id]=element.innerHTML;
	// update view
	$('confirm_'+element.id).innerHTML=element.innerHTML;
}

function moveDivBy(index, focusField) {
	var elem = 'view';
	var dur = 0.5
	var moveTo = index * 1017;
	new Effect.Move(elem, { x: -moveTo, y: 0, duration: dur, mode:'absolute', afterFinish: 
		function() {
			if (index<lastStep) {
				if (focusField) {
					if (focusField.id!='country') {
						new Effect.Appear('keyboard', {duration: 0.5});
					}
				} else { // if the focusField is 'gender' --> there is no div with that id !!!
					if( index == 0 ) $('keyboard').hide();
					else new Effect.Appear('keyboard', {duration: 0.5});
				}
			}
		}
	});
	// give focus to the correct field
	if (index<lastStep) {
		// if a field is defined, give it focus, we are coming from the confirm scene in this case.
		if (focusField) {
			giveFieldFocus(focusField,true);
		} else {
			// if a field is not defined, give focus to the first field in the scene
			var field = 'div#scene'+(index+1)+' div.data'
			giveFieldFocus($$(field)[0],true);			
		}
	} else {
		// we are moving into the confirmation scene, just update the data array
		updateModelAndView();
	}
}

function gotoSceneWithFocusOn(scene, elem) {
	resetTimer();
	currentStep = scene;
	var alt = elem.readAttribute('alt');
	moveDivBy(scene, $(alt));
	//var vv = $(elem.readAttribute('alt')).id;
	//if (vv!='country') {
	//	$('keyboard').show();
	//}
}

function setGender(elem) {
	// set the visual feedback
	$$('#genderButtons img').each(function(element){element.src="img/"+element.alt+".png"});
	var img;
	img=elem.alt
	elem.src = "img/"+img+'_over.png';
	//update model
	data['gender']=elem.alt.toUpperCase();
	// update view
	$('confirm_gender').innerHTML=data['gender'];
}

function hideKeyboard() {
	//$('keyboard').hide();
	//$('countries').show();
	new Effect.Parallel([ 
		new Effect.Fade('keyboard'), 
		new Effect.Appear('countries') 
	], { duration: 0.5 }
	);
	countriesAreShown = true;
}

function showKeyboard() {
	//$('countries').hide();
	//$('keyboard').show();
	new Effect.Parallel([ 
		new Effect.Fade('countries'), 
		new Effect.Appear('keyboard') 
	], { duration: 0.5 }
	);
	countriesAreShown = false;
}

function setCountry(theCountry) {
	resetTimer();
	$('country').innerHTML = theCountry;
	data['country'] = theCountry;
	showKeyboard();
}

function setModel(theModel) {
	resetTimer();
	/*$('model').innerHTML = theModel;*/
	data['model'] = theModel;
	$('confirm_model').innerHTML=theModel;
	showKeyboard();
}

var countriesAreShown = false;
var currentStep = 0;
var lastStep = 4;

var data = {'gender':'', 'lastname':'', 'firstname':'', 'street':'', 'number':'', 'box':'', 'zip':'', 'city':'', 'country':'', 'telephone':'', 'email':'', 'action':'insert', 'model':''};

var validation0 = {'model':'text'};
var validation1 = {'gender':'text', 'lastname':'text', 'firstname': 'text'};
var validation2 = {'street':'text', 'number':'text', 'box': 'none', 'zip': 'text', 'city': 'text', 'country': 'text'};
var validation3 = {'telephone':'text', 'email':'email'};

var validation1 = $A(['text', 'text', 'text']);
var validation1 = $H({'gender':'text', 'lastname':'text', 'firstname': 'text'});


var validation2 = $H({'street':'text', 'number':'text', 'box': 'none', 'zip': 'text', 'city': 'text', 'country': 'text'});
var validation3 = $H({'telephone':'text', 'email':'email'});


//var validation = {'validation0':validation0, 'validation1':validation1, 'validation2':validation2};
//var validation = $H({validation0:validation0, validation1:validation1, validation2:validation2});
var validation = $A([validation0, validation1, validation2, validation3]);



/*var step0 = {'position':'0', 'caption':'firstname', 'value':'Ian'};
var step1 = {'position':'1', 'caption':'lastname', 'value':'Mantripp'};
var step2 = {'position':'2', 'caption':'email', 'value':'ianmantripp@mac.com'};


var data = {'step0':step0, 'step1':step1, 'step2':step2};

var steps = [
	{'firstname': 'Ian'},
	{'lastname': 'Mantripp'},
	{'email': 'ianmantripp@mac.com'}
];
*/

function preloader() {
	// counter
	var i = 0;

	// create object
	imageObj = new Image();

	// set image list
	images = new Array();
	images[0]="img/start_enter_over.png"
	images[1]="img/back_over.png";
	images[2]="img/at_over.png";
	images[3]="img/a_over.png";
	images[4]="img/7_over.png";
	images[5]="img/6_over.png";
	images[6]="img/9_over.png";
	images[7]="img/8_over.png";
	images[8]="img/5_over.png";
	images[9]="img/e_over.png";
	images[10]="img/enter_over.png";
	images[11]="img/Ln_over.png";
	images[12]="img/Lh_over.png";
	images[13]="img/1_over.png";
	images[14]="img/dot_over.png";
	images[15]="img/d_over.png";
	images[16]="img/c_over.png";
	images[17]="img/b_over.png";
	images[18]="img/Lm_over.png";
	images[19]="img/Lg_over.png";
	images[20]="img/Lf_over.png";
	images[21]="img/Li_over.png";
	images[22]="img/Ld_over.png";
	images[23]="img/Lc_over.png";
	images[24]="img/Lb_over.png";
	images[25]="img/La_over.png";
	images[26]="img/l_over.png";
	images[27]="img/k_over.png";
	images[28]="img/j_over.png";
	images[29]="img/i_over.png";
	images[30]="img/hyphen_over.png";
	images[31]="img/Le_over.png";
	images[32]="img/3_over.png";
	images[33]="img/2_over.png";
	images[34]="img/0_over.png";
	images[35]="img/Ll_over.png";
	images[36]="img/Lk_over.png";
	images[37]="img/4_over.png";
	images[38]="img/h_over.png";
	images[39]="img/g_over.png";
	images[40]="img/f_over.png";
	images[41]="img/Lj_over.png";
	images[42]="img/delete_over.png";
	images[43]="img/Lo_over.png";
	images[44]="img/Lp_over.png";
	images[45]="img/Lq_over.png";
	images[46]="img/Lr_over.png";
	images[47]="img/Ls_over.png";
	images[48]="img/Lt_over.png";
	images[49]="img/Lu_over.png";
	images[50]="img/Lv_over.png";
	images[51]="img/Lw_over.png";
	images[52]="img/Lx_over.png";
	images[53]="img/Ly_over.png";
	images[54]="img/Lz_over.png";
	images[55]="img/m_over.png";
	images[56]="img/n_over.png";
	images[57]="img/next_over.png";
	images[58]="img/o_over.png";
	images[59]="img/p_over.png";
	images[60]="img/q_over.png";
	images[61]="img/r_over.png";
	images[62]="img/s_over.png";
	images[63]="img/space_over.png";
	images[64]="img/start_enter_over.png";
	images[65]="img/t_over.png";
	images[66]="img/u_over.png";
	images[67]="img/underscore_over.png";
	images[68]="img/v_over.png";
	images[69]="img/w_over.png";
	images[70]="img/x_over.png";
	images[71]="img/y_over.png";
	images[72]="img/z_over.png";
	images[72]="img/check_sheet.png";

	// start preloading
	for(i=0; i<=73; i++) {
		imageObj.src=images[i];
	}
}


// please keep these lines on when you copy the source
// made by: Nicolas - http://www.javascript-page.com

// well ok, I'll leave those lines although the code was modified by Ian !!!

var timerID = 0;
var tStart  = null;
var mytimer = 0;

function UpdateTimer() {
	if(timerID) {
		clearTimeout(timerID);
		clockID  = 0;
	}

	if(!tStart)
	tStart   = new Date();

	var   tDate = new Date();
	var   tDiff = tDate.getTime() - tStart.getTime();

	tDate.setTime(tDiff);

	mytimer = (tDate.getMinutes()*60)+tDate.getSeconds();
	
	if (mytimer>30) {
		location.href = "index.html";
	};

	timerID = setTimeout("UpdateTimer()", 1000);
}

function timerStart() {
	tStart   = new Date();
	mytimer = 0;
	timerID  = setTimeout("UpdateTimer()", 1000);
}

function timerStop() {
	if(timerID) {
		clearTimeout(timerID);
		timerID  = 0;
	}
	tStart = null;
}

function timerReset() {
	tStart = null;
	mytimer = 0;
}

function resetTimer() {
	timerReset();
	timerStart();
}

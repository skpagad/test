//Listen For Form Submit 
 
function validateMyForm(e) 
{ 
  var first = document.getElementById('first').value; 
  var last = document.getElementById('last').value; 
  var email = document.getElementById('signup_email').value; 
  var phone = document.getElementById('phone').value; 
  var pass1 = document.getElementById('pass1').value; 
  var pass2 = document.getElementById('pass2').value; 
  var checkbox = document.getElementById('aggrement'); 
 
if(!validateForm(first, last, email, phone, pass1, pass2, checkbox)){ 
	 
  return false; 
} 
 e.preventDefault(); 
} 
 
function validateForm(first, last, email, phone, pass1, pass2, checkbox){ 
  if(!first || !last || !email || !phone || !pass1 || !pass2) 
  { 
    alert('Please Fill in the Complete Form'); 
    return false; 
  } 
 
var forWhitespace = /^\S+$/; 
var Rexp = new RegExp(forWhitespace); 
 
var forCharOnly = /^[a-zA-Z]+$/; 
var rxp = new RegExp(forCharOnly); 
 
if(!first.match(Rexp) || !first.match(rxp)) 
{ 
  alert('Only letters are allowed in First Name'); 
  return false; 
} 
if(!last.match(Rexp) || !last.match(rxp)) 
{ 
  alert('Only letters are allowed in last Name'); 
  return false; 
} 
 
 
var expression = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i; 
var regex = new RegExp(expression); 
  if(!email.match(regex)) 
  { 
  	alert("Enter proper Email address"); 
  	return false; 
  } 
  var reg = /^[0-9]*$/ 
  var regex2 = new RegExp(reg); 
 
  if(phone.length!=10) 
  {	alert('Enter 10 Digit No. only.') 
  	return false; 
  } 
  if(!phone.match(regex2)){ 
    alert('phone no should contain number only.'); 
    return false; 
  } 
  if(!pass1.match(Rexp)) 
{ 
  alert('No WhiteSpace allowed in password Field'); 
  return false; 
} 
  if(pass1.length<6) 
  	{	alert('Password feild length less than 6'); 
  		return false; 
  	} 
  if(pass1!=pass2) 
  	{	alert('Passwords do not match'); 
  		return false; 
  	} 
 
 
 if (!checkbox.checked) 
{ 
    alert("Checkbox is NOT CHECKED."); 
    return false; 
} 
 
  return true; 
} 
 
function check_checkbox() 
{ 
	document.getElementById("agreement").checked = true; 
} 
	 
function disable_register() 
{ 
	if(document.getElementById("agreement").checked == true) 
	{ 
		document.getElementById("signup").disabled = false; 
	} 
	else  
	{ 
		document.getElementById("signup").disabled = true; 
	} 
} 

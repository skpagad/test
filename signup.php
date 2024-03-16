<?php 
session_start(); 
require "../includes/misc.inc"; 
require "../includes/clear_text.inc"; 
require "../includes/clear_number.inc"; 
require "../includes/crypto_rand_secure.inc"; 
require "../includes/get_real_ip.inc"; 
 
function checkEmail($email) 
{ 
	global $connect; 
    $email = mysqli_real_escape_string($connect,$email); 
     
	$checkEmail = mysqli_query($connect, "SELECT email FROM customer_login WHERE email='$email'"); 
	 
    if (mysqli_num_rows($checkEmail) == 0) 
	{ 
		return true; 
	} 
    return false; 
} 
 
function checkPhone($phone)  
{ 
	global $connect; 
    $phone = mysqli_real_escape_string($connect,$phone); 
 
	$checkPhone = mysqli_query($connect, "SELECT phone FROM customer_info WHERE phone='$phone'"); 
	 
    if (mysqli_num_rows($checkPhone) == 0) 
	{ 
		return true; 
	} 
    return false; 
} 
 
 
 
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST['signup'])) 
{ 
	if(empty($_POST['first'])) 
		$first = ""; 
	else 
	{ 
		$first = mysqli_real_escape_string($connect,clear_text($_POST['first'])); 
		$_SESSION['first'] = $first; 
	} 
	 
	if(empty($_POST['last'])) 
		$last = ""; 
	else  
	{ 
		$last = mysqli_real_escape_string($connect,clear_text($_POST['last'])); 
		$_SESSION['last'] = $last; 
	} 
	 
	if(empty($_POST['phone'])) 
		$phone = ""; 
	else  
		$phone = mysqli_real_escape_string($connect,clear_text($_POST['phone'])); 
		 
		 
	if(isset($_POST['email']) && !empty($_POST['email']) AND isset($_POST['pass1']) && !empty($_POST['pass1']) AND isset($_POST['pass2']) && !empty($_POST['pass2'])) 
	{ 
		$email = mysqli_real_escape_string($connect,clear_text($_POST['email'])); 
		$password = mysqli_real_escape_string($connect,clear_text($_POST['pass1'])); 
		$repassword = mysqli_real_escape_string($connect,clear_text($_POST['pass2'])); 
		 
		if(checkEmail($email) && checkPhone($phone)) 
		{ 
			if($password==$repassword) 
			{	 
				$uid = getToken(5); 
				$hash = md5( rand(0,1000) ); 
				$ipAddress = get_ip_address(); 
				 
				try 
				{ 
				$add_user = "INSERT INTO `customer_login`(`uid`,`email`, `password`,`hash`,`IPAddress`) VALUES ('$uid','$email','".password_hash($password, PASSWORD_DEFAULT)."','$hash','$ipAddress')"; 
				mysqli_query($connect,$add_user) or die(mysqli_error($connect)); 
				 
				$add_basic_info = "INSERT INTO `customer_info`(`uid`, `first`, `last`, `country_code`, `phone`, `lives_in`, `from`) VALUES ('$uid','$first','$last','','$phone','','')"; 
				mysqli_query($connect,$add_basic_info) or die(mysqli_error($connect)." basic"); 
				 
				mysqli_query($connect,"INSERT INTO `verificationtobesent`(`uid`,`first`,`email`,`hash`) VALUES ('$uid','$first','$email','$hash')") or die(mysqli_error($connect)." Verification"); 
					 
				$_SESSION['uid'] = $uid; 
				$_SESSION['uname'] = $first." ".$last; 
				 
				if(isset($_POST['requested_entity_id']) && !empty($_POST['requested_entity_id'])) 
				{ 
					$entity_id = mysqli_real_escape_string($connect,$_POST['requested_entity_id']); 
					mysqli_close($connect); 
					header("Location:../(address of previous page which user was viewing before account creation.php)?id=$entity_id&rclicked=yes"); 
					exit(); 
				} 
				 
				mysqli_close($connect); 
				header("Location:../index.php?message=successful&emailVerification=pending"); 
				/*Send to main page with message*/ 
				exit(); 
				} 
				catch(Exception $e) 
				{ 
					mysqli_close($connect); 
					header("Location:login.html?message=unsuccessful"); 
					exit();				 
				} 
			} 
		} 
		else 
		{ 
			mysqli_close($connect); 
			header("Location:login.html?message=emailalreadyexists"); 
			exit(); 
		} 
	} 
	else 
	{ 
		mysqli_close($connect); 
		header("Location:login.html?message=passwordmismatch"); 
		exit(); 
	} 
} 
else 
{ 
	mysqli_close($connect); 
	header("Location:login.php?message=unsuccessful"); 
	exit(); 
} 
?> 

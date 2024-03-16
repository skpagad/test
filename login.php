<?php  
session_start(); 
require_once "../includes/misc.inc"; 
require_once "../includes/clear_text.inc"; 
 
if($_SERVER['REQUEST_METHOD']=="POST") 
{ 
	$email = mysqli_escape_string($connect,clear_text($_POST['email']));  
	$mypassword = mysqli_escape_string($connect,clear_text($_POST['password'])); 
	if(!empty($email) && !empty($mypassword)) 
	{ 
		$select_user = "select l.uid,i.first,i.last,i.country_code,i.phone,l.email,i.phone,l.password from customer_info i INNER JOIN customer_login l on i.uid = l.uid WHERE l.email = '$email' LIMIT 1"; 
		$result = mysqli_query($connect,$select_user) or die(mysqli_error($connect)); 
		 
		$count = mysqli_num_rows($result); 
		 
		if($count==1) 
		{ 
			$row = mysqli_fetch_array($result); 
			//extract($row); 
 
			if(password_verify($mypassword,$row['password'])) 
            { 
				$uid = $row['uid']; 
				$_SESSION['uid'] = $uid; 
				$_SESSION['uname'] = $row['first']." ".$row['last']; 
				 
/*This code here handles the redirection to specific page you left previously so that user can login */ 
/*Consider you searched facebook page for redbus.in. You are now viewing www.facebook.com/redbus but user has not logged in yet. Now you want user to log in but also to redirect to same redbus page after successfull login. */ 
 
				if(isset($_POST['requested_entity_id']) && !empty($_POST['requested_entity_id'])) 
				{ 
					$entity_id = mysqli_real_escape_string($connect,$_POST['entity_id']); 
					mysqli_close($connect); 
					header("Location:../(address to page you previously left)?id=$entity_id&rclicked=yes"); 
					exit(); 
				} 
			 
				/*If it is fresh login attempt then redirect to customer panel */ 
				mysqli_close($connect); 
				header("Location:../customerpanel/index.php"); 
				exit(); 
			} //end of password verify if 
			else 	//username or password is wrong 
			{ 
				mysqli_close($connect); 
				header('Location:login.html?message=upwrong'); 
				exit(); 
			} 
		} 
		else //there are multiple accounts with same credentials. backup validation. it is not possible but we have considered that for safety purpose.  
		{ 
			mysqli_close($connect); 
			header('Location:login.html?message=unsuccessful'); 
			exit(); 
		} 
	} 
	else	//either password or username is missing. backup validation 
	{ 
		mysqli_close($connect); 
		header("Location:login.html?message=insufficient"); 
		exit(); 
	} 
} 
else	// request method is not POST. Someone is trying to mess around 
{ 
	mysqli_close($connect); 
	header("Location:login.php?message=unsuccessful"); 
	exit(); 
} 
?>
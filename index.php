<?php 
require_once 'php_action/db_connect.php';

session_start();

if(isset($_SESSION['userId'])) {
	header('location:'.$store_url.'dashboard.php');		
}

$errors = array();

if($_POST) {		

	$username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) || empty($password)) {
		if($username == "") {
			$errors[] = "Username is required";
		} 

		if($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $connect->query($sql);

		if($result->num_rows == 1) {
			$password = md5($password);
			// exists
			$mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$mainResult = $connect->query($mainSql);

			if($mainResult->num_rows == 1) {
				$value = $mainResult->fetch_assoc();
				$user_id = $value['user_id'];

				// set session
				$_SESSION['userId'] = $user_id;

				header('location:'.$store_url.'dashboard.php');	
			} else{
				
				$errors[] = "Incorrect username/password combination";
			} // /else
		} else {		
			$errors[] = "Username doesnot exists";		
		} // /else
	} // /else not empty username // password
	
} // /if $_POST
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .form {
    display: flex;
    justify-content: center;
    align-items: center;
    transform-style: preserve-3d;
    transition: all 1s ease;
  }
  
  .form .form_front {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 20px;
    position: absolute;
    backface-visibility: hidden;
    padding: 65px 45px;
    border-radius: 15px;
    box-shadow: inset 2px 2px 10px rgba(0,0,0,1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
  }
  
  .form .form_back {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 20px;
    position: absolute;
    backface-visibility: hidden;
    transform: rotateY(-180deg);
    padding: 65px 45px;
    border-radius: 15px;
    box-shadow: inset 2px 2px 10px rgba(0,0,0,1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
  }
  
  .form_details {
    font-size: 25px;
    font-weight: 600;
    padding-bottom: 10px;
    color: rgb(10, 10, 10);
  }
  
  .form-control {
    width: 245px;
    min-height: 45px;
    color: #fff;
    outline: none;
    transition: 0.35s;
    padding: 0px 7px;
    background-color: #212121;
    border-radius: 6px;
    border: 2px solid #212121;
    box-shadow: 6px 6px 10px rgba(0,0,0,1),
    1px 1px 10px rgba(255, 255, 255, 0.6);
  }
  
  .form-control::placeholder {
    color: #999;
  }
  
  .form-control:focus.input::placeholder {
    transition: 0.3s;
    opacity: 0;
  }
  
  .form-control:focus {
    transform: scale(1.05);
    box-shadow: 6px 6px 10px rgba(0,0,0,1),
    1px 1px 10px rgba(255, 255, 255, 0.6),
    inset 2px 2px 10px rgba(0,0,0,1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
  }
  
  .btn {
    padding: 10px 35px;
    cursor: pointer;
    background-color: #212121;
    border-radius: 6px;
    border: 2px solid #212121;
    box-shadow: 6px 6px 10px rgba(0,0,0,1),
    1px 1px 10px rgba(255, 255, 255, 0.6);
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    transition: 0.35s;
  }
  
  .btn:hover {
    transform: scale(1.05);
    box-shadow: 6px 6px 10px rgba(0,0,0,1),
    1px 1px 10px rgba(255, 255, 255, 0.6),
    inset 2px 2px 10px rgba(0,0,0,1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
  }
  
  .btn:focus {
    transform: scale(1.05);
    box-shadow: 6px 6px 10px rgba(0,0,0,1),
    1px 1px 10px rgba(255, 255, 255, 0.6),
    inset 2px 2px 10px rgba(0,0,0,1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
  }
  
  .form .switch {
    font-size: 13px;
    color: white;
  }
  
  .form .switch .signup_tog {
    font-weight: 700;
    cursor: pointer;
    text-decoration: underline;
  }
  
  .container #signup_toggle {
    display: none;
  }
  
  .container #signup_toggle:checked + .form {
    transform: rotateY(-180deg);
  }
    </style>
</head>
<body>
    <div class="container">
        <input id="signup_toggle" type="checkbox">
        <form class="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
            <div class="form_front">
                <div class="form_details">Login</div>
                    <div class="messages">
                        <?php if($errors) {
                            foreach ($errors as $key => $value) {
                                echo '<div class="alert alert-warning" role="alert">
                                <i class="glyphicon glyphicon-exclamation-sign"></i>
                                '.$value.'</div>';										
                                }
                            } ?>
                    </div>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" />
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off"/>
                        <button type="submit" class="btn">Login</button>
                </div>
        </form>
    </div>    
</body>
</html>
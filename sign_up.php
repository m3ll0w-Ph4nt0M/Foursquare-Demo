<?php 
	//database connection
    include_once('config/dbh.php');
    
    session_start();
    //destroy all sessions
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
    }
    
    //message to display to user
    $message = "";

    //when User tries to register
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

        //Assign variables
        $email    = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "INSERT INTO member (mem_email, 
        							  mem_username, 
        							  mem_password) 
				  VALUES (:email,
				  		  :username,
				  		  :password)
        		 ";

        $sth = $dbh->prepare($query);
		$sth->bindParam(':email',    $email,    PDO::PARAM_STR, 50);
		$sth->bindParam(':username', $username, PDO::PARAM_STR, 30);
		$sth->bindParam(':password', $password, PDO::PARAM_STR, 30);

		if($sth->execute()){
			$message = "Success!";
		}else{
			$message = "Please try again";
		}

    }else{
        //Do nothing
    }
?>

<html>

<head><meta http-equiv="Content-Type" content="text/html charset=us-ascii">
	<!-- CSS INCLUDE -->
	<link rel="stylesheet" type="text/css" href="include\chosen\chosen.css">
	<link rel="stylesheet" type="text/css" href="include\bootstrap\css\bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="include\DataTables-1.10.9\media\css\jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="css\custom.css">
	<!-- END CSS INCLUDE -->
</head>

<body style="" class="">

<form id="img_finder" role="form" method="POST">
	
	<div class="container">
		<div id ="header" class="row">
			<div class="col-md-9 col-xs-6">
				<img id="logo" src="img\ser_loc.png" class="img-responsive"><br>
				<img id="sub_logo" src="img\4sqr.png" class="img-responsive"><br>
			</div>

			<div class="col-md-3 col-xs-6">
				
			</div>
		</div>
		<br>

		<div id ="content" class="row">
			
			<div id="message" class="col-md-9 col-sm-6 col-xs-6">
			<img id="msg" src="img\signup_msg.png" class="img-responsive"><br>
			</div><!-- end message -->

			<div id="log_in" class="col-md-3 col-sm-6 col-xs-6">
				<br><h3><label class="label label-info"><span class="glyphicon glyphicon-star-empty"></span> Please fill in your details</label></h3><br>
				<input type="text" name="email" placeholder="email" class="form-control">
				<input type="text" name="username" placeholder="Username" class="form-control">
  				<input type="text" name="password" placeholder="Password" class="form-control"><br>
				<button class="btn btn-default "><span class="glyphicon glyphicon-hand-up"></span> Register</button>
				<button class="btn btn-default " onclick="Log_in()"><span class="glyphicon glyphicon-hand-up"></span> Back to log in</button>
				<br><h3><label class="label label-info"><?php echo $message;?></label></h3><br>
			</div><!-- log_in -->
		</div><!-- end content -->
	</div><!-- END Container -->



</form>

<!-- SCRIPT INCLUDE -->
<script type="text/javascript" src="js\jquery-1.11.3.min.js"> </script>
<script type="text/javascript" src="js\jquery-ui.min.js"> </script>
<script type="text/javascript" src="include\bootstrap\js\bootstrap.min.js"> </script>
<script type="text/javascript" src="include\chosen\chosen.jquery.min.js"> </script>
<script type="text/javascript" src="include\chosen\docsupport\prism.js"> </script>
<!-- END SCRIPT INCLUDE -->

<script type="text/javascript">
	function Log_in(){
    	event.preventDefault();
    	window.location.replace("index.php");
    }
</script>

</body>

</html>
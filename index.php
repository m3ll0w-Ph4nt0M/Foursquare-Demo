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

    //when User tries to log in
    if (isset($_POST['username']) && isset($_POST['password'])) {

        //Assign variables
        $username = $_POST['username'];
        $password = $_POST['password'];

        #GET id of user
        $sql = "SELECT `mem_id` 
        		FROM `member` 
        		WHERE `mem_username` = '$username' 
        		AND `mem_password` = '$password'
        		";

        $fetch = $dbh->query($sql);
        $info = $fetch->fetch(PDO::FETCH_ASSOC);
        
        if ($fetch->rowCount() > 0) {
              $user_id = $info['mem_id'];
              
              //Assign session variables
              $_SESSION['user_id'] = $user_id;

              include_once('insert/image.php');

             //Allow login if username and password are correct 
             echo("<script>window.location.href = 'img_search.php';</script>");
                
            } else {

               $message ="Invalid Username or Password!";
              
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
				<img id="sub_logo" src="img\4sqr.png" class="img-responsive">

			</div>

			<div class="col-md-3 col-xs-6">
				<br><br>
				<input type="text" name="username" placeholder="Username" class="form-control">
  				<input type="text" name="password" placeholder="Password" class="form-control"><br>
  				<button class="btn btn-default "><span class="glyphicon glyphicon-user"></span> Log In</button>
  				<button class="btn btn-default " onclick="Sign_up()"><span class="glyphicon glyphicon-hand-up"></span> Sign Up</button><br>
  				<br><label><?php echo $message;?></label><br>
  				<br>
			</div>
		</div>
		<br>

		<div id ="content" class="row">
			
			<div id="message" class="col-md-9 col-sm-6 col-xs-6">
			<img id="msg" src="img\login_msg.png" class="img-responsive"><br>
			</div><!-- end message -->

			<div id="log_in" class="col-md-3 col-sm-6 col-xs-6">
				
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
	function Sign_up(){
    	event.preventDefault();
    	window.location.replace("sign_up.php");
    }
</script>

</body>

</html>
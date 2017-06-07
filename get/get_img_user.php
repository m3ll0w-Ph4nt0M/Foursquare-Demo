<?php
include('../config/dbh.php');

$html = "";

#Perform Checks
if(isset($_POST['loc'])) {

	$loc = $_POST['loc'];
	
	$user_sql = "SELECT DISTINCT(`loc_img_user_id`) ,  `loc_img_user_fname` ,  `loc_img_user_lname` 
				 FROM  `loc_img_user` 
				 INNER JOIN  `loc_image` ON (  `loc_img_user`.loc_img_user_id =  `loc_image`.l_img_user_id ) 
				 WHERE loc_image.l_name = '{$loc}'
				";
	
	$rows = $dbh->query($user_sql);

	#Generate $options
	$options = "";
	while($row = $rows->fetch(PDO::FETCH_ASSOC)){
		$u_id    = $row['loc_img_user_id'];
		$u_fname = $row['loc_img_user_fname'];
		$u_lname = $row['loc_img_user_lname'];			
		$options .= "<option value='{$u_id}'>{$u_fname} {$u_lname}</option>";
	}

	#Generate $html
	$html = $options;
}

#Display $html
print($html);

?>

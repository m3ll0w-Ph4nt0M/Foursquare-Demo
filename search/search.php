<?php

include_once('config/dbh.php');

$table_content = "";

if(isset($_POST['t_id'])){
	$tab_selected = $_POST['t_id'];
	
	switch ($tab_selected){
		case 1:#Gen
				if(isset($_POST['location']) && isset($_POST['img_user'])){
					$loc  = $_POST['location'];
					$user = $_POST['img_user'];
						
					$table_query = "SELECT `loc_image`.*,`loc_img_user`.loc_img_user_fname,`loc_img_user`.loc_img_user_lname 
									FROM `loc_image`
									INNER JOIN `loc_img_user` 
									ON ( `loc_img_user`.loc_img_user_id = `loc_image`.l_img_user_id ) 
									WHERE `l_name` = '{$loc}'
									AND `l_img_user_id` = {$user}
								   ";

					$fetch = $dbh->query($table_query);
				    while($info = $fetch->fetch(PDO::FETCH_ASSOC)){

					    #variables
					    {
					        $id                 = $info['id'];
					        $loc_img_id         = $info['loc_img_id'];
					        $l_name             = $info['l_name'];
					        $loc_img_src_name   = $info['loc_img_src_name'];
					        $loc_img_src_url    = $info['loc_img_src_url'];
					        $loc_img_user_fname = $info['loc_img_user_fname'];
					        $loc_img_user_lname = $info['loc_img_user_lname'];
					    }
					    $table_content .= "
					    					<tr>
					    						<td>{$id}</td>
					    						<td><a href='$loc_img_src_url'><img id='img' src='{$loc_img_src_url}' class='img-responsive'></a></td>
					    						<td>{$loc_img_id}</td>
					    						<td>{$l_name}</td>
					    						<td>{$loc_img_src_name}</td>
					    						<td>{$loc_img_user_fname} {$loc_img_user_lname}</td>
					    					</tr>
					    				  ";
					}
				}else{
					//Do nothing
				}
			
			break;
		
		case 2:#loc
				if(isset($_POST['multi_loc_select'])){
					$mul_locs[] = $_POST['multi_loc_select'];
		
					foreach ($mul_locs as $mul_loc) {
						foreach ($mul_loc as $mul_location) {

							$table_query = "SELECT `loc_image`.*,`loc_img_user`.loc_img_user_fname,`loc_img_user`.loc_img_user_lname 
										FROM `loc_image`
										INNER JOIN `loc_img_user` 
										ON ( `loc_img_user`.loc_img_user_id = `loc_image`.l_img_user_id ) 
										WHERE `l_name` = '{$mul_location}'
									   ";

							$fetch = $dbh->query($table_query);
						    while($info = $fetch->fetch(PDO::FETCH_ASSOC)){

							    #variables
							    {
							        $id                 = $info['id'];
							        $loc_img_id         = $info['loc_img_id'];
							        $l_name             = $info['l_name'];
							        $loc_img_src_name   = $info['loc_img_src_name'];
							        $loc_img_src_url    = $info['loc_img_src_url'];
							        $loc_img_user_fname = $info['loc_img_user_fname'];
							        $loc_img_user_lname = $info['loc_img_user_lname'];
							    }

							    $table_content .= "
							    					<tr>
							    						<td>{$id}</td>
							    						<td><a href='$loc_img_src_url'><img id='img' src='{$loc_img_src_url}' class='img-responsive'></a></td>
							    						<td>{$loc_img_id}</td>
							    						<td>{$l_name}</td>
							    						<td>{$loc_img_src_name}</td>
							    						<td>{$loc_img_user_fname} {$loc_img_user_lname}</td>
							    					</tr>
							    				  ";
							}
						}
					}
				}else{
					//Do nothing
				}

				break;

		default:
			#Do nothing
			break;
	}
}

?>


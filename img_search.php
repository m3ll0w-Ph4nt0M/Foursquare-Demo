<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    //if user not logged in redirrect
    echo "<script>location.href = 'index.php';</script>";
    die();
}

include_once('config/dbh.php');
include_once('search/search.php');

#populate multisearch
$location_sql = "SELECT *
				 FROM `location`
				";

$loc_search_option = "";
$fetch = $dbh->query($location_sql);
while ($row = $fetch ->fetch()){
	$loc_id   = $row['id'];
	$loc_name = $row['loc_name'];
    $loc_search_option .= "<option value='$loc_name'>$loc_name</option>";
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

<form id="img_finder" role="form" action="img_search.php" method="POST">
	
	<div class="container">
		<div id ="header" class="row">
			<div class="col-md-9 col-xs-6">
				<br>
				<img id="logo" src="img\ser_loc.png" class="img-responsive"><br>
				<img id="sub_logo" src="img\4sqr.png" class="img-responsive"><br>
			</div>

			<div class="col-md-3 col-xs-6">
					<br>
					<button class="btn btn-default pull-right" onclick="Log_out()"><span class="glyphicon glyphicon-user"></span> Log Out</button>
			</div>
		</div>
		<br>

		<div id ="content" class="row">
			
			<div id="search" class="col-md-3 col-sm-12 col-xs-12">
				<center>
					<!-- START TAB -->
					<ul id="tab" class="nav nav-tabs">
						<li id="1"><a href="#gen" data-toggle="tab"><span class="glyphicon glyphicon-zoom-in"></span> General</a></li>
						<li id="2"><a href="#loc" data-toggle="tab"><span class="glyphicon glyphicon-zoom-out"></span> Locations</a></li>
					</ul>

					<div class="tab-content">
						<div id="gen" class="tab-pane fade active in">
							<br>
							<select id="location" name="location" placeholder="select a location" class="form-control">
								<option value="" selected disabled>Select a location</option>
								<?php echo $loc_search_option;?>
							</select><br>

							<select id="img_user" name="img_user" placeholder="first select a location" class="form-control">
							</select><br><br>	

							<button id="loc_single" type="submit" class="btn btn-primary" value="1"><span class="glyphicon glyphicon-search"></span> Search!</button><br><br>
						</div>
						
						<div id="loc" class="tab-pane fade active">
							<br>
							<select id="multi_loc_select" name="multi_loc_select[]" data-placeholder="select multiple locations" multiple class="chosen-select">
								<?php echo $loc_search_option;?>
							</select><br><br>

							<button id="loc_multi" type="submit" class="btn btn-primary" value="2"><span class="glyphicon glyphicon-search"></span> Search!</button><br><br>
						</div>
						<!--hidden input-->
						<input type="hidden" name="t_id" id="t_id" value="1"/>
					</div><!-- END TAB -->
				</center>
			</div><!-- end search -->

			<div id="table" class="col-md-9 col-sm-12 col-xs-12">
				<table id="img_table" name="img_table" class="table table-responsive table-hover" data-toggle="table" data-show-columns="true" cellspacing="0">
					<thead>
						<tr class="info">
							<th>ID</th>
							<th>Image</th>
							<th>Image ID</th>
							<th>location</th>
							<th>Source</th>
							<th>Creator</th>
						</tr>	
					</thead>
					<tbody>
						<?php echo $table_content; ?>
					</tbody>
				</table>
			</div><!-- end table -->

		</div><!-- end content -->
	</div><!-- end container -->

</form>

<!-- SCRIPT INCLUDE -->
<script type="text/javascript" src="js\jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js\jquery-ui.min.js"></script>
<script type="text/javascript" src="include\bootstrap\js\bootstrap.min.js"></script>
<script type="text/javascript" src="include\chosen\chosen.jquery.min.js"></script>
<script type="text/javascript" src="include\chosen\docsupport\prism.js"></script>
<script type="text/javascript" src="include\DataTables-1.10.9\media\js\jquery.dataTables.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$('#img_table').DataTable({
			ordering: false,
			searching: false
		});

		$('#loc').removeClass('active');
		$('#search').addClass('Thumbnail');
	});

	// chosen settings
	$(".chosen-select").chosen({
		disable_search_threshold: 10,
		no_results_text: "Oops, nothing found!",
		width: "80%"
	});

</script>

<script type="text/javascript">
	// dropdown populator
	var loc;
    $(document).ready(function() {

        //Populate #stream_content
        $('#location').change(function() {          

            loc = $(this).val();

            $('#img_user').empty();

            $.post(
                'get/get_img_user.php',
                {
                    loc:loc
                },
                function(data){
                    $('#img_user').html(data);
                }
            );
        });
    
    });
	
	//identify which tab was selected
    $('#tab li').click(function() {
        var id = $(this).attr("id");
        $("#t_id").val(id);
    });

    function Log_out(){
    	event.preventDefault();
    	window.location.replace("index.php");
    }

</script>

<!-- END SCRIPT INCLUDE -->

</body>

</html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AJAX POSTING MESSAGE</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

	//##### send add record Ajax request to response.php #########
	$("#FormSubmit").click(function (e) {
			e.preventDefault();
			if($("#contentText").val()==='')
			{
				alert("Ketikkan message anda!");
				return false;
			}
			
			$("#FormSubmit").hide(); //hide submit button
			$("#LoadingImage").show(); //show loading image
			
		 	var myData = 'pesan_anda='+ $("#contentText").val(); //build a post data structure
			jQuery.ajax({
			type: "POST", // HTTP method POST or GET
			url: "response.php", //Where to make Ajax calls
			dataType:"text", // Data type, HTML, json etc.
			data:myData, //Form variables
			success:function(response){
				$("#responds").append(response);
				$("#contentText").val(''); //empty text field on successful
				$("#FormSubmit").show(); //show submit button
				$("#LoadingImage").hide(); //hide loading image

			},
			error:function (xhr, ajaxOptions, thrownError){
				$("#FormSubmit").show(); //show submit button
				$("#LoadingImage").hide(); //hide loading image
				alert(thrownError);
			}
			});
	});

	//##### Send delete Ajax request to response.php #########
	$("body").on("click", "#responds .del_button", function(e) {
		 e.preventDefault();
		 var clickedID = this.id.split('-'); //Split ID string (Split works as PHP explode)
		 var DbNumberID = clickedID[1]; //and get number from array
		 var myData = 'recordToDelete='+ DbNumberID; //build a post data structure
		 
		$('#item_'+DbNumberID).addClass( "sel" ); //change background of this element by adding class
		$(this).hide(); //hide currently clicked delete button
		 
			jQuery.ajax({
			type: "POST", // HTTP method POST or GET
			url: "response.php", //Where to make Ajax calls
			dataType:"text", // Data type, HTML, json etc.
			data:myData, //Form variables
			success:function(response){
				//on success, hide  element user wants to delete.
				$('#item_'+DbNumberID).fadeOut();
			},
			error:function (xhr, ajaxOptions, thrownError){
				//On error, we alert user
				alert(thrownError);
			}
			});
	});

});
</script>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="content_wrapper">
<ul id="responds">
<?php
//include db configuration file
include_once("koneksi.php");

//MySQL query
$results = $mysqli->query("SELECT id_status,status FROM status");
//get all records from status table
while($row = $results->fetch_assoc())
{
  echo '<li id="item_'.$row["id_status"].'">';
  echo '<div class="del_wrapper">
			<a href="#" class="del_button" id="del-'.$row["id_status"].'">';
			echo '<img src="images/icon_del.gif" border="0" />';
	  echo '</a>
		</div>';	
  echo $row["status"].'</li>';
}

//close db connection
$mysqli->close();
?>
</ul>
    <div class="form_style">
    <textarea name="pesan_anda" id="contentText" cols="45" rows="5" placeholder="Masukkan pesan anda"></textarea>
    <button id="FormSubmit">Simpan Pesan</button>
    <img src="images/loading.gif" id="LoadingImage" style="display:none" />
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insert Program</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
	<style type="text/css">
		.header{
			width: 100%;
			height: 100px;
			background-color: red;
		}

		.title{
			color: white;
		}
		.form{
			
			width: 50rem;
			height: auto;

		}
	</style>
</head>
<body>
	<div class="container-fluid header">
		<div class="t-holder">
			<br>
			<center><h3 class="title">Insert Program</h3></center>
			
		</div>
		
	</div>
	
			<div class="row container form">
			
	<form method="POST" id="form">
                                <div class="mb-3">
                                    <label for="program_name" class="form-label">Program Name</label>
                                    <input class="form-control"  name="program_name" id="program_name" placeholder="Enter program name">
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Departments</label>
                                        <input class="form-control" list="departments" name="department" id="department" placeholder="Enter department name">
                                                <datalist id="departments">
                                                    <?php 
                                                        include "../config.php";
                                                        $sql = "SELECT DISTINCT department_name FROM departments";
                                                        $result = mysqli_query($conn,$sql);

                                                        while($row = mysqli_fetch_array($result)){
                                                           $department = $row['department_name']
                                                        
                                                    ?>
                                                  <option value="<?php echo $department?>">
                                                  <?php }?>
                                                </datalist>    
                                 </div>
                                <div class="mb-3">
                                    <label for="abbrv" class="form-label">Course Code</label>
                                    <input class="form-control" name="abbrv" id="abbrv" placeholder="Enter abbrv">
                                </div>
                                
                                
  
  <button type="submit" type="submit"class="btn btn-primary">Submit</button>
</form>
		</div>
	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#form").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "insertProgram.php", // PHP file to handle the insertion
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: function(response) {
            // Handle the response from the PHP file
            $("#form").trigger('reset');
            alert(response);
         },
         error: function(xhr, status, error) {
            // Handle errors
            console.error(error); // Log the error message
         }
      });
   });
});
</script>
</body>
</html>
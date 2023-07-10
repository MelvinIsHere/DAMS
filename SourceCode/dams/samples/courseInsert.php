<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insert Course</title>
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
			<center><h3 class="title">Insert Course</h3></center>
			
		</div>
		
	</div>
	
			<div class="row container form">
			
	<form method="POST" id="form">
                                <div class="mb-3">
                                    <label for="course" class="form-label">Course Name</label>
                                    <input class="form-control" list="courses" name="course" id="course" placeholder="Enter course name">
                                </div>
                                <div class="mb-3">
                                    <label for="course_code" class="form-label">Course Code</label>
                                    <input class="form-control" name="course_code" id="course_code" placeholder="Enter course code">
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
                                <div class="mb-3 row">
                                    <div class="col">
                                         <label for="units" class="form-label">Units</label>
                                    <input class="form-control" type="number" name="units" id="units" placeholder="Enter units">
                                    </div>
                                    <div class="col">
                                         <label for="lecture" class="form-label">Lecture hours / week</label>
                                    <input class="form-control" type="number" name="lecture" id="lecture" placeholder="Enter hours">
                                    </div>
                                      <div class="col">
                                         <label for="rle" class="form-label">Rle hours / week</label>
                                    <input class="form-control" type="number" name="rle" id="rle" placeholder="Enter hours">
                                    </div>
                                     <div class="col">
                                         <label for="lab" class="form-label">Rle hours / week</label>
                                    <input class="form-control" type="number" name="lab" id="lab" placeholder="Enter hours">
                                    </div>
                                   
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
         url: "insertCourse.php", // PHP file to handle the insertion
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
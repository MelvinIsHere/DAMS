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
			<center><h3 class="title">Insert Section</h3></center>
			
		</div>
		
	</div>
	
			<div class="row container form">
			
	<form method="POST" id="form">
                                <div class="mb-3">
                                    <label for="section_name" class="form-label">Section Name</label>
                                    <input class="form-control"  name="section_name" id="section_name" placeholder="Enter section name">
                                </div>
                                <div class="mb-3">
                                    <label for="course" class="form-label">Course name</label>
                                        <input class="form-control" list="courses" name="course" id="course" placeholder="Enter course name">
                                                <datalist id="courses">
                                                    <?php 
                                                        include "../config.php";
                                                        $sql = "SELECT DISTINCT course_description FROM courses";
                                                        $result = mysqli_query($conn,$sql);

                                                        while($row = mysqli_fetch_array($result)){
                                                           $courses = $row['course_description']
                                                        
                                                    ?>
                                                  <option value="<?php echo $courses?>">
                                                  <?php }?>
                                                </datalist>    
                                 </div>
                                 <div class="mb-3">
                                    <label for="program" class="form-label">Program</label>
                                        <input class="form-control" list="programs" name="program" id="program" placeholder="Enter program name">
                                                <datalist id="programs">
                                                    <?php 
                                                        include "../config.php";
                                                        $sql = "SELECT DISTINCT program_title FROM programs";
                                                        $result = mysqli_query($conn,$sql);

                                                        while($row = mysqli_fetch_array($result)){
                                                           $programs = $row['program_title']
                                                        
                                                    ?>
                                                  <option value="<?php echo $programs?>">
                                                  <?php }?>
                                                </datalist>    
                                 </div>
                                 <div class="mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                        <input class="form-control" list="semesters" name="semester" id="semester" placeholder="Enter semester">
                                                <datalist id="semesters">
                                                    <?php 
                                                        include "../config.php";
                                                        $sql = "SELECT DISTINCT sem_description FROM semesters";
                                                        $result = mysqli_query($conn,$sql);

                                                        while($row = mysqli_fetch_array($result)){
                                                               $semesters = $row['sem_description']
                                                        
                                                    ?>
                                                  <option value="<?php echo $semesters?>">
                                                  <?php }?>
                                                </datalist>    
                                 </div>
                                <div class="mb-3">
                                    <label for="total_stud" class="form-label">Total number of students</label>
                                    <input class="form-control" name="total_stud" id="total_stud" placeholder="Enter total number of students">
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
         url: "insertSection.php", // PHP file to handle the insertion
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
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insert titles</title>
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
			<center><h3 class="title">Insert faculty title</h3></center>
			
		</div>
		
	</div>
	
			<div class="row container form">
			
	<form method="POST" id="form">
  <div class="mb-3">
    <label for="lname" class="form-label">faculty Name</label>
                                    <input class="form-control" list="faculty_names" name="faculty_name" id="faculty_name" placeholder="Enter faculty name">
                                    <datalist id="faculty_names">
                                        <?php 
                                            include "../config.php";
                                            $sql = "SELECT DISTINCT firstname,lastname,middlename FROM faculties";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                               $faculty_name = $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'];
                                            
                                        ?>
                                      <option value="<?php echo $faculty_name?>">
                                      <?php }?>
                                    </datalist>

    
  </div>
   <div class="mb-3">
    <label for="lname" class="form-label">Titles</label>
                                    <input class="form-control" list="titles" name="title" id="title" placeholder="Enter title name">
                                    <datalist id="titles">
                                        <?php 
                                            include "../config.php";
                                            $sql = "SELECT DISTINCT title_description FROM titles";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                               $title = $row['title_description']
                                            
                                        ?>
                                      <option value="<?php echo $title?>">
                                      <?php }?>
                                    </datalist>

    
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
         url: "insertFacultyTitles.php", // PHP file to handle the insertion
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
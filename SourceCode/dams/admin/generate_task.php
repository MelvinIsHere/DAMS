<?php 
include "../config.php";
session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];



    $data = mysqli_query($conn,"SELECT 
            u.user_id,
            u.unique_id,
            u.email,
            u.password,
            u.img,
            u.status,
            u.type,
            d.department_name,
            d.department_abbrv
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
            WHERE user_id = '$id'
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    $department_name = $data_result['department_name'];

    if($data_result){


?>
<!DOCTYPE html>
<html>
<?php  include "../header/header.php"?>
<body>
    <nav class="navigation">
        <h3 class="title-nav">Create Task</h3><br>
    </nav>
    <center>
        <div>
            <form method="POST" action="" id="form">
                <div class="row" id="row">
                    <div class="col-xs-12 col-sm-7">
                        <label for="lname" class="form-label">Task name</label>
                                    <input class="form-control" list="task_names" name="task_name" id="task_name" placeholder="Enter task name">
                                    <datalist id="task_names">
                                        <?php 
                                            include "../config.php";
                                            $sql = "SELECT template_title FROM document_templates";
                                            $result = mysqli_query($conn,$sql);

                                            while($row = mysqli_fetch_array($result)){
                                               $task_names = $row['template_title'];
                                            
                                        ?>
                                      <option value="<?php echo $task_names?>">
                                      <?php }?>
                                    </datalist>

                    <!--     <label>Task Name</label>
                        <br>
                        <input type="text" class="input-date" placeholder="Type task name..." name="task_name"> -->
                        <br>
                        <br>
                        <label>Task Description</label>
                        <br>
                        <input type="text" class="input-description" placeholder="Type something..." name="description">

                    </div>              
                    <div class="col-xs-12 col-sm-4">
                        <div>
                            <label class="label">Date Start</label>
                            <br>
                            <input type="date" name="dateStart" class="input-date" />
                            <br>
                            <br>
                            <label class="label">Due Date</label>
                            <br>
                            <input type="date" name="dateEnd" class="input-date" />
                            <br><br>
                        <div>
                            <h5>Assigned to:</h5>
                            <input type="checkbox" name="dean" value="1"> Deans of Colleges   
                            <input type="checkbox" name="ovcaa" value="1">  Office of Vice Chancellor of Academic Affairs
                        </div>
                        </div>
                        <br>
                        <button id="submit"type="submit"  name="submit"class="btn btn-primary btn-upload">Upload</button>
                    </div>
                </div>

            </form>
        </div>
    </center>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#form").submit(function(e) {
      e.preventDefault(); // Prevent the form from submitting normally

      // Get the form data
      var formData = new FormData(this);

      // Send the AJAX request
      $.ajax({
         url: "task.php", // PHP file to handle the insertion
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

<?php }}?>
<?php 
include "../config.php";
session_start();

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];
    $id = $_SESSION['user_id'];



    $data = mysqli_query($conn,"SELECT 
                                u.`email`,
                                u.`password`,
                                u.`type`,
                                u.`img`,
                                u.`unique_id`,
                                u.`user_id`,
                                f.`department_id`,
                                f.`designation`,
                                f.`firstname`,
                                f.`middlename`,
                                f.`lastname`,
                                f.`suffix`,
                                f.`designation`,
                                f.`position`,
                                dp.`department_abbrv`,
                                dp.`department_name`,
                                dp.`department_id`

                            FROM users u 
                            LEFT JOIN faculties f ON f.`faculty_id` = u.faculty_id
                            LEFT JOIN departments dp ON dp.`department_id` = f.`department_id`
                            WHERE u.user_id = '$id'
    
            ");
    

    if($data){
        $task_id = $_GET['id'];
        $task = mysqli_query($conn,"SELECT * FROM tasks WHERE task_id = '$task_id'");
        $task_data = mysqli_fetch_assoc($task);
        if($task_data){
            $task_name = $task_data['task_name'];
            $task_posted = $task_data['date_posted'];
            $task_due = $task_data['due_date'];
            $task_desc = $task_data['task_desc'];
            $status_id = $_GET['status_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Task | Submission</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Add your custom styles in styles.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-dark" style="background: #A52A2A;height: 100px">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Pending Documents</span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $task_name; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><strong>Due date : </strong><?php echo $task_posted; ?></h6>
                        <hr>
                        <h5 class="card-title">Description</h5>
                        <p class="card-text"><?php echo $task_desc; ?><br>See google doc for reference.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data"
                            id="form">
                            <div class="form-group">
                                <label for="file">Choose File:</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" required>
                                    <label class="custom-file-label" for="file">Select file...</label>
                                </div>
                            </div>
                            <input type="" name="task_status_id" value="<?php echo $status_id;?>" hidden>
                            <input type="text" name="task_id" value="<?php echo $task_id; ?>" style="width: 0px; height: 0px; display: none;">
                            <div id="file-info" class="mt-3"></div>

                            <div class="progress mt-3" style="display: none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <ul class="uploaded-area"></ul>

                            <button type="submit" class="btn btn-success btn-block" name="submit" id="submit">Submit</button>
                        </form>
                        <div class="progress mt-3" style="display: none;">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
<script type="text/javascript">
$(document).ready(function () {
    $("#form").submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "../upload_file.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Handle the success response
                alert(response);
                window.location.href = "pendingDocuments.php";
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error(error);
                alert("Error occurred while uploading the file. Please try again later.");
            }
        });
    });
});
</script>


<script type="text/javascript">
  $(document).ready(function() {
    // When the file input changes
    $("#file").change(function() {
        // Get the selected file
        var file = $(this)[0].files[0];

        // Display file information dynamically
        var fileInfo = `<strong>File Information:</strong><br>`;
        fileInfo += `Name: ${file.name}<br>`;
        fileInfo += `Size: ${formatFileSize(file.size)}<br>`;
        fileInfo += `Type: ${file.type}`;

        // Display the file information in the 'file-info' div
        $("#file-info").html(fileInfo);
    });

    // Function to format file size in human-readable format
    function formatFileSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});

</script>



<script>
const form = document.querySelector("form"),
fileInput = document.querySelector(".file-input"),
progressArea = document.querySelector(".progress-area"),
uploadedArea = document.querySelector(".uploaded-area");

form.addEventListener("click", () =>{
  fileInput.click();
});

fileInput.onchange = ({target})=>{
  let file = target.files[0];
  if(file){
    let fileName = file.name;
    if(fileName.length >= 12){
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(fileName);
  }
}

function uploadFile(name){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/upload.php");
  xhr.upload.addEventListener("progress", ({loaded, total}) =>{
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize;
    (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024*1024)).toFixed(2) + " MB";
    let progressHTML = `<li class="row">
                          <i class="fas fa-file-alt"></i>
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;
    if(loaded == total){
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row">
                            <div class="content upload">
                              <i class="fas fa-file-alt"></i>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <i class="fas fa-check"></i>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });
  let data = new FormData(form);
  xhr.send(data);
}
</script>
  <script type="text/javascript">
        $(document).ready(function () {
            const form = document.getElementById("form");
            const fileInput = document.getElementById("file");
            const progressArea = document.querySelector(".progress");
            const uploadedArea = document.querySelector(".uploaded-area");

            fileInput.addEventListener("change", function () {
                let file = fileInput.files[0];
                if (file) {
                    let fileName = file.name;
                    if (fileName.length >= 12) {
                        let splitName = fileName.split('.');
                        fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
                    }

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "php/upload.php");
                    xhr.upload.addEventListener("progress", function (event) {
                        let percent = Math.round((event.loaded / event.total) * 100);
                        progressArea.style.display = "block";
                        progressArea.innerHTML = `${percent}%`;
                        progressArea.style.width = `${percent}%`;

                        if (percent === 100) {
                            progressArea.innerHTML = "Uploaded!";
                            setTimeout(() => {
                                progressArea.style.display = "none";
                            }, 1000);
                        }
                    });

                    let formData = new FormData(form);
                    xhr.send(formData);
                }
            });
        });
    </script>

   <script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="script.js"></script> <!--Add your custom JavaScript in script.js -->
</body>
</html>
<?php }
}
}
else{

}?>

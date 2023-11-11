<?php 
include "../config.php";
session_start();
$task_id = $_GET['id'];

 if(isset($_SESSION['unique_id']) && isset($_SESSION['user_id'])){
    $users_id = $_SESSION['unique_id'];

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
            LEFT JOIN departments d ON u.user_id = d.user_id
            WHERE unique_id = '$users_id'
    
            ");
    $data_result = mysqli_fetch_assoc($data);
    

    if($data_result){
        $task = mysqli_query($conn,"SELECT * FROM tasks WHERE task_id = '$task_id'");
        $task_data = mysqli_fetch_assoc($task);
        if($task_data){
            $task_name = $task_data['task_name'];
            $task_posted = $task_data['date_posted'];
            $task_due = $task_data['due_date'];
            $task_desc = $task_data['task_desc']
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Task | Submsission</title>
    <link rel="stylesheet" type="text/css" href="deans.css">
    <link rel="stylesheet" type="text/css" href="submission-pending-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
     <nav class="navigation">
      <h3 class="title-nav">Pending Documents</h3><br>
    </nav>
    <center>
        
            <div class="row" id="row">
                <div class="col-xs-12 col-sm-7">
                  <div class="header">
                    <h3><?php echo $task_name;?></h3>
                    <h6><?php echo $task_posted;?></h6>
                  </div>

                    <hr class="hr">
                    <p class="due-date">Due Date: <?php echo $task_posted;?></p>
                    <p><?php echo $task_desc; ?><br> 
                        <br>
                        See google doc for reference.
                    </p>
                </div>
                <div class="col-xs-12 col-sm-4" id="upload">
                  <center>
                    <div class="body">
                      <form action="../upload_file.php" method="POST" enctype="multipart/form-data">
                              <div class="wrapper">
                                <header>File Uploader JavaScript</header>
                                
                                  <input require class="file-input" type="file" name="file" hidden  accept=".xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.doc, .docx, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                  <i class="fas fa-cloud-upload-alt"></i>
                                  <p>Browse File to Upload</p>
                                
                                <section class="progress-area"></section>
                                <section class="uploaded-area"></section>
                       <a class="btn btn-primary btn-sm" href="#">Upload</a>
                              </div>
                        
                  </center>
                    </div>
                </div>
                <input type="text" name="task_id" value="<?php echo $task_id; ?>" style="width: 0px; height: 0px; display: none;">
            </div>
            <br><br><br><br>
            <button type="submit" name="submit" class="btn btn-success pull-right ">Submit</button>
       </form>
   </center>
<!-- <a class="btn btn-warning btn-sm" href="deans.php">Back</a> -->
 






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

</body>
</html>
<?php }
}
}
else{

    header("Location: ../index.php");
}?>
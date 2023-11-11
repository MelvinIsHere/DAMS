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
            d.department_abbrv,
            d.department_id
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.department_id
           WHERE user_id = '$id'");
    
    while($row = mysqli_fetch_array($data)){
        $department_name = $row['department_name'];
        $img = $row['img'];
        $type =$row['type'];
        $department_id = $row['department_id'];
        $department_abbrv = $row['department_abbrv'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style type="text/css">
        body{
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar{
            min-height: 100px;
            background-color: #A52A2A;
            
        }
        .title{
            color: white;
        }
        .form-control{
            height: 50px;
        }

        
        .btn{
            
            width: 50%;
            height: 50px;
            position: relative;
            border-radius: 50px;
        }
        .break{
            margin-top: 50px;
        }
        .submit{
            
            width: 150px;
            height: 50px;
            position: relative;



        }
     .container.footer {
        margin-top: auto;
        }
    


    </style>
</head>
<body>
  <div class="container-fluid topbar">
    
    <h3 class="title"><center>Individual Performance Commitment and Review</center></h3>
</div>

<div class="container">
    <form class="row" action="../php/insert_ipcr.php" method="POST" id="form">
        <!-- Your form elements go here -->
        <div id="inputs" class="container ">
            
            
        </div>


      <div class="d-flex justify-content-end">
          <button class="btn-success submit mt-3 " type="submit" onclick="submitForm()">Submit</button>
      </div>
    </form>
 
     
    
        
</div>
<div class="container footer">
    <div class="row container mx-auto align-self-stretch mt-4">
        
                             
                    <button class="btn-success btn" onclick="addInput('event')"><center>+</center></button>                              
                    <button class="btn-danger btn" onclick="removeLastInput()"><center>-</center></button>                
            
             
  
        
      
      
    </div>
</div>



<script type="text/javascript">
var addedInputs = []; // Array to store added input elements
function addInput() {

    var input_div = document.getElementById('inputs');

    // Create the main row div with class "row"
    
    var rowDiv = document.createElement("div");
    rowDiv.classList.add("row", "g-3","break"); // Use Bootstrap class "g-3" for gutter spacing

    var colDiv = document.createElement("div");
    colDiv.classList.add("col-md-4");

    var label_mfo = document.createElement("label");
    label_mfo.classList.add("form-label");
    label_mfo.innerText = "Major Final Output";
    label_mfo.for = "form-label";

    var mfo_input = document.createElement("input");
    mfo_input.classList.add("form-control");
    mfo_input.name = "mfo[]";
    
    // mfo_input.style.width = "100%"; // Set the input width to 100%
    // mfo_input.style.marginLeft = "5px";

  

    var colDiv2 = document.createElement("div");
    colDiv2.classList.add("col-md-4");

    var label_success_indicator = document.createElement("label");
    label_success_indicator.classList.add("form-label");
    label_success_indicator.innerText = "Success Indicator";

    var success_indicator_input = document.createElement("input");
    success_indicator_input.classList.add("form-control");
    success_indicator_input.name = "success_indicator[]";
    // success_indicator_input.style.width = "100%"; // Set the input width to 100%
    // success_indicator_input.style.marginLeft = "8px";

    var colDiv3 = document.createElement("div");
    colDiv3.classList.add("col-md-2");

    var mfo_category_label = document.createElement("label");
    mfo_category_label.classList.add("form-label");
    mfo_category_label.innerText = "Category";

    var mfo_category_drop_down = document.createElement("select");
    mfo_category_drop_down.classList.add("form-control","category");
    mfo_category_drop_down.name = "category[]";

    var option_1 = document.createElement("option");
    option_1.value = "INSTRUCTION";
    option_1.text = "INSTRUCTION";
    option_1.classList.add("option");

    var option_2 = document.createElement("option");
    option_2.value = "STRATEGIC";
    option_2.text = "STRATEGIC";
    option_2.classList.add("option");


    var option_3 = document.createElement("option");
    option_3.value = "SUPPORT";
    option_3.text = "SUPPORT";
    option_3.classList.add("option");



    var colDiv4 = document.createElement("div");
    colDiv4.classList.add("col-md-2");

    var description_label = document.createElement("label");
    description_label.classList.add("form-label");
    description_label.innerText = "Description";

    var description = document.createElement("input");
    description.classList.add("form-control");
    description.name = "description[]";


   


    mfo_category_drop_down.appendChild(option_1);
    mfo_category_drop_down.appendChild(option_2);
    mfo_category_drop_down.appendChild(option_3);



    colDiv.appendChild(label_mfo);
    colDiv.appendChild(mfo_input);
    
    colDiv2.appendChild(label_success_indicator);
    colDiv2.appendChild(success_indicator_input);

    colDiv3.appendChild(mfo_category_label);
    colDiv3.appendChild(mfo_category_drop_down);

    colDiv4.appendChild(description_label);
    colDiv4.appendChild(description);


    rowDiv.appendChild(colDiv);
    rowDiv.appendChild(colDiv2);
    rowDiv.appendChild(colDiv3);
    rowDiv.appendChild(colDiv4);


    input_div.appendChild(rowDiv);

    // Store the added elements in the array
    addedInputs.push(rowDiv);

   

    // var btn = document.createElement("button");
    // btn.setAttribute("id","button");



}
function removeLastInput() {
    var input_div = document.getElementById('inputs');

    // Check if there are any elements to remove
    if (addedInputs.length > 0) {
        var lastAddedElement = addedInputs.pop(); // Get the last added element from the array
        input_div.removeChild(lastAddedElement); // Remove the element from the DOM
    }
}

function submitForm() {
    var form = document.getElementById("form");
    form.submit();
}

addInput();


</script>
<?php }
}
else{

    header("Location: ../index.php");
}?>
</body>
</html>
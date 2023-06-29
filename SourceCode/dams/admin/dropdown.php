
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dropdown auto complete</title>
</head>
<body>
	<label for="search">Faculty</label>

<select name="cars" id="search" style="width: 200px">
    <option value=''>  </option>
    <?php 
             $conn = new mysqli("localhost","root","","dams");
            if ($conn->connect_error) {
                    die("Connection failed : " . $conn->connect_error);
            }
                $sql = "SELECT DISTINCT first_name FROM data_start";
                $result = $conn->query($sql);
                while($row = mysqli_fetch_array($result)){
                    $name = $row['first_name'];
                  
                    
                
    ?>        
  
  <option value='<?php echo $name;?>'><?php echo $name;?></option>
  
  <?php } ?>
</select>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<script type="text/javascript">
    $("#search").chosen();
</script>
</body>

</html>
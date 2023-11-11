<!DOCTYPE html>
<html>
<?php include "header/header.php";

?>
<body>
  <a href="pass.php">passs value here</a>




  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <?php 

  if(isset($_GET['alert'])){
    $value = $_GET['alert'];
    if($value == "sucess"){
      echo "<script type='text/javascript'>
       swal({
                  title: 'Updated!',
                  text: 'The Academic Year has been Updated!',
                  icon: 'success',
               });
</script>";
    }elseif($value == "error"){
        echo "<script type='text/javascript'>
       swal({
                  title: 'Error!',
                  text: 'The Academic Year has been Updated!',
                  icon: 'error',
               });
</script>";
    }
  }else{

  }


  ?>

</body>
</html>
	<?php 
	  // $conn = new mysqli("localhost","root","","dams");
          //                               if ($conn->connect_error) {
          //                                       die("Connection failed : " . $conn->connect_error);
          //                               }
          //                               $sql = "SELECT * FROM data_start";
          //                               $result = $conn->query($sql);

          //                               echo $result;

          //                               $conn->close();


        
        $conn = new mysqli("localhost", "root", "", "dams");
                                        if ($conn->connect_error) {
                                                die("Connection failed : " . $conn->connect_error);
                                        }

                // END NG CONNECTION
                                                //<- START NG QUERY NG SELECT ALL DATA SA DATABASE TABLE
                                            $sql = "SELECT * FROM announcement ORDER BY `date` ASC LIMIT 8";     //QUERY
                                            $result = $conn->query($sql);      //EXCUTION
                                            $result_array = [];
                                            if(mysqli_num_rows($result) > 0){
                                                        foreach($result as $row){
                                                                array_push($result_array, $row);
                                                        }
                                                        header('Content-type: application/json');
                                                        echo json_encode($result_array);
                                            }
                                            else{
                                                echo "<h4>Nothing Found</h4>";
                                            }



                                ?>
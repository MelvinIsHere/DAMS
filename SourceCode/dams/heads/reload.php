        <?php 
        session_start();
        $users_id = $_SESSION['user_id'];
      include "../php/functions.php";

        
        
$conn = new mysqli("localhost", "root", "", "dams2");
                                        if ($conn->connect_error) {
                                                die("Connection failed : " . $conn->connect_error);
                                        }

                // END NG CONNECTION
                                                //<- START NG QUERY NG SELECT ALL DATA SA DATABASE TABLE
                                            $sql = "   

 SELECT 
                                                            n.content,
                                                            n.date,
                                                            n.is_task,
                                                            un.user_id,
                                                            un.notif_id
                                                        FROM 
                                                            notifications n
                                                        LEFT JOIN 
                                                            user_notifications un ON un.notif_id = n.notif_id
                                                        WHERE 
                                                        is_task != 'no' AND
                                                            un.user_id NOT IN (
                                                                SELECT 
                                                                    un2.user_id
                                                                FROM 
                                                                    notifications n2
                                                                LEFT JOIN 
                                                                    user_notifications un2 ON un2.notif_id = n2.notif_id
                                                                WHERE 
                                                                    un2.user_id = '$users_id';
                                                                    );
                                                                        
                                                            
                                                                );";  


                                                        //QUERY
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
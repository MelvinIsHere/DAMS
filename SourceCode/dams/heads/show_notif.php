        <?php 
        session_start();
        $id = $_SESSION['user_id'];
        


        
     include "../config.php";
                                       

                // END NG CONNECTION
                                                //<- START NG QUERY NG SELECT ALL DATA SA DATABASE TABLE
                                             $sql = "SELECT 
                                                            n.content AS 'content',
                                                            n.date AS 'date',
                                                            n.is_task,
                                                            un.user_id,
                                                                un.notif_id
                                                        FROM 
                                                            notifications n
                                                        LEFT JOIN 
                                                            user_notifications un ON un.notif_id = n.notif_id
                                                        WHERE 
                                                        is_task = 'yes' AND un.user_id = '$id'
                                                        ORDER BY `date` DESC";  

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
                                                echo "a";

;
                                            }



                                ?>
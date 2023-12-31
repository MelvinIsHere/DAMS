
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

             
                   <!-- Sidebar Toggle (Topbar) -->
                    
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                         <div class="input-group">
                            <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <?php 
                            if(isset($_GET['due_date'])){ ?>
                                      <input type="text" name="due_date" style="width:0px;height:0px;display: none;" value="<?php  if(isset($_GET['due_date'])){echo $_GET['due_date']; } ?>">
                           <?php }

                            ?>
                            

                             
                            <div class="input-group-append">
                                <button class="btn " type="submit" style="color:white;background-color:#A52A2A">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                 

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                         <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="count_notif"></span>
                            </a>


                            
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in "
                                aria-labelledby="alertsDropdown" >
                                <h6 class="dropdown-header" style="background-color:#00BFA5">
                                    Notifications
                                </h6>
                                <div class="notif" style="overflow: scroll; max-height: 400px;">
                                    
                                </div>
                               
                                
                            </div>
                        </li>
                        <script type="text/javascript">
                         $(document).ready(function () {
                              $("#alertsDropdown").click(function (event) {
                                event.preventDefault(); // Prevent the default link behavior
                                
                                $.ajax({
                                  type: 'POST',
                                  url: 'upNotif.php',
                                  success: function(response) {
                                    // Handle the success response from the server
                                    console.log(response);
                                    // Optionally, you can perform additional actions or update the UI
                                  },
                                  error: function(xhr, status, error) {
                                    // Handle any errors that occur during the request
                                    console.error(error);
                                  }
                                });
                              });
                            });


                        </script>
                        <script type="text/javascript">
                                $(document).ready(function(){
                                        notif();
                                    });

                                function notif(){
                                        $.ajax({
                                        type: "GET",
                                        url: "../admin/reload.php",
                                        success: function(response){
                                            console.log(response);
                                        $.each(response,function (key,value){
                                            // console.log(value['first_name']);
                                            $('.notif').append(
                                                    // '<p>'+value['title']+ '</p><br>\
                                                    // <p>'+value['content']+'</p><br>\
                                                    // <p>' + value['content'] + '</p><br>'
                                                  '  <a class="dropdown-item d-flex align-items-center" href="#">\
                                                        <div class="mr-3">\
                                                            <div class="icon-circle bg-success">\
                                                                <i class="fas fa-bell text-white"></i>\
                                                            </div>\
                                                        </div>\
                                                        <div>\
                                                            <div class="small text-gray-500">'+value['date']+'</div>\
                                                            '+value['content']+'\
                                                        </div>\
                                                    </a>'
                                            );
                                        });
                                        }


                                    });
                                }
                        </script>
                                <script type="text/javascript">


                                        function loadDoc() {
                                        setInterval(function() {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.onreadystatechange = function() {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    document.getElementById('count_notif').innerHTML = this.responseText;
                                                }
                                            };
                                            xhttp.open("GET", "get_notif.php", true);
                                            xhttp.send();
                                        }, 1000);
                                    }

                                    loadDoc();

                                    </script>
                        
                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $department_name;?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo '../php/images/'.$img?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!--<a class="dropdown-item" href="#">-->
                                <!--    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>-->
                                <!--    Profile-->
                                <!--</a>-->
                                <a class="dropdown-item" href="settings.php?alert=">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <!--<a class="dropdown-item log" href="#log" data-toggle="modal" data-target="#logModal">-->
                                <!--    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>-->
                                <!--    Activity Log-->
                                <!--</a>-->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                   <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="../php/logout.php?logout_id=<?php echo $users_id; ?>"  style="margin-right: 10px;">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



 <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="LogLabel">Activity Log</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>



                <div class="modal-body">
                    <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Activity</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php include "../config.php";


                                         $sql = "SELECT * FROM activity_log";
                                         $result = $conn->query($sql);
                                         while($row = mysqli_fetch_array($result)){
                                          $id = $row['log_id'];
                                          $activity = $row['activity'];
                                          $date = $row['date'];  
                                         


                                        ?>
                                        <tr>
                                            <td><?php echo $id ;?></td>
                                            <td><?php echo $activity ;?></td>
                                            <td><?php echo $date ;?></td>
                                            
                                        </tr>
                                    <?php }?>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>


                </div>



              
            </div>
        </div>
    


     
 
    <!-- Scrollable modal -->


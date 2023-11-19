
        <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#A52A2A">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <!-- <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div> -->
                <div class="sidebar-brand-text mx-3">OVCAA</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link tablinks" href="admin.php">
                    <i class="fas fa-home fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                      
                        <a class="collapse-item tablinks"  href="pendingDocuments.php">Pending Task</a>
                        <a class="collapse-item tablinks"  href="#send_task" data-toggle = "modal">Create Task</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="submission_monitoring.php" >
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Monitoring</span>
                </a>
               
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Departments Manager
            </div>
          


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#management"
                    aria-expanded="true" aria-controls="management">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Manage</span>
                </a>
                <div id="management" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Actions:</h6>
                        <a class="collapse-item" href="manage_departments.php">Manage Departments</a>
                        <a class="collapse-item" href="section_management.php">Manage Sections</a>
                        <a class="collapse-item" href="courses_management.php">Manage Courses</a>
                        <a class="collapse-item" href="faculties_management.php">Manage Faculties</a>
                        <a class="collapse-item" href="faculty_titles_management.php">Manage Facultiy Titles</a>
                        <a class="collapse-item" href="program_management.php?search=">Manage Programs </a>
                       
                    </div>
                </div>
            </li>

              <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Account Manager
            </div>
          


           <li class="nav-item active">
                <a class="nav-link tablinks" href="account_management.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Accounts</span></a>
            </li>
               <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                File Manager
            </div>
          


           <li class="nav-item active">
                <a class="nav-link tablinks" href="file_manager.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Files</span></a>
            </li>




            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>
          


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw  fa-chart-line"></i>
                    <span>Forecasting</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="#">Predict</a>
                       
                    </div>
                </div>
            </li>

          

         
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

           

        </ul>
        <!-- End of Sidebar -->


         <!-- Edit Modal HTML -->
    <div id="send_task" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
                    <div class="modal-header">                      
                        <h4 class="modal-title">Send task</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" action="../php/send_task.php">
                            <div class="form-group">
                                  <label for="lname" class="form-label">Task name</label>
                                    <input class="form-control" list="task_names" name="task_name" id="task_name" placeholder="Enter task name" required>
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
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Insert Description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Term</label>
                                <select class="form-control" name="term">
                                    <?php
                                        include "../php/config";
                                        $query = mysqli_query($conn,"SELECT term FROM terms");
                                        if($query){
                                            while($row = mysqli_fetch_assoc($query)){
                                                
                                                $term = $row['term'];
                                    ?>

                                                <option><?php echo $term;?></option>

                                    <?php
                                      
                                        }
                                    }else{

                                        }
                                     ?>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                 <label class="form-label">Due date</label>
                                 <input type="date" name="due_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select class="form-control" name="category" required>
                                    <option value="Dean">Deans</option>
                                    <option value="Head">Heads</option>
                                    <option value="Admin">OVCAA</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                           
                        

                    </div>                   
                       
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button  class="btn btn-primary btn-sm" type="submit" name="submit">Send</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


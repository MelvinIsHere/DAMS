 <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#A52A2A">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $type; ?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link tablinks"  href="staffs.php">
                    <i class="fas fa-fw fa-home"></i>
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
                <a class="nav-link collapsed "  href="pendingDocuments.php">
                    <i class="fas fa-fw fa-tasks"></i>
                    <span>Pending</span>
                </a>
                        
                        
                        <!-- <a class="collapse-item tablinks"  href="pendingDocuments.php">Pending Task</a> -->
                      
                 
            </li>
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                Task Manager
            </div>
              <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tasktables"
                    aria-expanded="true" aria-controls="tasktables">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Manage Task</span>
                </a>
                <div id="tasktables" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Task tables:</h6>
                      
                        <a class="collapse-item tablinks"  href="ipcr.php">IPCR</a>
                        <a class="collapse-item tablinks"  href="official_time.php" <?php 
                            if($type == 'Staff'){
                                echo "hidden";
                            }
                        ?>>RCOT</a>
                       
                        
                    </div>
                </div>
            </li>
            

            <!-- Heading -->
         

           

           



           

         
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

           

        </ul>
        <!-- End of Sidebar -->

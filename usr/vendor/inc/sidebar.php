<?php
            $aid=$_SESSION['u_id'];
            $ret="select * from tms_user where u_id=?";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('i',$aid);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            while($row=$res->fetch_object())
        {
        ?>
            <ul class="sidebar navbar-nav">
                  <li class="nav-item active">
                    <a class="nav-link" href="user-dashboard.php">
                      <i class="fas fa-fw fa-tachometer-alt"></i>
                      <span>Dashboard</span>
                    </a>
                  </li>

                  <li class="nav-item">
                  <a class="nav-link" href="user-view-profile.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>My Profile</span></a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link" href="usr-book-vehicle.php">
                        <i class="fas fa-fw fa-book"></i>
                        <span>Book Facility</span></a>
                    </li>


                  
                </ul>
<?php }?>
<!--sidebar-menu-->
<?php
  $active = '';
  $temp_lv1 = $this->GlobalVar->GetSideBar($user_id,0,0)->result();
  // var_dump($temp_lv1);
?>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="<?php echo base_url(); ?>" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Dashboards</span> </a>
                </li>
                <?php
                  foreach ($temp_lv1 as $key) {
                    // var_dump($key);
                    $parent_id = $key->id;
                    $temp_lv2 = $this->GlobalVar->GetSideBar($user_id,$parent_id,0)->result();

                    if ($key->multilevel == "0") {
                      if($key->separator == "0"){
                        echo "<li><a href='".base_url().$key->link."' class=' hvr-bounce-to-right'><i class='fa ".$key->ico." nav_icon'></i> <span class = 'nav-label'>".$key->permissionname."</span></a> </li>";
                      }
                      else{
                        echo "<li class ='content'><center><span class ='separator-custom'> ".$key->permissionname."</span></center></li>";  
                      }
                    }
                    else{
                      echo "<li class='submenu'>";
                      echo "<a href='".base_url().$key->link."' class='hvr-bounce-to-right'><i class='fa ".$key->ico." nav_icon'></i> <span class = 'nav-label'>".$key->permissionname."</span></a>";
                      echo "<ul>";
                      foreach ($temp_lv2 as $child) {
                        if($child->separator == "0"){
                          echo "<li><a href='".base_url().$child->link."' class='hvr-bounce-to-right'>".$child->permissionname."</a></li>";
                        }
                        else{
                          echo "<li class ='content'><center><span class ='separator-custom'> ".$child->permissionname."</span></center></li>";
                        }
                      }
                      echo "</ul>";
                      echo "</li>";
                    }
                  }
                ?>
                <li>
                    <a href="<?php echo base_url(); ?>Auth/logout" class=" hvr-bounce-to-right"><i class="fa fa-sign-out nav_icon "></i><span class="nav-label">Logout</span> </a>
                </li>
            </ul>
        </div>
	</div>
</nav>
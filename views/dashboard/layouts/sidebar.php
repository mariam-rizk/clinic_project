
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <img src="dashboard_assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">VCare </span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
            $menuItems = [
            ['Dashboard', 'dashboard', 'fas fa-tachometer-alt'],
            ['Doctors', 'manage_doctors', 'fas fa-file-alt'],
            ['Majors', 'manage_majors', 'fas fa-file-alt'],
            ['Bookings', 'manage_bookings', 'fas fa-tag'],
            ['Users', 'manage_users', 'fas fa-users'],
            ['Contacts', 'manage_contacts', 'far fa-file-alt'],
          ];
          foreach ($menuItems as [$label, $pageKey, $icon]) {
                $active = (isset($_GET['page']) && $_GET['page'] === $pageKey) ? 'active' : '';
                echo "<li class='nav-item'>
                        <a href='dashboard.php?page=$pageKey' class='nav-link $active'>
                          <i class='nav-icon $icon'></i>
                          <p>$label</p>
                        </a>
                        </li>";
          }    
        ?>
      </ul>
    </nav>
  </div>
</aside>
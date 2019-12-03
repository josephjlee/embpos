<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('dashboard'); ?>">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Nav Item -->
  <?php

  $this->db->where('is_ready', 1);
  $this->db->order_by('position', 'ASC');
  $query_menu = $this->db->get('menu');
  $menus = $query_menu->result_array();
  ?>

  <?php foreach ($menus as $menu) : ?>

    <li class="nav-item <?= strtolower($menu['name']) == $this->uri->segment(1) ? 'active' : ''; ?>">

      <a class="nav-link my-0 pb-0" href="#" data-toggle="collapse" data-target="#collapse-<?= $menu['name']; ?>">
        <i class="<?= $menu['icon']; ?>"></i>
        <span><?= $menu['name']; ?></span>
      </a>

      <?php
        $this->db->where('menu_id', $menu['menu_id']);
        $this->db->where('is_visible', 1);
        $this->db->order_by('sidebar_order', 'ASC');
        $query_page = $this->db->get('page');
        $pages = $query_page->result_array();
        ?>

      <div id="collapse-<?= $menu['name']; ?>" class="collapse <?= strtolower($menu['name']) == $this->uri->segment(1) ? 'show' : ''; ?>" data-parent="#accordionSidebar">
        <div class="py-2 collapse-inner">

          <?php foreach ($pages as $page) : ?>
            <a class="collapse-item my-0 <?= strtolower($title) == strtolower($page['name']) ? 'active' : ''; ?>" href="<?= base_url() . $page['url']; ?>"><?= $page['name']; ?></a>
          <?php endforeach; ?>

        </div>
      </div>

    </li>

  <?php endforeach; ?>

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline mt-2">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
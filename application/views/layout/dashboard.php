<?php $this->load->view('template/dashboard-header'); ?>

    <?php $this->load->view('template/dashboard-sidebar'); ?>

    <?php $this->load->view('template/dashboard-topbar'); ?>
    
    <?php echo $content; ?>

<?php $this->load->view('template/dashboard-footer'); ?>
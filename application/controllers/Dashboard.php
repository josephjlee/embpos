<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public function index()
  {

    $data['title'] = 'Dashboard';

    $data['content'] = $this->load->view('dashboard/index.php', $data, TRUE);

    $data['view_script'] = 'dashboard.js';

    $this->load->view('layout/dashboard', $data);
  }
}

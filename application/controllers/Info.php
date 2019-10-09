<?php 
defined('BASEPATH') OR exit('No direct script access allowed.');

class Info extends CI_Controller {

    public function tentang() {

        $data['title'] = 'Tentang Aplikasi';
        
        $data['content'] = $this->load->view('dashboard/info/about', $data, TRUE);

        $this->load->view('layout/dashboard', $data);

    }

    public function index()
    {
        $data['title'] = 'Tentang Aplikasi';
        
        $data['content'] = $this->load->view('dashboard/info/about', $data, TRUE);

        $this->load->view('layout/dashboard', $data);
    }

}
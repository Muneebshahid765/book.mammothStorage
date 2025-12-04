<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Australia/Brisbane'); // Set the timezone to UK
    }

    public function index()
    {
        $this->load->view('storage/templates/header');
        $this->load->view('storage/home/index');
        $this->load->view('storage/templates/footer');
    }
}

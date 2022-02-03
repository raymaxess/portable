<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookmark extends CI_Controller {
  public function __construct()
  {
      parent::__construct();
      $this->load->helper('file');
      $this->load->model('api_model');
      $this->load->model('bookmark_model');
      $this->filepath = "bookmarkFile.csv";
  }

	public function index()
	{
    $id = '';
    $action = '';
    if($this->input->post('id')) $id = $this->input->post('id');
    if($this->input->post('action')) $action = $this->input->post('action');

    if ($action == 'check') {
      $this->bookmark_model->add($id);
    }

    if ($action == 'uncheck') {
      $this->bookmark_model->remove($id);
    }
  }
}

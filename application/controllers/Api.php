<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('theguardian_model');
  }

	public function search()
	{
    $data =  null;
    $q = isset($_GET["q"]) ? trim($_GET["q"]) : '';
    $apiKey = isset($_GET["api-key"]) ? trim($_GET["api-key"]) : '';

    $data['response'] = $this->theguardian_model->processSearchRequest($q, $apiKey);

    $this->load->view('api/result_view', $data);
  }

  public function item()
  {
    $data =  null;
    $id = isset($_GET["id"]) ? trim($_GET["id"]) : '';
    $apiKey = isset($_GET["api-key"]) ? trim($_GET["api-key"]) : '';

    $data['response'] = $this->theguardian_model->processSingleItemRequest($id, $apiKey);

    $this->load->view('api/result_view', $data);
  }
}

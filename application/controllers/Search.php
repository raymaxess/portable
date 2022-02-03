<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      $this->load->model('api_model');
      $this->load->model('bookmark_model');
      $this->filepath = "bookmarkFile.csv";
  }

	public function index()
	{
    $data = null;
		$this->load->view('search/main_view', $data);
	}

  function getArticles()
  {
    $articles = null;
    $q = '';
    if($this->input->post('query')) $q = $this->input->post('query');
    
    // Call own API; as a proxy for the Guardian
    $url = base_url()."api/search/?api-key=".THEGUARDIAN_API_KEY; //default request URL
    if ($q != "") {
      $url .= "&q=".urlencode($q);
    }

    $res = $this->api_model->sendRequest($url);
    $response = json_decode($res, true);
    if (isset($response['response']['results']))
    {
      $articles = $response['response']['results'];
    }

    echo $this->prepareHtmlOutput($articles);
  }

  private function prepareHtmlOutput($articles) 
  {
    $bookmarkedItems = $this->bookmark_model->getBookmarked();

    $output = '';
    $output .= '
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <tr>
            <th>Title</th>
            <th>Publication Date</th>
            <th>Bookmark</th>
          </tr>
    ';
    if(isset($articles[0]))
    {
      foreach($articles as $row)
      {
        $output .= '
                  <tr>
                    <td>'.$row['webTitle'].'. <a href="'.$row['webUrl'].'" target="_blank"> Read more...</td>
                    <td>'.date_format(date_create($row['webPublicationDate']), 'd/m/Y').'</td>
                    <td> <input type="checkbox" id="'.$row['id'].'" name="chkBookmarks" onchange="checkboxfunction(this)" value='.$row['id'].'></td>
                  </tr>
                ';
      }
    }
    else
    { 
      $output .= '<tr>
                    <td colspan="4" style="text-align:center">No Article Found</td>
                  </tr>';
    }

    foreach($bookmarkedItems as $id => $content) 
    {
      $contentArr = json_decode($content, true);
      $output .= '
                <tr>
                  <td>'.$contentArr['webTitle'].'. <a href="'.$contentArr['webUrl'].'" target="_blank"> Read more...</td>
                  <td>'.$contentArr['webPublicationDate'].'</td>
                  <td> <input type="checkbox" id="'.$id.'" name="chkBookmarks" onchange="checkboxfunction(this)" value='.$id.' checked></td>
                </tr>
              ';
    }

    $output .= '</table>';

    return $output;
  }
}

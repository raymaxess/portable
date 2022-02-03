<?php
class theguardian_model extends CI_Model
{
  function processSearchRequest($q, $apiKey)
  {
    $url = "https://content.guardianapis.com/search?api-key=$apiKey"; //default request
    if ($q != "") {
      $url = "https://content.guardianapis.com/search?api-key=$apiKey&q=".urlencode($q);
    }

    return $this->sendRequest($url);
  }

  function processSingleItemRequest($id, $apiKey)
  {
    $url = "http://content.guardianapis.com/$id?api-key=$apiKey";

    return $this->sendRequest($url);
  }

  private function sendRequest($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $res = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    // echo '<pre>' . print_r(json_decode($res, true), TRUE) . '</pre>';

    return $res;
  }
}
?>
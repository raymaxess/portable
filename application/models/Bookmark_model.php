<?php
class bookmark_model extends CI_Model
{
  function add($id) 
  {
    $handle = fopen($this->filepath, "a+");  
    $newItem = array($id, $this->getItemDetails($id));
    fputcsv($handle, $newItem);
    fclose($handle);
  }

  function remove($id)
  {
    $filteredItems = array();
    if (($handle = fopen($this->filepath, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (isset($data[0]) && isset($data[1]))
        {
          $bookmarkedId = $data[0];
          $bookmarkedContent = $data[1];

          if ($bookmarkedId != $id) {
            $filteredItems[] = array($bookmarkedId, $bookmarkedContent);
          }
        }
      }
      fclose($handle);
    }

    fclose(fopen($this->filepath,'w'));
    $handle = fopen($this->filepath, "a+");
    foreach ($filteredItems as $row) 
    {
      fputcsv($handle, $row);
    }
    fclose($handle);
  }

  private function getItemDetails($item) 
  {
    $content = null;
    // Call own API; as a proxy for the Guardian
    $url = base_url()."api/item/?api-key=".THEGUARDIAN_API_KEY."&id=$item";
    $res = $this->api_model->sendRequest($url);
    $response = json_decode($res, true);

    if (isset($response['response']['content']))
    {
      $data = $response['response']['content'];
      $content = array("webTitle" => $data["webTitle"], "webUrl" => $data["webUrl"], "webPublicationDate" => date_format(date_create($data['webPublicationDate']), 'd/m/Y'));
    }

    return json_encode($content);
  }

  function getBookmarked() {
    $bookmarkedItems = array();
    if (($handle = fopen($this->filepath, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (isset($data[0]) && isset($data[1]))
        {
          $bookmarkedId = $data[0];
          $bookmarkedContent = $data[1];
          $bookmarkedItems[$bookmarkedId] = $bookmarkedContent;
        }
      }
      fclose($handle);
    }

    return $bookmarkedItems;
  }

}
?>
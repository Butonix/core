<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// {"offers":[{"response":"latest","download":"http:\/\/downloads.wordpress.org\/release\/wordpress-5.3.2.zip","locale":false,"packages":{"full":"http:\/\/downloads.wordpress.org\/release\/wordpress-5.3.2.zip","no_content":"http:\/\/downloads.wordpress.org\/release\/wordpress-5.3.2-no-content.zip","new_bundled":"http:\/\/downloads.wordpress.org\/release\/wordpress-5.3.2-new-bundled.zip","partial":false,"rollback":false},"current":"5.3.2","version":"5.3.2","php_version":"5.6.20","mysql_version":"5.0","new_bundled":"5.3","partial_version":false}],"translations":[]}


class ChartiUpdater {

    private $requester;

    public function __construct($client_version, $requester) {

      //echo $this->getNonce();

      $this->check_version($client_version, $this->repository_version());

    }


    private function check_version($client_version, $repository_version) {
      
      if($repository_version > $client_version) {
        $response = 'upgrade';
      } else {
        $response = 'latest';
      }

      $params = array(
        "charti_cms" =>
          array(
            array(
              "response" => $response,
              "download" => $this->repository_url(),
              "locale" => "en_US", //
              // "packages" => array(
              //   "full" => $this->repository_url(),
              //   "no_content" => false,
              //   "new_bundled" => false,
              //   "partial" => false,
              //   "rollback" => false
              // ),
              "current" => $repository_version,
              "version" => $repository_version,
              "php_version" => "5.6.20",
              "mysql_version" => "5.0",
              "new_bundled" => $repository_version
            )
          )
      );

      echo json_encode($params);
      
    }

    private function repository_version() {
      $bv = '5.5';
      return $bv;
    }

    private function repository_url() {
      return 'https://github.com/Rareloop/wp-router.git';
    }

    private function getNonce() {
    
      $nonce = hash('sha512', $this->makeRandomString() );
      
      return $nonce;

    }

    public function makeRandomString($bits = 64) {
      
      $bytes = ceil($bits / 2);
      $return = '';
      
      for ($i = 0; $i < $bytes; $i++) {
          $return .= chr(mt_rand(0, 255));
      }

      return $return;

    }

}


if( isset($_GET['version']) && !empty( $_GET['version']) ) {

   // if( !empty( $_GET['version']) && !empty($_GET['req']) ) {

   // }


  $client_version = $_GET['version'];
  $requester = 'charticms';

  $updater = new ChartiUpdater($client_version, $requester);

  //var_dump($updater->makeRandomString());


}

// function verifyNonce($data, $cnonce, $hash) {
//     $id = 'george';
//     $nonce = getNonce($id);  // Fetch the nonce from the last request
//     removeNonce($id, $nonce); //Remove the nonce from being used again!
//     $testHash = hash('sha512',$nonce . $cnonce . $data);
//     return $testHash == $hash;
// }


// function sendData($data) {
//     $nonce = getNonceFromServer();
//     $cnonce = hash('sha512', makeRandomString());
//     $hash = hash('sha512', $nonce . $cnonce . $data);
//     $args = array('data' => $data, 'cnonce' => $cnonce, 'hash' => $hash);
//     sendDataToClient($args);
// }


// function makeRandomString($bits = 256) {
//     $bytes = ceil($bits / 8);
//     $return = '';
//     for ($i = 0; $i < $bytes; $i++) {
//         $return .= chr(mt_rand(0, 255));
//     }
//     return $return;
// }

//echo getNonce();

//var_dump($uri);

// all of our endpoints start with /person
// everything else results in a 404 Not Found
// if ($uri[1] !== 'person') {
//     header("HTTP/1.1 404 Not Found");
//     exit();
// }


// the user id is, of course, optional and must be a number:
// $userId = null;
// if (isset($uri[2])) {
//     $userId = (int) $uri[2];
// }

// // authenticate the request with Okta:
// if (! authenticate()) {
//     header("HTTP/1.1 401 Unauthorized");
//     exit('Unauthorized');
// }
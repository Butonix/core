<?php 

class FrontEndRoutes {

  public $watchdir = ROUTESPATH . 'web/';

  public function __construct() {
    $this->get_route_files();
  }

  // Grab all files found by Watcher and initialize
  private function get_route_files() {

    foreach ($this->Watcher() as $file) {
        // ignore the file if the "deprecated-" is set as prefix
        // ignore .DS_Store file made by macOS
        if (strpos($file, "deprecated-") !== 0 && $file !== '.DS_Store') {
          include ROUTESPATH . 'web/' . $file; // include the route file
        }
    }

  }

  /**
      The Watcher will always look into /routes/web/* directory for custom routes
      for ordering your routes you can prefix all files with numbers.
      For example: "1-account.php"
  */
  private function Watcher() {

    $dir = $this->watchdir;

    $result = array();

    $cdir = scandir( $dir );

    foreach ($cdir as $key => $value) {
      if (!in_array($value,array(".",".."))) {

         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         } else {
            $result[] = $value;
         }
      }
    }
    
     return $result;

  }
}

new FrontEndRoutes;
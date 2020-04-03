<?php
/**
  * Charti CMS
  *
  * @since 0.1
  * @author Cristian George

  *
      The little Core Protector <3 

      Will prevent direct accessing on php files.

      Instead of simple exit(),
      We are going to send a 404 header to the browser
      Whenver someone is trying to access a golden file.
      In this way you are totally safe and no ones will
      Ever know you are using the lovely Charti CMS.

        - Gods be with you,
          ... and stay hydrated!

  *
 **/

if ( ! defined( 'ABSPATH' ) ) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    exit("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\r\n<html><head>\r\n<title>404 Not Found</title>\r\n</head><body>\r\n<h1>Not Found</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " was not found on this server.</p>\r\n</body></html>");
}
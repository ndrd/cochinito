<?php
 
function con_start() {
     
    $host = "52.10.224.33";
    $user = "root";
    $pass = "tren_mame";
    $db = "mxhacks";
    
    $mysqli = new mysqli($host, $user, $pass, $db);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connection failed: %s\n", mysqli_connect_error());
        exit();
    }
    /* change character set to utf8 */
    $mysqli->set_charset("utf8_spanish_ci");
    return $mysqli;
} 
?>
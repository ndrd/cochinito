  <?php include('./includes/conn.php'); 
    echo "test 1";
    $mysqli = con_start();
    $ret = [];
    $count = 0;

    $id = 1;
    $smtp = $mysqli->prepare("SELECT u.id_user, u.first_name FROM User u WHERE u.id_user = ?");
    
    $smtp->bind_param("i", $id);
    $smtp->execute();
    $smtp->store_result();   
    $smtp->bind_result($id, $first_name);
    while ($smtp->fetch()) {
         $ret[$count][0] =  $id;
         $ret[$count][1] =  $first_name;
         $count++;
    }
    echo "test 3";
    $smtp->free_result();
    $smtp->close();
    var_dump($ret);
  ?>  

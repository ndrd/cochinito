<?php
/**
 * Created by PhpStorm.
 * User: enriqueohernandez
 * Date: 10/4/15
 * Time: 4:59 AM
 */

$titulo = "Deudas";
include ("head.php");

include('./includes/conn.php');


$mysqli = con_start();

$smtp = $mysqli->prepare("SELECT id_deudor, id_user, name, description, amount, completed, hidden FROM Deudores WHERE id_user = 1");

$smtp->execute();


$smtp->store_result();

$smtp->bind_result($id_deudor, $id_user, $name, $description, $amount, $completed, $hidden);

while ($smtp->fetch()) {
    $ret[$count][0] =  $id_deudor;
    $ret[$count][1] =  $id_user;
    $ret[$count][2] =  $name;
    $ret[$count][3] =  $description;
    $ret[$count][4] =  $amount;
    $ret[$count][5] =  $completed;
    $ret[$count][6] =  $hidden;

    $count++;
}
//echo "test 3";

$smtp->free_result();
$smtp->close();



?>

<div class="container">

    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>


    <h1>Nueva Deuda</h1>

    <form action="nuevaDeuda.php">

        <button name="tipo" value="nueva" type="submit" class="btn btn-primary btn-lg btn-block">Nueva Deuda</button>

    </form>


    <h1>Deudas</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Cantidad</th>
            <th>Estatus</th>
        </tr>
        </thead>
        <tbody>

         <?php

         echo "deudasController.php?id_deudor='.$id_deudor.'&id_usuario='.$id_user.'&cantidad='.$amount.'&nombre='.$nombre.'&descripcion='.$description.'";

         foreach($ret as $personas){
             if($personas[6] == "0") {

                 echo '<td>' . $personas[2] . '</td>';
                 echo '<td>' . $personas[3] . '</td>';
                 echo '<td>' . $personas[4] . '</td>';
                 echo '<td><form action="deudasController.php?id_deudor='.$id_deudor.'&id_usuario='.$id_user.'&cantidad='.$amount.'&nombre='.$nombre.'&descripcion='.$description.'"> <button name="tipo" value="pagado" type="submit" class="btn btn-primary">Pagado</button>   <button name="tipo" value="hide" type="submit" class="btn btn-primary">Eliminar X</button></form></td>';
                 echo '</tr>';
             }
         }

         ?>


        </tbody>
    </table>


</div>


<?php
include ("foot.php")
?>


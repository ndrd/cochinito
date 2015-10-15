    <?php include('./includes/conn.php');

    $titulo = "Egress";
    include ("head.php");

    setlocale(LC_MONETARY, 'en_US');
    $mysqli = con_start();
    $countM = 0;
    $countI = 0;

    $id = 1;
    $smtp = $mysqli->prepare("SELECT t.amount, t.monthly,
      EXTRACT(MONTH FROM t.created), t.created, t.id_trans, t.description
      FROM Transaction t
      WHERE t.id_user = 1 AND t.is_active = 1 AND t.type = 2");

    $smtp->bind_param("i", $id);
    $smtp->execute();
    $smtp->store_result();
    $smtp->bind_result($amount, $monthly, $month, $date, $idt, $desc);

    while ($smtp->fetch()) {
      if($monthly == 1){
         $retM[$countM][0] =  $amount;
         $retM[$countM][1] =  $idt;
         $retM[$countM][2] =  $desc;
         $countM++;
      } elseif($monthly == 0){
         $retI[$month][] =  array($amount, $idt);
      }
    }

    $smtp->free_result();
    $smtp->close();

    function getMonth ($i){
        switch ($i) {
            case 1:
                return "Enero";
            case 2:
                return "Febrero";
            case 3:
                return "Marzo";
            case 4:
                return "Abril";
            case 5:
                return "Mayo";
            case 6:
                return "Junio";
            case 7:
                return "Julio";
            case 8:
                return "Agosto";
            case 9:
                return "Septiembre";
            case 10:
                return "Octubre";
            case 11:
                return "Noviembre";
            case 12:
                return "Diciembre";
        }
    }
  ?>


<div class="container">
    <div class="row">
      <div class="col-lg-12" style="padding: 80px 0 0;">
         <div class="mdl-card__title mdl-card--expand">

          <!-- title -->
          <h2 class="mdl-card__title-text">Perdida de cada mes</h2>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mdl-card mdl-shadow--2dp">

          <div class="mdl-card__supporting-text monthly_ingress">

            <!-- body -->
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Cantidad</th>
                  <th>Descripcion</th>
                  <th>Acción</th>
                </thead>
                <tbody id="tbodyIngresos">
                  
                <?php
                  for($i = 0; $i < count($retM); $i++){

                    echo "<tr>";
                    echo "<td style='background-color: #E7BAB6;' >- " . money_format('%(#5n',$retM[$i][0]) . "</td>";
                    echo "<td>".$retM[$i][2]."</td>";
                    echo "<td><button class='remove' data-id='".$retM[$i][1]."' value='remove' style='color:green;'>Quitar perdida</button></td>";
                    echo "</tr>";
                  }
                ?>
                </tbody>
              </table>  
            </div>
          </div>
          <div class="mdl-card__actions mdl-card--border">

            <!-- button -->
            <input id="ingresoFijo" class="monthlyInput"  type="text" name="amount" placeholder="100" style="color:black;">
              <input id="desc" class="monthlyInput"  type="text" name="desc" placeholder="Compra de materiales" style="color:black;">
            <button style="color:red;" class="insertMonthly mdl-button mdl-js-button mdl-js-ripple-effect"
              data-idu='1'>Registra deuda mensual</button>

        </div>
      </div>

      <div class="col-md-6">

      <?php
        for($i = 0; $i < 13; $i++){
          if(count($retI[$i]) > 0){
            $row = "<div class='table-responsive'>";
            $row .=  "<table class='table'>";
            $row .=    "<thead>";
            $row .=      "<th colspan='3'>" . getMonth($i). "</th>";
            $row .=    "</thead>";
            $row .=    "<tbody>";

                #body
                for($j = 0; $j < count($retI[$i]); $j++){
            $row .=    "<tr>";
            $row .=      "<td style='background-color: #E7BAB6;'>- ". money_format('%(#5n',$retI[$i][$j][0]). "</td>";
            $row .=      "<td> Descripcion</td>";
            $row .=      "<td><button class='remove' data-id='".$retI[$i][$j][1]."' value='remove' style='color:green;'>Quitar adeudo</button></td>";
            $row .=    "</tr>";
                      
                }
            $row .=     "</tbody>";
            $row .=   "</table>";
            $row .=   "<div class='mdl-card__actions mdl-card--border'>";
            $row .=     "<input id='ingresoFijo' class='monthlyInput'  type='text' name='amount' placeholder='100' style='color:black;'>";
            $row .=     "<button style='color:red;' class='insertMonthly mdl-button mdl-js-button mdl-js-ripple-effect' data-idu='1'>Pagaste dinero en ".getMonth($i) . "</button>";
            $row .=   "</div>";
              #button     
            echo $row;
          }
        }
      ?>



      </div>
    </div>
  
</div>

    <script>
    $(document).ready(function() {
        $('#egress').addClass("active");
      });
      $('.insertMonthly').on('click', function (e) {

        e.preventDefault();
        var idu = $(this).attr('data-idu');
        var row = $('#tbodyIngresos');
        var amount = $('#ingresoFijo').val();
          var desc = $('#desc').val();
        var button = $(this);
        $.ajax({
          type: 'post',
          url: './createMonthlyDebt.php',
          data: {idu:idu, amount: amount, desc: desc},
          success: function (json) {
            
            if ($.trim(json)!=0) {
              
              var newRow = "<tr>";
                  newRow += "<td style='background-color: #E7BAB6;'>- " + amount + ".00</td>";
                  newRow += "<td>"+desc+"</td>";
                  newRow += "<td><button class='remove' data-id='"+$.trim(json)+"' value='remove' style='color:red;'>Quitar ganancia</button>";
                  newRow += "</tr>";
              $(row).append(newRow);
              
             
            }

          }
      });

    });

      $('.remove').on('click', function (e) {
        console.log("clicked", this);
        e.preventDefault();
        var id = $(this).attr('data-id');
        var button = $(this);

        $.ajax({
          type: 'post',
          url: './removeTransaction.php',
          data: {id:id},
          success: function (json) {
              $(button).parent().parent().hide("slow");
              

          },
          error: function (json) {
            console.log(json);

          }
      });

    });
</script>

<?php  include("foot.php") ?>


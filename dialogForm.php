<form>
  <?php
    include_once('login.php');
    $mysqli = new mysqli($host,$username,$password,$database);
    $query = "SELECT doctor_id, name from Doctor"; 
    if($result = $mysqli->query($query)){
      echo '<select id = "doctor">';
      while($row= $result->fetch_assoc()){
        echo '<option value='.$row["doctor_id"].'>'.$row["name"].'</option>';
      }
      echo '</select>';
      $result->free();
    }
    $mysqli->close(); 
  ?>
  <input id="dateInput" type ="hidden" ></input>
  <input id="previousDoctor" type="hidden"></input>
</form>
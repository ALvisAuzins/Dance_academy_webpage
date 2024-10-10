<?php
 ob_start();
 session_start();

 if( !isset($_SESSION['user']) ){
  header("Location: Home.php");
 }
 include_once 'Dbconnect.php';

  $error = false;
 // funkcija kļūdu ziņojuma izvadīšanai
  function phpAlert($msg) {
      echo '<script type="text/javascript">alert("' . $msg . '")</script>';
  }

 if ( isset($_POST['btn-register']) ) {
  
   
  // kļudas pārbaude, ja lauks atstāts neizvēlēts
  if ($_POST['eventname'] === 'Izvēlies') {
   $error = true;
   $eventnameError = "<span style='color: red'>Lūdzu izvēlaties deja stilu, kuru vēlaties apmeklēt.</span>";
  } 

  if ($_POST['tickets'] === 'Izvēlies') {
   $error = true;
   $ticketsError = "<span style='color: red'>Lūdzu izvēlaties savu vecuma grupu.</span>";
  } 
  
  // Ja nav kļūdu, turpina reģistrāciju
  if( !$error ) {
   // Pēc lietotāja id lauka atrod visus datus
    $userres=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
    $userRow=mysql_fetch_array($userres);
    $userid = $userRow['userId'];
    $eventname = $_POST['eventname'];
    $tickets = $_POST['tickets'];

   // pieslēgšanās datubāzei
    $dbc = mysqli_connect('localhost', 'root', '', 'dbusers') or die('Error connecting to MySQL server');
   // atlasa datus ar lietotāja izvēlētajiem datiem
    $check=mysqli_query($dbc,"SELECT * FROM ticket WHERE EventName = '$eventname' AND userId=".$_SESSION['user']);
    $checkrows=mysqli_num_rows($check);
   // Ja atrod vismaz 1 rindu izvadīs paziņojumu
    if($checkrows>0){
      echo phpAlert(   "Jūs esat jau pierakstīts ar šiem izvēlētajiem datiem"   );
    } else {
   // Ja neatrod, tad datus saglabās sistēmas datubāzē
   $query = "INSERT INTO ticket(userId,EventName,biletesskaits) VALUES('$userid','$eventname','$tickets')";
   $res = mysql_query($query);
   mysqli_close($dbc);
    }
   // izvadītie paziņojumi uz ekrāna
   if ($res) {
    $errTyp = "success";
    $errMSG = phpAlert(  "Pieteikšanās ir izdevusies"   ) ;                              
    unset($eventname);
    unset($tickets);   
   } else {
    $errTyp = "danger";
    $errMSG = phpAlert(   "Notikusi kļūda, mēģiniet vēlreiz..."   ); 
   } 
    
  }
  
  
 }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pieteikšanās uz pasākumiem</title>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>
<body>

<section id="boxcontent">

 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
  
             <h1>Pierakstīšanās uz pasākumiem šeit.</h1>
                    
             <hr />
                      
<?php
   if ( isset($errMSG) ) {
    
?>
    
             <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
        
<?php
   }
?>
        <!--izvēlne starp deju stiliem-->              
        <section id="text_columns">
          Pasākuma nosaukums:   <select  name="eventname" class="form-control">
                <option value="Izvēlies">Izvēlies</option>
                <option value="Jam with everyone">Jam with everyone</option>
                <option value="Masterclasses with Waydi">Masterclasses with Waydi</option>
                
            </select>
                <span class="text-danger"><?php echo $eventnameError; ?></span>
        </section>
                    
            <hr />
    <!--izvēlne starp vecuma grupām-->
        <section id="text_columns">
          Biļešu skaits:  <select  name="tickets" class="form-control">
                <option value="Izvēlies">Izvēlies</option>
                <option value="1">1 biļete</option>
                <option value="2">2 biļetes</option>
                <option value="3">3 biļetes</option>
                <option value="4">4 biļetes</option>
                <option value="5">5 biļetes</option>
            </select>
               <span class="text-danger"><?php echo $ticketsError; ?></span> 
        </section>   
                         
             <hr />
    <!--Poga, lai pierakstītos ar izvēlētajiem datiem-->
        <button type="submit" class="btn btn-block btn-primary" name="btn-register">Pierakstīties</button>

             <hr />
    <!--atgriešanās sākuma lapā-->
         <a class="button" href="Grafiks.php">Lai atgrieztos iepriekšējā lapā spiediet šeit...</a>    

    </form>
    

</section>

</body>
</html>
<?php ob_end_flush(); ?>
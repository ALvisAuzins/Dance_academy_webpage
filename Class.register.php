<?php
 ob_start();
 session_start();

 if( !isset($_SESSION['user']) ){
  header("Location: Home.php");
 }
 include_once 'dbconnect.php';

  $error = false;
 // funkcija kļūdu ziņojuma izvadīšanai
  function phpAlert($msg) {
      echo '<script type="text/javascript">alert("' . $msg . '")</script>';
  }

 if ( isset($_POST['btn-register']) ) {
  
   
  // kļudas pārbaude, ja lauks atstāts neizvēlēts
  if ($_POST['classname'] === 'Izvēlies') {
   $error = true;
   $classnameError = "<span style='color: red'>Lūdzu izvēlaties deja stilu, kuru vēlaties apmeklēt.</span>";
  } 

  if ($_POST['classagegroup'] === 'Izvēlies') {
   $error = true;
   $classagegroupError = "<span style='color: red'>Lūdzu izvēlaties savu vecuma grupu.</span>";
  } 
  
  // Ja nav kļūdu, turpina reģistrāciju
  if( !$error ) {
   // Pēc lietotāja id lauka atrod visus datus
    $userres=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
    $userRow=mysql_fetch_array($userres);
    $userid = $userRow['userId'];
    $classname = $_POST['classname'];
    $classagegroup = $_POST['classagegroup'];

	 // pieslēgšanās datubāzei
    $dbc = mysqli_connect('localhost', 'root', '', 'dbusers') or die('Error connecting to MySQL server');
	 // atlasa datus ar lietotāja izvēlētajiem datiem
    $check=mysqli_query($dbc,"SELECT * FROM classes WHERE className = '$classname' AND classAgeCategory = '$classagegroup' AND userId=".$_SESSION['user']);
    $checkrows=mysqli_num_rows($check);
	 // Ja atrod vismaz 1 rindu izvadīs paziņojumu
    if($checkrows>0){
      echo "Jūs esat jau pierakstīts ar šiem izvēlētajiem datiem";
    } else {
	 // Ja neatrod, tad datus saglabās sistēmas datubāzē
   $query = "INSERT INTO classes(userId,className,classAgeCategory) VALUES('$userid','$classname','$classagegroup')";
   $res = mysql_query($query);
   mysqli_close($dbc);
    }
	 // izvadītie paziņojumi uz ekrāna
   if ($res) {
    $errTyp = "success";
    $errMSG = phpAlert(  "Pieteikšanās ir izdevusies"   ) ;                              
    unset($classname);
    unset($classagegroup);   
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
<title>Pieteikšanās uz nodarbībām</title>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>
<body>

<section id="boxcontent">

 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
  
             <h1>Pierakstīšanās uz nodarbībām šeit.</h1>
                    
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
          Dejas stils:   <select  name="classname" class="form-control">
                <option value="Izvēlies">Izvēlies</option>
                <option value="Hip-Hop">Hip-Hop</option>
                <option value="Popping">Popping</option>
                <option value="Breaking">Breaking</option>
            </select>
                <span class="text-danger"><?php echo $classnameError; ?></span>
        </section>
                    
            <hr />
		<!--izvēlne starp vecuma grupām-->
        <section id="text_columns">
          Vecuma grupa: <select  name="classagegroup" class="form-control">
                <option value="Izvēlies">Izvēlies</option>
                <option value="zem 18">zem 18</option>
                <option value="virs 18">virs 18</option>
            </select>
               <span class="text-danger"><?php echo $classagegroupError; ?></span> 
        </section>   
         	               
             <hr />
		<!--Poga, lai pierakstītos ar izvēlētajiem datiem-->
        <button type="submit" class="btn btn-block btn-primary" name="btn-register">Pierakstīties</button>

             <hr />
		<!--atgriešanās sākuma lapā-->
         <a class="button" href="Home.php">Lai atgrieztos sākuma lapā spiediet šeit...</a>    

    </form>
    

</section>

</body>
</html>
<?php ob_end_flush(); ?>
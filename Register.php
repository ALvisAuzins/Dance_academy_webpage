<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: Home.php");
 }
 include_once 'dbconnect.php';

 $error = false;

 if ( isset($_POST['btn-signup']) ) {
  
  // lietotāja nejaušu simbolu ievadīšanu gadījuma pārbaude
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);
  
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  
  $lastname = trim($_POST['lastname']);
  $lastname = strip_tags($lastname);
  $lastname = htmlspecialchars($lastname);
  
  $age = trim($_POST['age']);
  $age = strip_tags($age);
  $age = htmlspecialchars($age);
  
  $phone = trim($_POST['phone']);
  $phone  = strip_tags($phone );
  $phone  = htmlspecialchars($phone );
  
  // vārda pārbaude
  if (empty($name)) {
   $error = true;
   $nameError = "<span style='color: red'>Lūdzu ievadiet savu vārdu.</span>";
  } else if (strlen($name) < 3) {
   $error = true;
   $nameError = "<span style='color: red'>Vārdam jābūt vismaz ar 3 simboliem.</span>";
  } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
   $error = true;
   $nameError = "<span style='color: red'>Vārdam jāsatur alfabēta simboli.</span>";
  }
  
  // uzvārda pārbaude
  if (empty($lastname)) {
   $error = true;
   $lastnameError = "<span style='color: red'>Lūdzu ievadiet savu uzvārdu.</span>";
  } else if (strlen($lastname) < 3) {
   $error = true;
   $lastnameError = "<span style='color: red'>Uzvārdam jābūt vismaz ar 3 simboliem.</span>";
  } else if (!preg_match("/^[a-zA-Z ]+$/",$lastname)) {
   $error = true;
   $lastnameError = "<span style='color: red'>Uzvārdam jāsatur alfabēta simboli.</span>";
  }
  
  //e-pasta adreses pārbaude
  if(empty($email)){
   $error = true;
   $emailError = "<span style='color: red'>Lūdzu ievadiet Jūsu e-pasta adresi.</span>";
  } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "<span style='color: red'>Lūdzu ievadiet derīgu e-pasta adresi.</span>";
  } else {
   // pārbauda vai e-pasts eksistē
   $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
   $result = mysql_query($query);
   $count = mysql_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "<span style='color: red'>E-pasts jau tiek izmantots.</span>";
   }
  }
  
  // vecuma pārbaude 
  if (empty($age)) {
   $error = true;
   $ageError = "<span style='color: red'>Lūdzu ievadiet savu vecumu.</span>";
  } else if (is_int($age)) {
   $error = false;
   $ageError = "<span style='color: red'>Lūdzu ievadiet savu vecumu ar ciparu simboliem.</span>";
  } 
  
  // telefona nummura pārbaude
  if (empty($phone)){
   $error = true;
   $phoneError = "<span style='color: red'>Lūdzu ievadiet savu telefona nummuru.</span>";
  } else if(strlen($phone) < 8) {
   $error = true;
   $passError = "<span style='color: red'>Kļūda tālruņa nummurā.</span>";
  }
  
  // paroles pārbaude
  
  if (empty($pass)){
   $error = true;
   $passError = "<span style='color: red'>Lūdzu ievadiet paroli.</span>";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "<span style='color: red'>Parolei jāsastāv vismaz no 6 simboliem.</span>";
  }
  
  // paroles šifrēšana izmantojot SHA256();
  $password = hash('sha256', $pass);
  
  // Ja nav kļūdu, turpina reģistrāciju
  if( !$error ) {
   
   $query = "INSERT INTO users(userName,userLastname,userEmail,userPass,userAge,userPhone) VALUES('$name','$lastname','$email','$password','$age','$phone')";
   $res = mysql_query($query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "Reģistrācija ir izdevusies, varat pieslēgties";
    unset($name);
    unset($email);
    unset($pass);
	 unset($lastname);
	unset($age);
	unset($phone);
   } else {
    $errTyp = "danger";
    $errMSG = "Notikusi kļuda, mēģiniet vēlreiz..."; 
   } 
    
  }
  
  
 }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrācija</title>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>
<body>

<section id="boxcontent">

 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
  
             <h1>Reģistrējies šeit.</h1>
                    
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
        <!-- Datu ievadīšana  -->                
        <section id="text_columns">
            <input type="text" name="name" class="form-control" placeholder="Ievadiet Vārdu" maxlength="50" value="<?php echo $name ?>" />               			
                <span class="text-danger"><?php echo $nameError; ?></span>
        </section>  
         
		<section id="text_columns">		 
            <input type="text" name="lastname" class="form-control" placeholder="Ievadiet Uzvārdu" maxlength="50" value="<?php echo $lastname ?>" />              
                <span class="text-danger"><?php echo $lastnameError; ?></span>
        </section> 
		
        <section id="text_columns">	    
            <input type="email" name="email" class="form-control" placeholder="Ievadiet e-pastu" maxlength="40" value="<?php echo $email ?>" />               
                <span class="text-danger"><?php echo $emailError; ?></span>
         </section>   
         
		<section id="text_columns">
            <input type="password" name="pass" class="form-control" placeholder="Ievadiet Paroli" maxlength="15" />                
                <span class="text-danger"><?php echo $passError; ?></span>
        </section> 			
            
		<section id="text_columns">	
            <input type="number" name="age" class="form-control" placeholder="Ievadiet Vecumu" maxlength="3" value="<?php echo $age ?>" />
                <span class="text-danger"><?php echo $ageError; ?></span>
		</section>
             
		<section id="text_columns">	 
            <input type="text" name="phone" class="form-control" placeholder="Ievadiet Tālruni" maxlength="8" value="<?php echo $phone ?>" />
                 <span class="text-danger"><?php echo $phoneError; ?></span>
         </section>
         
             <hr />

        <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Reģistrēt</button>

             <hr />
		<!-- Poga, lai pārietu pie pieslēgšanās --> 
        <a href="index.php">Lai pieslēgtos spiediet šeit...</a> 
 
   
    </form>
    

</section>

</body>
</html>
<?php ob_end_flush(); ?>
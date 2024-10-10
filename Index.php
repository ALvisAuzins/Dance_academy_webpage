<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // tas nekad neļaus atvērt index(login) lapu, ja sesija ir iestatīta
 if ( isset($_SESSION['user'])!="" ) {
  header("Location: home.php");
  exit;
 }
 
 $error = false;
 
 if( isset($_POST['btn-login']) ) { 
  
  // lietotāja nejaušu ievadīšanu gadījuma pārbaude
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  // lietotāja nejaušu ievadīšanu gadījuma pārbaude
  
  if(empty($email)){
   $error = true;
   $emailError = "<span style='color: red'>Lūdzu ievadiet Jūsu e-pasta adresi.</span>";
  } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "<span style='color: red'>Lūdzu ievadiet derīgu e-pasta adresi.</span>";
  }
  
  if(empty($pass)){
   $error = true;
   $passError = "<span style='color: red'>Lūdzu ievadiet paroli.</span>";
  }
  
  // Ja nav kļūdas, turpina pieslēgšanos
  if (!$error) {
   
   $password = hash('sha256', $pass); // jaucot paroles izmanto SHA256
  
   $res=mysql_query("SELECT userId, userName, userPass FROM users WHERE userEmail='$email'");
   $row=mysql_fetch_array($res);
   $count = mysql_num_rows($res); // Ja parole un e-pasts ir pareizi, tam jāatrod 1 rinda
   
   if( $count == 1 && $row['userPass']==$password ) {
    $_SESSION['user'] = $row['userId'];
    header("Location: home.php");
   } else {
    $errMSG = "<span style='color: red'>Nepareizi dati, mēģiniet vēlreiz...</span>";
   }
    
  }
  
 }
?>
<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pieslēgšanās</title>
	<link rel="SHORTCUT ICON" href="bildes/favicon.ico"/>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	
</head>
<body>

	<section id="boxcontent">
	
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
		<h1>Pieslēgšanās deju akadēmijas "nosaukums" mājaslapā.</h1>
				
				<hr/>
                        
<?php
   if ( isset($errMSG) ) {
?>
    
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
               
<?php
   }
?>           
            <section id="text_columns">
				<input type="email" name="email"  placeholder="Jūsu e-pasts" value="<?php echo $email; ?>" maxlength="40" />               
					<span class="text-danger"><?php echo $emailError; ?></span>
            </section>
          
            <section id="text_columns">
				<input type="password" name="pass"  placeholder="Jūsu parole" maxlength="15" />
                    <span class="text-danger"><?php echo $passError; ?></span>
            </section>
            
				<hr />
       
            <button type="submit" class="btn btn-block btn-primary" name="btn-login">Pieslēgties</button>
         
				<hr />
       
            <a href="register.php">Lai reģistrētos spiediet šeit...</a>
           
        
        </div>
   
    </form>
    
</section>

</body>
</html>
<?php ob_end_flush(); ?>
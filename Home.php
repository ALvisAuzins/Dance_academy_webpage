<?php
 include("template_class.php");
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // ja sesija nav iestatīta šis novirzīs uz sākuma lapu 
 if( !isset($_SESSION['user']) ) {
  header("Location: Index.php");
  exit;
 }
 // atlasa lietotāja detaļas, kurš ir pašlaik pieslēdzies
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysql_fetch_array($res);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sveiks - <?php echo $userRow['userName']; ?></title>
	<link rel="SHORTCUT ICON" href="bildes/favicon.ico"/>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
    <link href="lightbox/css/lightbox.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans|Baumans' rel='stylesheet' type='text/css'>

    <script src="js/vendor/modernizr.min.js"></script>
    <script src="js/vendor/respond.min.js"></script>


    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="js\/vendor\/1.7.2.jquery.min.js"><\/script>')</script>

    <script src="lightbox/js/lightbox.js"></script>
    <script src="js/vendor/prefixfree.min.js"></script>
    <script src="js/vendor/jquery.slides.min.js"></script>
    <script src="js/script.js"></script>
	<!-- slide funkcija -->  
	<script>                                           
        $(function() {
            $('#slides').slidesjs({
                height: 235,
                navigation: false,
                pagination: false,
                effect: {
                    fade: {
                        speed: 400
                    }
                },
                callback: {
                    start: function(number)
                    {
                        $("#slider_content1,#slider_content2,#slider_content3").fadeOut(500);
                    },
                    complete: function(number)
                    {
                        $("#slider_content" + number).delay(500).fadeIn(1000);
                    }
                },
                play: {
                    active: false,
                    auto: true,
                    interval: 6000,
                    pauseOnHover: false,
                    effect: "fade"
                }
            });
        });
    </script>
	
</head>
<body>

<?php
   $template = new template_Class();
   $template->showHeader();
?>

<?php
$template = new template_Class();
$template->showMenu();
?>
<!-- Raksti lapas sākumā -->  
<section id="boxcontent">
    <h2 class="hidden">Sākums</h2>
    <article>
        <img src="images/Lock.png" alt="Some alt text"/>
        <h3>Individuālās deju nodarbības</h3>
        <p>
            Tiks pievienots raksts.
        </p>
            <hr />
        <p>
            Ja esi ieinteresēts apmeklēt kādu no mūsu piedāvātajiem deju stiliem uz individuālajām nodarbībām, tad pieteikšanās šeit.
        </p>   
            <hr />     
        <a class="button" href="Class.register.php">Pieteikšanās uz nodarbībām</a>     <!-- Poga ar pieteikšanos uz nodarbībām -->              
    </article>
    <article>
        <img src="images/phone.png" alt="Some alt text"/>
        <h3>Virsraksts 2</h3>
        <p>
            Tiks pievienots raksts.
        </p>
    </article>
    <article>
        <img src="images/gear.png" alt="Some alt text"/>
        <h3>Virsraksts 3</h3>
        <p>
            Tiks pievienots raksts.
        </p>
    </article>
    <br class="clear"/>
</section>

<?php
 $template = new template_Class();
 $template->showFooter();
?>
   
</body>
</html>
<?php ob_end_flush(); ?>
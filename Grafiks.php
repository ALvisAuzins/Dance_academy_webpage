<?php
 
 session_start();
 require_once 'dbconnect.php';
 
 // ja sesija nav iestatīta šis novirzīs uz sākuma lapu
 if( !isset($_SESSION['user']) ) {
  header("Location: Index.php");
  exit;
 }
?>


<?php
include("template_class.php");
include_once('functions.php');
?>


<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafiks</title>
    <link rel="SHORTCUT ICON" href="bildes/favicon.ico"/>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
    <link href="lightbox/css/lightbox.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans|Baumans' rel='stylesheet' type='text/css'>
	
	<link type="text/css" rel="stylesheet" href="css/stylecalendar.css"/>
	<script src="jquery/jquery.min.js"></script>
	
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
<!-- kalendāra pievienošana --> 
 <div id="calendar_div">
	<?php echo getCalender(); ?>
</div>
<!-- pasākuma izvadīšana --> 
<section id="text_columns">


    <h2 class="hidden">Apakšraksts</h2>
    <section class="column2">
        <h3 class="hidden">Raķete</h3>
        <article class="row">
            <h4 class="hidden">apakšraksts</h4>
            <img src="images/rocket.png" width="80" class="rocket" alt="Some alt text"/>
            <p>Notikumi šajā mēnesī:<br><br>
<!-- pasākuma ievietošana no datubāzes --> 
             <?php

    $now = new \DateTime('now');
    $month = $now->format('m');        
         $ticketevents=mysql_query("SELECT * FROM ticketevents WHERE MONTH(EventDate) = '$month'");
   
    WHILE ($ticketrow=mysql_fetch_array($ticketevents)):
         $eventname=$ticketrow['Event Name'];
         $eventdate=$ticketrow['EventDate'];
             echo "*$eventname<br>$eventdate<br><br>";
    endwhile;
       
?>
            </p>
<!-- pieteikšanās izvēle --> 
        </article>
        <article class="row">
            <h4 class="hidden">Pulkstenis</h4>
            <img src="images/clock.png" width="80" class="clock" alt="Some alt text"/>
            <p>
                 <a class="button" href="Monthevent.register.php">Pieteikties uz notikumiem</a>
            </p>
        </article>
    </section>
</section>
<?php
$template = new template_Class();
$template->showFooter();
?>
</body>
</html>

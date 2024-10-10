<?php					
/*
 * Funkcija ar Ajax
 */
if(isset($_POST['func']) && !empty($_POST['func'])){
	switch($_POST['func']){
		case 'getCalender':
			getCalender($_POST['year'],$_POST['month']);
			break;
		case 'getEvents':
			getEvents($_POST['date']);
			break;
		//Priekš notikuma ievietošanas
		case 'addEvent':
			addEvent($_POST['date'],$_POST['title']);
			break;
		default:
			break;
	}
}

/*
 * Saņemt kalendāru HTML
 */
function getCalender($year = '',$month = '')
{
	$dateYear = ($year != '')?$year:date("Y");
	$dateMonth = ($month != '')?$month:date("m");
	$date = $dateYear.'-'.$dateMonth.'-01';
	$currentMonthFirstDay = date("N",strtotime($date));
	$totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN,$dateMonth,$dateYear);
	$totalDaysOfMonthDisplay = ($currentMonthFirstDay == 7)?($totalDaysOfMonth):($totalDaysOfMonth + $currentMonthFirstDay);
	$boxDisplay = ($totalDaysOfMonthDisplay <= 35)?35:42;
?>
	<div id="calender_section">
		<h2>
        	<a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>');">&lt;&lt;</a>
            <select name="month_dropdown" class="month_dropdown dropdown"><?php echo getAllMonths($dateMonth); ?></select>
			<select name="year_dropdown" class="year_dropdown dropdown"><?php echo getYearList($dateYear); ?></select>
            <a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>');">&gt;&gt;</a>
        </h2>
		<div id="event_list" class="none"></div>
        <!--Notikumu pievienošanai-->
        <div id="event_add" class="none">
        	<p>Pievienot notikumu <span id="eventDateView"></span></p>
            <p><b>Notikuma teksts: </b><input type="text" id="eventTitle" value=""/></p>
            <input type="hidden" id="eventDate" value=""/>
            <input type="button" id="addEventBtn" value="Pievienot"/>
        </div>
		<div id="calender_section_top">
			<ul>
				<li>Svētdiena</li>
				<li>Pirmdiena</li>
				<li>Otrdiena</li>
				<li>Trešdiena</li>
				<li>Ceturtdiena</li>
				<li>Piektdiena</li>
				<li>Sestdiena</li>
			</ul>
		</div>
		<div id="calender_section_bot">
			<ul>
			<?php 
				$dayCount = 1; 
				for($cb=1;$cb<=$boxDisplay;$cb++){
					if(($cb >= $currentMonthFirstDay+1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay)){
						//Tekošais datums
						$currentDate = $dateYear.'-'.$dateMonth.'-'.$dayCount;
						$eventNum = 0;
						//Norāda db konfigurācijas failu
						include 'dbConfig.php';
						//Iegūstam jaunumu skaitu
						$result = $db->query("SELECT title FROM events WHERE date = '".$currentDate."' AND status = 1");
						$eventNum = $result->num_rows;
						//Definē datuma šūnu krāsu
						if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
							echo '<li date="'.$currentDate.'" class="grey date_cell">';
						}elseif($eventNum > 0){
							echo '<li date="'.$currentDate.'" class="light_sky date_cell">';
						}else{
							echo '<li date="'.$currentDate.'" class="date_cell">';
						}
						//Datuma šūna
						echo '<span>';
						echo $dayCount;
						echo '</span>';
						
						//notikuma uznirstošā izvēlne  
						echo '<div id="date_popup_'.$currentDate.'" class="date_popup_wrap none">';
						echo '<div class="date_window">';
						echo '<div class="popup_event">Jaunumi ('.$eventNum.')</div>';
						echo ($eventNum > 0)?'<a href="javascript:;" onclick="getEvents(\''.$currentDate.'\');">kas jauns?</a><br/>':'';
					
					
					
					session_start();
					include_once 'dbconnect.php';
					$dbc = mysqli_connect('localhost', 'root', '', 'dbusers') or die('Error connecting to MySQL server');
   					$check=mysqli_query($dbc,"SELECT * FROM user_role WHERE role_id= '1' AND userId=".$_SESSION['user']);
    				$checkrows=mysqli_num_rows($check);

					if($checkrows>0){
      				echo '<a href="javascript:;" onclick="addEvent(\''.$currentDate.'\');">pievienot</a>';
								echo '</div></div>';												
								echo '</li>';	
   						
   						 } 
					mysqli_close($dbc);																																																																																																																														
						$dayCount++;
			?>
			<?php }else{ ?>
				<li><span>&nbsp;</span></li>
			<?php } } ?>
			</ul>
		</div>
	</div>

	<script type="text/javascript">
		function getCalendar(target_div,year,month){
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'func=getCalender&year='+year+'&month='+month,
				success:function(html){
					$('#'+target_div).html(html);
				}
			});
		}
		
		function getEvents(date){
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'func=getEvents&date='+date,
				success:function(html){
					$('#event_list').html(html);
					$('#event_add').slideUp('slow');
					$('#event_list').slideDown('slow');
				}
			});
		}
		//Priekš notikuma pievienošanas
		function addEvent(date){
			$('#eventDate').val(date);
			$('#eventDateView').html(date);
			$('#event_list').slideUp('slow');
			$('#event_add').slideDown('slow');
		}
		//Priekš notikuma pievienošanas
		$(document).ready(function(){
			$('#addEventBtn').on('click',function(){
				var date = $('#eventDate').val();
				var title = $('#eventTitle').val();
				$.ajax({
					type:'POST',
					url:'functions.php',
					data:'func=addEvent&date='+date+'&title='+title,
					success:function(msg){
						if(msg == 'ok'){
							var dateSplit = date.split("-");
							$('#eventTitle').val('');
							alert('Notikums veiksmīgi izveidots.');
							getCalendar('calendar_div',dateSplit[0],dateSplit[1]);
						}else{
							alert('Notikusi kļūda, mēģiniet vēlreiz.');
						}
					}
				});
			});
		});
		
		$(document).ready(function(){
			$('.date_cell').mouseenter(function(){
				date = $(this).attr('date');
				$(".date_popup_wrap").fadeOut();
				$("#date_popup_"+date).fadeIn();	
			});
			$('.date_cell').mouseleave(function(){
				$(".date_popup_wrap").fadeOut();		
			});
			$('.month_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			$('.year_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			$(document).click(function(){
				$('#event_list').slideUp('slow');
			});
		});
	</script>
<?php
}

/*
 * opciju saraksts mēnešiem
 */
function getAllMonths($selected = ''){
	$options = '';
	for($i=1;$i<=12;$i++)
	{
		$value = ($i < 01)?'0'.$i:$i;
		$selectedOpt = ($value == $selected)?'selected':'';
		$options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("m", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
	}
	return $options;
}

/*
 * opciju saraksts gadam
 */
function getYearList($selected = ''){
	$options = '';
	for($i=2015;$i<=2025;$i++)
	{
		$selectedOpt = ($i == $selected)?'selected':'';
		$options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	return $options;
}

/*
 * Saņemt notikumus pēc datuma
 */
function getEvents($date = ''){
	//Norāda db konfigurācijas failu
	include 'dbConfig.php';
	$eventListHTML = '';
	$date = $date?$date:date("Y-m-d");
	//Saņemt notikumus tekošajā datumā
	$result = $db->query("SELECT title FROM events WHERE date = '".$date."' AND status = 1");
	if($result->num_rows > 0){
		$eventListHTML = '<h2>Notikumi '.date("d M Y",strtotime($date)).'</h2>';
		$eventListHTML .= '<ul>';
		while($row = $result->fetch_assoc()){ 
            $eventListHTML .= '<li>'.$row['title'].'</li>';
        }
		$eventListHTML .= '</ul>';
	}
	echo $eventListHTML;
}

/*
 * datumam pievienot notikumu
 */
function addEvent($date,$title){
	//Norāda db konfigurācijas failu
	include 'dbConfig.php';
	$currentDate = date("Y-m-d H:i:s");
	//Saglabā pievienoto notikumu db
	$insert = $db->query("INSERT INTO events (title,date,created,modified) VALUES ('".$title."','".$date."','".$currentDate."','".$currentDate."')");
	if($insert){
		echo 'ok';
	}else{
		echo 'err';
	}
}
?>
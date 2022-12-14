<?php
include "connect_db.php";    					// З'єднання з файлом connect_db.php

$page=1;                                        // 1 сторінка
if (isset($_GET['page']))                       // Створення змінної Сторінка з URL сторінки
	$page = $_GET['page'];
else $page = 1;

if(isset($_GET['count'])) 
	$count = $_GET['count'];
else $count = 20;                               // Кількість записів для виводу таблиці та графіку

$art = ($page * $count) - $count;				// Визначення початкового значення для діапазону

$result = $conn->query("SELECT id_bme280 FROM bme280");  	// Запит для визначення кількості записів таблиці bme280
$all_rec = $result->num_rows;                   // Кількість записів таблиці bme280 
if($all_rec % $count == 0)						// Визначення кількості сторінок навігатора
	$num_of_pages = $all_rec/$count;
else 
	$num_of_pages = $all_rec/$count+1;

// Отримання значень ТЕМПЕРАТУРИ, ТИСКУ, ВИСОТИ, ВОЛОГОСТІ та ДАТИ для графіків
	$ch_temp=$conn->query("SELECT temp_bme280 FROM bme280 ORDER BY id_bme280 LIMIT $art, $count");
	$ch_press=$conn->query("SELECT press_bme280 FROM bme280 ORDER BY id_bme280 LIMIT $art, $count");
	$ch_alt=$conn->query("SELECT alt_bme280 FROM bme280 ORDER BY id_bme280 LIMIT $art, $count");
	$ch_hum=$conn->query("SELECT hum_bme280 FROM bme280 ORDER BY id_bme280 LIMIT $art, $count");             
	$ch_date=$conn->query("SELECT date_bme280 FROM bme280 ORDER BY date_bme280 LIMIT $art, $count");
?>

<!doctype html>  <!-- html код -->
<html>
	<head>
		<script src="js/Chart.min.js"></script>	<!-- Підключення додаткових бібліотек -->
		<script src="js/utils.js"></script>
		<script src="js/jquery.js"></script>
		<meta charset="utf-8">					<!-- Підключення кирилиці -->
			<style>								<!-- Стиль графіків -->
			canvas{ 
				-moz-user-select: none;
				-webkit-user-select: none;
				-ms-user-select: none;
			}
			.chart-container {
				width: 800px;
				margin-left: 40px;
				margin-right: 40px;
			}
			.container {
				display: flex;
				flex-direction: row;
				flex-wrap: wrap;
				justify-content: center;
			}
			</style>
	</head>

	<body bgcolor="#dfffde">				<!-- Колір фону сторінки -->
		<script>							<!-- Скрипт для динамічного оновлення інформації у таблиці "Дані датчика BME280" -->
		$(document).ready(function(){
			loadData();
		});
		var loadData = function() {
			$.ajax({						<!-- ajax-запит до бази даних -->
				type:"GET",
				url:"/extract.php",			<!-- звертання до файла extract.php -->
				dataType: "json",
				success: function(result){
					$("#temp").text(result.temp_bme280 + ' °С');
					$("#press").text(result.press_bme280 + ' гПа');
					$("#alt").text(result.alt_bme280 + ' м');
					$("#hum").text(result.hum_bme280 + ' %');
					setTimeout(loadData, 2000); 
				}
			});
		};	
		</script>

<!-- Створення таблиці "Дані датчика BME280" -->
		<caption><h1 style="font-family : Arial;" align=center>Дані датчика BME280</h1></caption>
		<table bordercolor="black" border ="1" style="font-size : 24px; font-family : Arial; background:Khaki" cellspacing="0" cellpadding="10px" align=center width=45%>
		  <tr style="background:#a5f2c1;">
			<th>Температура</th>
			<th>Тиск</th>
			<th>Висота над рівнем моря</th>
			<th>Вологість</th>
			<tr><td align=center id="temp"></td>
			<td align=center id="press"></td>
			<td align=center id="alt"></td>
			<td align=center id="hum"></td></tr>	
		</table>
		<caption><h2 style="font-family : Arial;" align=center>База даних</h2></caption>

		<?php 	// Функція виводу навігатора кількості значень БД
		function Count_navigator($count, $all_rec){
			echo "<div style='font-family : Arial;' align='center'> Кількість значень: ";         
			for ($i = 1; $i <= 4; $i++){
				if($i == 1)
					if($count==20)
						echo "<a style='font-size : 24px;' href=index.php?count=20>| 20 |</a>";
					else echo "<a href=index.php?count=20>| 20 |</a>";
				if($i == 2)
					if($count==50)
						echo "<a style='font-size : 24px;' href=index.php?count=50>| 50 |</a>";
					else echo "<a href=index.php?count=50>| 50 |</a>";
				if($i == 3)
					if($count==100)
						echo "<a style='font-size : 24px;' href=index.php?count=100>| 100 |</a>";
					else echo "<a href=index.php?count=100>| 100 |</a>";
				if($i == 4)
					if($count==$all_rec)
						echo "<a style='font-size : 24px;' href=index.php?count=".$all_rec.">| ".$all_rec." |</a>";
					else echo "<a href=index.php?count=".$all_rec.">| ".$all_rec." |</a>";
			}
		}
// Функція виводу навігатора сторінок БД
		function Page_navigator($count, $page, $num_of_pages){
			echo "<div> Сторінка ";   
			for ($i = 1; $i <= $num_of_pages; $i++){
				if($page==$i)
					echo "<a style='font-size : 24px;' href=index.php?page=".$i."&count=".$count.">| ".$i." |</a>";
				else 
					echo "<a href=index.php?page=".$i."&count=".$count.">| ".$i." |</a>";
			}
		}
// Вивід навігатора сторінок БД
		Count_navigator($count, $all_rec);
		Page_navigator($count, $page, $num_of_pages);
		?>
		
<!-- Створення таблиці "База даних"-->	
		<table bordercolor="black" border="1" style="font-family : Arial;" cellspacing="0" align=center width=600>
		<colgroup>
			<col span="1" style="background:#84b591">                   <!-- Фон першого стовбця таблиці-->
			<col span="6" style="background-color:#aee8bd">             <!-- Фон для інших стовбців таблиці-->
		</colgroup>
		  <tr align=center style="background:blue; color:white">		<!-- Фон першого рядка таблиці-->
			<th>ID</th>
			<th>Дата</th>
			<th>Час</th>
			<th>Температура</th>
			<th>Тиск</th>
			<th>Висота</th>
			<th>Вологість</th>
		   </tr>

		<?php 
// Запит до БД для виводу усіх значень з таблиці "bme280"
		$result=$conn->query("SELECT * FROM bme280 ORDER BY id_bme280 LIMIT $art,$count");           
		while($myrow=$result->fetch_array(MYSQLI_ASSOC)){						// Вивід усіх значень таблиці
			$php_date = date("d/m/Y", strtotime($myrow['date_bme280']));     	// Перевод дати у формат "d/m/Y"
			$php_time = date("H:i:s", strtotime($myrow['date_bme280']));     	// Перевод часу у формат "H:i:s"
			echo"<tr align=center><td>";
			echo $myrow['id_bme280'];
			echo"</td><td>";
			echo $php_date;
			echo"</td><td>";
			echo $php_time;
			echo"</td><td>";
			echo $myrow['temp_bme280']." &degС";
			echo"</td><td>";
			echo $myrow['press_bme280']." гПа";
			echo"</td><td>";
			echo $myrow['alt_bme280']." м";
			echo"</td><td>";
			echo $myrow['hum_bme280']." %";
			echo"</td></tr>";
			}
		?>
		</table>

		<?php // Вивід навігатора сторінок БД
		Page_navigator($count, $page, $num_of_pages);
		Count_navigator($count, $all_rec);
		?>

		<caption><h2>Графіки</h2></caption> 
		<div class="container"> 	<!-- Ініціалізація графіків -->
			<div class="chart-container"> 
				<canvas id="chart-temp"></canvas>
			</div>
			<div class="chart-container">
				<canvas id="chart-press"></canvas>
			</div>
			<div class="chart-container">
				<canvas id="chart-alt"></canvas>
			</div>
			<div class="chart-container">
				<canvas id="chart-hum"></canvas>
			</div>
		</div>
		<script>
		var color = Chart.helpers.color;
		function createConfig(data, text, colorName) {
			return {
				type: 'line',
				data: {
					labels: [<?php while ($o = mysqli_fetch_array($ch_date)) { echo '"' . $o['date_bme280'] . '",';}?>],
					datasets: [{
						label: text,
						data: data,
						backgroundColor: color(window.chartColors[colorName]).alpha(0.9).rgbString(),
						borderColor: window.chartColors[colorName],
						borderWidth: 1
					}]
				},
				options: {
					responsive: true
				}
			};
		}

		window.onload = function() {
			[{
				id: 'chart-temp',	// Графік температури
				color: 'yellow',
				text: 'Температура',
				data: [<?php while($t=mysqli_fetch_array($ch_temp)){echo '"'.$t['temp_bme280'].'",';}?>]
			}, {
				id: 'chart-press',	// Графік тиску
				color: 'red',
				text: 'Тиск',
				data: [<?php while($p=mysqli_fetch_array($ch_press)){echo '"'.$p['press_bme280'].'",';}?>]
			}, {
				id: 'chart-alt', 	// Графік Висоти
				color: 'green',
				text: 'Висота над рівнем моря',
				data: [<?php while($l=mysqli_fetch_array($ch_alt)){echo '"'.$l['alt_bme280'].'",';}?>]
			}, {
				id: 'chart-hum', 	// Графік вологості
				color: 'blue',
				text: 'Вологість',
				data: [<?php while($h=mysqli_fetch_array($ch_hum)){echo '"'.$h['hum_bme280'].'",';}?>]
			}].forEach(function(details) {
				var ctx = document.getElementById(details.id).getContext('2d');
				var config = createConfig(details.data, details.text, details.color);
				new Chart(ctx, config);
			});
		};
		</script>	
	</body>
</html>

<!--<meta http-equiv="Refresh" content="20; URL=\"> -->                 				<!-- Оновлення сторінки раз у 20 секунд  -->
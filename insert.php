<?php
 include "connect_db.php";    					// З'єднання з файлом connect_db.php
 
 $temp = $_GET['temp'];                         // Створення змінної Температура з URL сторінки
 $press = $_GET['press'];                       // Створення змінної Тиск з URL сторінки
 $alt = $_GET['alt'];                           // Створення змінної Висота з URL сторінки
 $hum = $_GET['hum'];                           // Створення змінної Вологість з URL сторінки
 $counter = $_GET['counter'];                   // Створення змінної Лічильник з URL сторінки								
 $maxcount = $_GET['maxcount'];					// Створення змінної Максимального лічильника з URL сторінки для запису у БД bme280

echo "<script>console.log('$temp, $press, $alt, $hum, $counter, $maxcount' );</script>";
// Створення запиту до БД
$query = $conn->query("SELECT * FROM bme280_current");

if(mysqli_num_rows($query) != 0){
	$sql = "UPDATE bme280_current SET temp_bme280 = $temp, press_bme280 = $press, alt_bme280 = $alt, hum_bme280 = $hum";
	$result = mysqli_query($conn, $sql);		// Оновлення даних у таблиці bme280_current
}
else{
	$sql = "INSERT INTO bme280_current (temp_bme280, press_bme280, alt_bme280, hum_bme280) VALUES ($temp, $press, $alt, $hum)";
	$result = mysqli_query($conn, $sql);		// Внесення даних до таблиці
}
	
if($counter==$maxcount){						// Внесення даних до таблиці bme280
	$sql = "INSERT INTO bme280 (temp_bme280, press_bme280, alt_bme280, hum_bme280) VALUES ($temp, $press, $alt, $hum)";
	$result = mysqli_query($conn, $sql);
}
?>

<!-- Перехід на головну сторінку--> 
<meta http-equiv="Refresh" content="0; URL=\index.php">   
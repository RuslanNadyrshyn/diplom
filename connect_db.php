<?php
$conn = new mysqli("192.168.0.103", "diplom", "diplom", "diplom");  	// Підключення до БД
if (! $conn)
die("Помилка: не вдається підключитися: " . $conn->connect_error);      // Повідомлення при неможливості підключення до бази
?>
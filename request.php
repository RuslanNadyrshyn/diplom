<?php ?>

<script>
    const APPID = "<?php echo $APPID; ?>";
    const WEATHER_SOURCE = "http://openweathermap.org/img/wn/";
    const PNG_ENDING = "@2x.png";

/*
    Дані отримуються за допомогою ajax-запиту до файла get_weather.php, який
виконує запит необхідними з даними до API.openweathermap.org
Після отримання даних, за допомогою jQuery заповнюються відповідні елементи HTML-коду. 

(тут картинка как єто віглядит)
*/
    var getWeather = function (city) {      // Функція, яка виконує запит до API для отримання
        $.ajax({							// даних погоди обраного міста
            type: "GET",                                                
            url: "/get_weather.php?city="+city,                          
            dataType: "json",
            success: function (result) {    // Заповнення елементів таблиці даних погоди обраного міста
                var $img = $("<img class='weather-content-icon' src=''/>");
                $img.attr('src', WEATHER_SOURCE + result.weather[0].icon + PNG_ENDING);
                $("#weather").append($img);
                $("#weather").append(result.weather[0].description);
                $("#temp-weather").text(result.main.temp + " °С");
                $("#temp-feels-weather").text(result.main.feels_like + " °С");
                $("#press-weather").text(result.main.pressure + " ГПа");
                $("#hum-weather").text(result.main.humidity + " %");
                $("#cloud-weather").text(result.clouds.all + " %");
                $("#wind-weather").text(result.wind.speed + " м/с");
                $("#location-weather").html(result.main.temp + " °С");
                $("#weather-icon").attr('src', WEATHER_SOURCE + result.weather[0].icon + PNG_ENDING);        
            }
        });
    };

    var getCurrentData = function () {                  // Функція, яка виконує ajax-запит до бази даних  
        $.ajax({										// за допомогою файла "get_current.php" для динамічного
            type: "GET",                                // виводу даних в таблицю "Дані датчика BME280".
            url: "database/get_current.php",            
            dataType: "json",
            success: function (result) {                // Заповнення отриманими даними відповідних елементів таблиці 
                $("#temp").text(result.temp_bme280 + ' °С');
                $("#press").text(result.press_bme280 + ' гПа');
                $("#alt").text(result.alt_bme280 + ' м');
                $("#hum").text(result.hum_bme280 + ' %');
                setTimeout(loadData, 2000);             // Рекурсійний виклик функції для оновлення інформації кожні 2 секунди
            }
        });
    };

    var getNumOfPages = function (count) {              // Функція, яка за допомогою ajax-запиту до файла 
        var numOfPages = 0;                             // "get_num_of_pages.php" отримує та повертає кількість 
        $.ajax({                                        // сторінок для обраної кількості рядків
            async: false,		
            type: "GET",                                            
            url: "database/get_num_of_pages.php?count=" + count,	
            dataType: "json",
            success: function (result) {
                numOfPages = Number(result);
            }
        });
        return numOfPages;
    };

    /*
    Функція, яка за допомогою ajax-запиту до файла "database/fetch_db.php" отримує дані таблиці
    в обраних користувачем межах та за відповідними умовами, після чого викликає функції створення з отриманими даними
    таблиці бази даних та графіків
    */
    function fetchDB(page, count, param, order) {       // Функція, яка за допомогою ajax-запиту до файла
        $.ajax({                                        // "database/fetch_db.php" отримує дані таблиці
            type: "GET",                                // в обраних межах та за відповідними умовами, після чого викликає функції створення таблиці бази даних та графіків
            url: "database/fetch_db.php?" + "page=" + page + "&count=" + count + "&param=" + param + "&order=" + order,
            dataType: "json",
            success: function (result) {
                printDB(result);                    
                printCharts(result);
            }
        });
    };
</script>
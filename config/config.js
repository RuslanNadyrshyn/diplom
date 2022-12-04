const WEATHER_SOURCE = "http://openweathermap.org/img/wn/";
const PNG_ENDING = "@2x.png";
// const HOST="http://weather.vps.webdock.cloud";
const HOST="";

const COUNTER_LIST = [20, 50, 100, 200, 500, 1000];

const OPTIONS = [
    { value: "id", text: "ID" },
    { value: "date", text: "Час" },
    { value: "temp", text: "Температура" },
    { value: "press", text: "Тиск" },
    { value: "alt", text: "Висота" },
    { value: "hum", text: "Вологість" }
];

const ORDERS = [
    { value: "DESC", text: "По спаданню" },
    { value: "ASC", text: "По зростанню" }
]

const DEFAULT_PAGE = 1;
const DEFAULT_PARAM = "id";
const DEFAULT_ORDER = "DESC";
const DEFAULT_CITY = "Київ";



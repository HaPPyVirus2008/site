<?php
// Инициализация сессии
session_start();

// Подключение к базе данных MySQL
$servername = "185.27.134.10";
$username = "epiz_33883972";
$password = "bNzVPo5SQ7CBe";
$dbname = "epiz_33883972_saw";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Создание таблицы, если она не существует
$sql = "CREATE TABLE IF NOT EXISTS questions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    option4 VARCHAR(255) NOT NULL,
    correct_option INT(1) NOT NULL
)";

$conn->query($sql);

// Получение данных из POST запроса
if (isset($_POST['question'])) {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correctOption = $_POST['correctOption'];

    // Вставка данных в базу данных
    $sql = "INSERT INTO questions (question_text, option1, option2, option3, option4, correct_option) 
            VALUES ('$question', '$option1', '$option2', '$option3', '$option4', $correctOption)";
    
    $conn->query($sql);
}

// Проверка и обновление роли игрока
if (isset($_GET['change_role'])) {
    $sql = "UPDATE players SET role = 'player'";
    $conn->query($sql);
    echo "success";
}

// Запрос для получения вопроса от Пилы
$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $questionData = [
        'question' => $row['question_text'],
        'option1' => $row['option1'],
        'option2' => $row['option2'],
        'option3' => $row['option3'],
        'option4' => $row['option4'],
        'correctOption' => $row['correct_option']
    ];
    echo json_encode($questionData);
} else {
    echo json_encode(['question' => '']);
}

$conn->close();
?>

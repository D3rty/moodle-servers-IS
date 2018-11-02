<?php

session_start();

if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}

const ROOT_URL = 'http://localhost/ismoodle';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ИС "Сервера Moodle"</title>

    <link rel="icon" type="image/png" href="img/favicon/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-128.png" sizes="128x128" />
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="https://twemoji.maxcdn.com/2/twemoji.min.js?11.0"></script>
</head>

<body>
    <script> 
        const ROOT_URL = 'http://localhost/ismoodle'; 
    </script>
    <div class="container-fluid">
        <nav id="sidebar">
            <div class="sidebar-header">
                <span>ИС «Серверы Moodle»</span>
            </div>
            <ul class="list-unstyled sidebar-menu">
            <span class="sidebar-category-name" style="margin-top:10px;">Управление</span>
                <li>
                    <a href="index.php" id="manage-servers">
                        <span class="sidebar-icons">⚙️</span>
                        Серверы
                    </a>
                </li>
                <li>
                    <a href="manage-admins.php" id="manage-admins">
                        <span class="sidebar-icons">👨‍💻</span>
                        Администраторы
                    </a>
                </li>
                <span class="sidebar-category-name">Подсистема «Импорт»</span>
                <li>
                    <a href="#collapse-import" data-toggle="collapse" aria-expanded="false">
                        <span class="sidebar-icons">📥</span>
                        Импорт
                    </a>
                    <ul class="list-unstyled collapse" id="collapse-import">
                        <li>
                            <a href="#">Преподавателей</a>
                        </li>
                        <li>
                            <a href="#">Обучающихся</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#collapse-linking" data-toggle="collapse" aria-expanded="false">
                        <span class="sidebar-icons">🔗</span>
                        Привязки
                    </a>
                    <ul class="list-unstyled collapse" id="collapse-linking">
                        <li>
                            <a href="#" id="">Дисциплин</a>
                        </li>
                        <li>
                            <a href="#" id="">Преподавателей</a>
                        </li>
                        <li>
                            <a href="#" id="">Обучающихся</a>
                        </li>
                        <li>
                            <a href="elements-linking.php" id="elements-linking">Элементов оценивания</a>
                        </li>
                    </ul>
                </li>
                <span class="sidebar-category-name">Подсистема «Отчёты»</span>
                <li>
                    <a href="#collapse-reports" data-toggle="collapse" aria-expanded="false">
                        <span class="sidebar-icons">📊</span>
                        Отчеты
                    </a>
                    <ul class="list-unstyled collapse" id="collapse-reports">
                        <li>
                            <a href="report-by-institutes.php" id="report-by-institutes">По институтам</a>
                        </li>
                        <li>
                            <a href="report-by-disciplines.php" id="report-by-disciplines">По дисциплинам</a>
                        </li>
                        <li>
                            <a href="report-by-teachers.php" id="report-by-teachers">По преподавателям</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#collapse-export" data-toggle="collapse" aria-expanded="false">
                        <span class="sidebar-icons">📤</span>
                        Экспорт
                    </a>
                    <ul class="list-unstyled collapse" id="collapse-export">
                        <li>
                            <a href="create-export-url.php" id="create-export-url">Создать ссылку</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar">
                <div class="navbar-header">
                    <a id="sidebarCollapse" class="hvr-icon-wobble-horizontal">
                        <img id="sidebarCollapseImg" src="img/chevrons-left.svg" width="25px" height="25px" class="hvr-icon">
                    </a>
                    <a id="currentDirection" class="direction-hidden"></a>
                    
                </div>
                <a class="btn btn-light my-2 my-sm-0" id="logout-button" href="logout.php">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
            </nav>

            <main class="main-content">
<?php
/**
 * Главный файл галереи изображений.
 * 
 * PHP version 8+
 * 
 * @category Gallery
 * @package  ImageGallery
 * @author   Daniil Belov <student@usm.md>
 * @license  MIT License
 * @link     http://localhost/
 */

/**
 * Получает список изображений из указанной директории с фильтрацией системных файлов и расширений.
 *
 * @param string $directory Путь к директории с изображениями (по умолчанию 'image/').
 * @return array Массив путей к файлам изображений, готовых для вывода.
 * @throws Exception Если директория не существует или к ней нет доступа.
 */
function getGalleryImages(string $directory = 'image/'): array {
    if (!is_dir($directory)) {
        return [];
    }

    $scanResult = scandir($directory);
    if ($scanResult === false) {
        return [];
    }

    $allowedExtensions = ['jpg', 'jpeg'];
    $images = [];

    foreach ($scanResult as $file) {
        // Пропускаем системные указатели каталогов
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = $directory . $file;
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Проверяем, является ли элемент файлом с допустимым расширением
        if (is_file($filePath) && in_array($fileExtension, $allowedExtensions, true)) {
            $images[] = $filePath;
        }
    }

    return $images;
}

// Получаем массив изображений с помощью функции
$imagesList = getGalleryImages('image/');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея изображений</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #34495e;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            flex: 1;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .gallery-item p {
            padding: 10px;
            font-size: 14px;
            text-align: center;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }
        .no-images {
            text-align: center;
            color: #777;
            padding: 40px;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- Хедер сайта -->
    <header>
        <h1>Мой сайт-галерея</h1>
    </header>

    <!-- Меню навигации -->
    <nav>
        <a href="#">Главная</a>
        <a href="#">Галерея</a>
        <a href="#">О проекте</a>
        <a href="#">Контакты</a>
    </nav>

    <!-- Основной контент -->
    <main>
        <h2>Фотогалерея</h2>
        
        <?php if (!empty($imagesList)): ?>
            <div class="gallery">
                <?php foreach ($imagesList as $path): ?>
                    <div class="gallery-item">
                        <img src="<?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?>" alt="Изображение из галереи">
                        <p><?= htmlspecialchars(basename($path), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-images">В директории "image/" пока нет изображений формата .jpg.</p>
        <?php endif; ?>
    </main>

    <!-- Футер сайта -->
    <footer>
        <p>&copy; <?= date('Y') ?> Галерея изображений. Все права защищены.</p>
    </footer>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <!-- Header -->
    <div>
        <a href="index.php?page=start">Homepage</a> |
        <a href="">Kontakt</a> |
    </div>

    <?php
    $headline = "Herzlich Willkommen";
    echo '<h1>' . $_GET['page'] . '</h1>';
    ?>

</body>
</html>

<?php
session_start(); 
require 'db.php';


$typeCuisine = isset($_POST['TypeCuisine']) ? $_POST['TypeCuisine'] : '';
$categoriePlat = isset($_POST['categoriePlat']) ? $_POST['categoriePlat'] : '';


$sql = "SELECT * FROM plat WHERE 1";
$params = [];


if (!empty($typeCuisine)) {
    $sql .= " AND TypeCuisine = :typeCuisine";
    $params[':typeCuisine'] = $typeCuisine;
}

if (!empty($categoriePlat)) {
    $sql .= " AND categoriePlat = :categoriePlat";
    $params[':categoriePlat'] = $categoriePlat;
}

$sql .= " ORDER BY TypeCuisine"; // Sorting by TypeCuisine

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize dishes by TypeCuisine
$platsParTypeCuisine = [];
foreach ($plats as $plat) {
    $platsParTypeCuisine[$plat['TypeCuisine']][] = $plat;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Menu du Restaurant</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="webFace">
        <form method="POST" action="">
            <label for="TypeCuisine">Choose a type of cuisine:</label>
            <select name="TypeCuisine" id="TypeCuisine">
                <option value="">All</option>
                <option value="marocaine" <?= ($typeCuisine == "marocaine") ? "selected" : "" ?>>Marocaine</option>
                <option value="chinoise" <?= ($typeCuisine == "chinoise") ? "selected" : "" ?>>Chinoise</option>
                <option value="Espagnole" <?= ($typeCuisine == "Espagnole") ? "selected" : "" ?>>Espagnole</option>
                <option value="Francaise" <?= ($typeCuisine == "Francaise") ? "selected" : "" ?>>Francaise</option>
            </select>

            <label for="categoriePlat">Choose a category:</label>
            <select name="categoriePlat" id="categoriePlat">
                <option value="">All</option>
                <option value="plat principal" <?= ($categoriePlat == "plat principal") ? "selected" : "" ?>>Main meal
                </option>
                <option value="entrée" <?= ($categoriePlat == "entrée") ? "selected" : "" ?>>Appetizer</option>
                <option value="dessert" <?= ($categoriePlat == "dessert") ? "selected" : "" ?>>Dessert</option>
            </select>

            <button type="submit" name="searchBtn"> <a href="cart1.php" class="cartIcon"> <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAAXNSR0IArs4c6QAAAnhJREFUWEftlztoVUEQhr8EtUgRBCMIapAYiSg2ioIighYSJCAEFCSVFr6xs/ARiCSQYKGgKGhhUklQFCwsEkgwhSAKaaKo4AMLH2iniKJiPD/MkfXm3nPO7jkxt8h09975Z787szuzW0OVWU2V8TALlFaR0gx1OYJvwCjwKC1Ikb+XAk2WCd4P7Cty0aRYWYCk3wMM/g+opJKtA9oM4j6weSaA3DUXAW+BWvtyDfB4uqHSjv0NYJdBXAKOzjTQFmDMID4D5wsG+goMAJ/iuGkZkp/KtLpgEDec2soGH6BDwOVpBHoTZWmZD1Ad8B6oN9FtYCIn4F6g0WL0Aid9gOR70dnQN4HdOYDmAx+BuRajCXjtC7QceGGiX8BS4EMg1GFAJ1b2ANjoxsmyqWP/EWCbfdDMOxMI9BBYb9qDwJVQoHbglomVncXAb0+olcBT03wHGgAd/b/mkyF1bHVudXCZ9pH2k4+djQCOm0CzUTPyH/MBkvA00G0R7kVNc6sHjf6QMrvQNK3R/hnKC7TAgs6xQM3Ay4xQO6K9c9d83wFLgCnXHd8MKd51J9VqmEcyArlzsQ84UU4XArQJ0HVE9iVqmOcyAp0C4syucNpIrj0Ui58AqzKClLpN6T2uQ0iGpNfpUOlCrCNJGwoUQ7V4EmkGxr2srDQPkCdLNvc8QGr5mtqvgGPAcIUltwMXAA1RvWAOJKGFAgnkmhP4efQq0VgoZ88At7R6Ugms0JK5HVuBfwLzKqzxw7lqyKUzOv49RQPpBTLu9JWrCaVQafcbgK4ua5MueKElU3xB7bSBW7EEBqIS63ZwJ+22mQco27Hx9JoFSktY1WXoD2FUXiVH8oH5AAAAAElFTkSuQmCC" /></a></button>
        </form>
    </nav>

    <h1>Menu</h1>

    <?php if (empty($platsParTypeCuisine)): ?>
    <p>No dishes found for the selected filters.</p>
    <?php else: ?>
    <?php foreach ($platsParTypeCuisine as $typeCuisine => $plats): ?>
    <section>
        <h2 class="TypeCuisineTitle"><?= ucfirst($typeCuisine) ?></h2>
        <div class="container">
            <?php foreach ($plats as $plat): ?>
            <div class="dish-card">
                <img class="plat-img" src="<?= htmlspecialchars($plat['image']) ?>"
                    alt="<?= htmlspecialchars($plat['nomPlat']) ?>">
                <h2><?= htmlspecialchars($plat['nomPlat']) ?></h2>
                <p><strong>Type of Kitchen:</strong> <?= htmlspecialchars($plat['TypeCuisine']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($plat['categoriePlat']) ?></p>
                <p class="price"><?= number_format($plat['prix'], 2, ',', ' ') ?> €</p>


                <button name="action" value="add" class="cartBtn add-to-cart"
                    data-plat-id="<?= htmlspecialchars($plat['idPlat']) ?>">Add to
                    Cart</button>



            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>
    <?php endif; ?>
    <script src="script1.js"></script>
</body>

</html>
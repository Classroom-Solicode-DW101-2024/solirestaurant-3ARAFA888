<?php
session_start();

require 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];




$platId = isset($_POST['platId']) ? $_POST['platId'] : null;

$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['platId'])) {

if ($platId && $action) {
    $stmt = $pdo->prepare("SELECT * FROM plat WHERE idPlat = :platId");
    $stmt->execute([':platId' => $platId]);
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plat) {
        if ($action === 'add') {
            if (isset($_SESSION['cart'][$platId])) {
                $_SESSION['cart'][$platId]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$platId] = [
                    'img' => $plat['image'],
                    'name' => $plat['nomPlat'],
                    'price' => $plat['prix'],
                    'quantity' => 1
                ];
            }
        } elseif ($action === 'reduce') {
            if (isset($_SESSION['cart'][$platId])) {
                if ($_SESSION['cart'][$platId]['quantity'] > 1) {

                $_SESSION['cart'][$platId]['quantity'] -= 1;
                }else{
                    unset($_SESSION['cart'][$platId]);
                }
            }
        }

    };
}
// echo json_encode(["success" => true]); 
// exit();
}
// print_r ($cartItems);
$cartItems = $_SESSION['cart'];



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cartStyle.css?=v3">
    <title>Document</title>
</head>

<body>
    <h1>Your Cart</h1>

    <?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Adding</th>
                <th>Reducing</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $platId => $item): ?>
            <tr>
                <td><img class="plat-img" src="<?= htmlspecialchars($item['img']) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2, ',', ' ') ?> €</td>
                <td class="addingReducingBtn">
                    <form method="POST" action="cart1.php">
                        <input type="hidden" name="platId" value="<?= htmlspecialchars($platId) ?>">
                        <input type="hidden" name="action" value="add">
                        <button type="submit">Add an element</button>
                    </form>
                </td>
                <td class="addingReducingBtn">
                    <form method="POST" action="cart1.php">
                        <input type="hidden" name="platId" value="<?= htmlspecialchars($platId) ?>">
                        <input type="hidden" name="action" value="reduce">
                        <button type="submit">Reduce an element</button>
                    </form>
                </td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add an option for checkout (optional) -->
    <!-- <a href="checkout.php">Proceed to Checkout</a> -->
    <?php endif; ?>
    <form method="POST" action="cart1.php">
        <input type="hidden" name="confirm_order" value="1">
        <button type="submit">Confirm Order</button>
    </form>

    <script src="script1.js"></script>
</body>

</html>
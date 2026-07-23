<?php

session_start();

if (empty($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/data.php';

if (!isset($_SESSION['orders']) || !is_array($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

$orderMessage = '';
$orderError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSku = trim((string) ($_POST['sku'] ?? ''));
    $orderQty = filter_input(
        INPUT_POST,
        'order_qty',
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1,
                'max_range' => 100,
            ],
        ]
    );

    $selectedProduct = null;

    foreach ($productObjects as $product) {
        if ($product->getSku() === $selectedSku) {
            $selectedProduct = $product;
            break;
        }
    }

    if ($selectedProduct === null) {
        $orderError = 'SKU khong hop le.';
    } elseif ($orderQty === false || $orderQty === null) {
        $orderError = 'So luong dat thu phai tu 1 den 100.';
    } else {
        $_SESSION['orders'][] = [
            'sku' => $selectedProduct->getSku(),
            'name' => $selectedProduct->getName(),
            'qty' => $orderQty,
        ];

        $orderMessage = 'Da luu order thu vao Session.';
    }
}

$totalInventoryValue = 0;

foreach ($productObjects as $product) {
    $totalInventoryValue += $product->lineTotal();
}

$username = (string) ($_SESSION['username'] ?? 'admin');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MiniShop — Dashboard OOP</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 16px;
            background: #f3f4f6;
            color: #1f2937;
        }

        header,
        section {
            margin-bottom: 24px;
            padding: 18px;
            background: white;
            border: 1px solid #d1d5db;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #d1d5db;
            text-align: left;
        }

        th {
            background: #e5e7eb;
        }

        .number {
            text-align: right;
        }

        .message {
            padding: 10px;
            color: #166534;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }

        .error {
            padding: 10px;
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 14px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        select,
        input,
        button {
            margin-top: 6px;
            padding: 9px;
        }

        a {
            color: #1d4ed8;
        }
    </style>
</head>

<body>
    <!-- MS_EXPECT product_count=8 inventory_value=41380000 -->

    <header>
        <div>
            <h1>MiniShop — Dashboard OOP</h1>
            <p>
                Xin chao,
                <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></strong>
            </p>
        </div>

        <a href="logout.php">Dang xuat</a>
    </header>

    <section>
        <h2>Danh sach 8 san pham</h2>

        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Ten</th>
                    <th>Danh muc</th>
                    <th>Gia</th>
                    <th>So luong</th>
                    <th>Thanh tien</th>
                    <th>Muc ton</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($productObjects as $product): ?>
                    <?php
                    $categoryName =
                        $categoryMap[$product->getCategoryId()] ?? '—';
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $product->getSku(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $product->getName(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $categoryName,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td class="number">
                            <?= $product->getPrice() ?>
                        </td>

                        <td class="number">
                            <?= $product->getQty() ?>
                        </td>

                        <td class="number">
                            <?= $product->lineTotal() ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $product->stockLevel(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <strong>Tong gia tri kho:</strong>
            <?= $totalInventoryValue ?>
        </p>
    </section>

    <section>
        <h2>Dat thu san pham bang Session</h2>

        <?php if ($orderMessage !== ''): ?>
            <p class="message">
                <?= htmlspecialchars($orderMessage, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <?php if ($orderError !== ''): ?>
            <p class="error">
                <?= htmlspecialchars($orderError, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="post" action="dashboard.php">
            <div>
                <label for="sku">Chon SKU</label>
                <select id="sku" name="sku" required>
                    <?php foreach ($productObjects as $product): ?>
                        <option value="<?= htmlspecialchars(
                            $product->getSku(),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                            <?= htmlspecialchars(
                                $product->getSku() . ' — ' . $product->getName(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="order_qty">So luong dat thu</label>
                <input
                    id="order_qty"
                    name="order_qty"
                    type="number"
                    min="1"
                    max="100"
                    value="1"
                    required
                >
            </div>

            <button type="submit">Them order</button>
        </form>
    </section>

    <section>
        <h2>Danh sach order trong Session</h2>

        <?php if ($_SESSION['orders'] === []): ?>
            <p>Chua co order nao.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>SKU</th>
                        <th>Ten san pham</th>
                        <th>So luong dat thu</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($_SESSION['orders'] as $index => $order): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td>
                                <?= htmlspecialchars(
                                    (string) $order['sku'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    (string) $order['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td class="number">
                                <?= (int) $order['qty'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                Hay nhan F5 de kiem tra danh sach order van duoc Session luu lai.
            </p>
        <?php endif; ?>
    </section>
</body>
</html>

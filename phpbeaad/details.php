<?php
if (isset($_GET['id'])) {
    $cards = json_decode(file_get_contents('cards.json'), true);

    if (isset($cards[$_GET['id']])) {
        $card = $cards[$_GET['id']];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>IKémon | <?= $card['name'] ?></title>
            <link rel="stylesheet" href="styles/main.css">
            <link rel="stylesheet" href="styles/details.css">

            <style>
                <?php

                $typeColors = [
                    'normal' => '#A9A9A9',
                    'fire' => '#FF4500',
                    'water' => '#0000CD',
                    'electric' => '#FFD700',
                    'grass' => '#006400',
                    'ice' => '#00CED1',
                    'fighting' => '#A52A2A',
                    'poison' => '#800080',
                    'ground' => '#8B4513',
                    'psychic' => '#FF1493',
                    'bug' => '#8B4513',
                    'rock' => '#696969',
                    'ghost' => '#4B0082',
                    'dark' => '#2F4F4F',
                    'steel' => '#708090',
                ];

                if (array_key_exists(strtolower($card['type']), $typeColors)) {
                    echo '.card-type{ color: ' . $typeColors[strtolower($card['type'])] . '; }';
                }
                ?>
            </style>
        </head>

        <body>
            <header>
                <h1><a href="index.php">IKémon</a> > <?= $card['name'] ?></h1>
                <h2><a href='index.php'>Vissza a főoldalra</a></h2>
            </header>
            <div id="content">
                <div id="details">
                    <div class="image clr-<?= strtolower($card['type']) ?>">
                        <img src="<?= $card['image'] ?>" alt="">
                    </div>
                    <div class="info">
                        <div class="description"><?= $card['description'] ?></div>
                        <span class="card-type"><span class="icon">🏷</span> <span style="color: black;">Type:</span> <?= $card['type'] ?></span>

                        <div class="attributes">
                            <div class="card-hp"><span class="icon">❤</span> Health: <?= $card['hp'] ?></div>
                            <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $card['attack'] ?></div>
                            <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $card['defense'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <p>IKémon | ELTE IK Webprogramozás</p>
            </footer>
        </body>

        </html>

<?php
    }
}
?>
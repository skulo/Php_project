<?php



ob_start();
$users = json_decode(file_get_contents('users.json'), true);


if (isset($_POST['logout'])) {
    $users['loggedin'] = [
        "username" => "",
        "email" => "",
        "pw1" => "",
        "money" => "",
        "cards" => [],
        "id" => "",
    ];

    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {

    header("Location: login.php");
    exit();
}

if (isset($_POST['reg'])) {

    header("Location: register.php");
    exit();
}

$isloggedin = false;
$userid = "";


if ($users['loggedin']['username'] !== "") {
    $isloggedin = true;
    $userid = $users['loggedin']['id'];
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home </h1>
        <div id="datadetails">
            <?php if ($users['loggedin']['username'] == "admin") : ?>

                <a href="addcard.php">Új kártya hozzáadása</a>
                <br>

            <?php endif; ?>

            <?php if ($users['loggedin']['username'] == "") : ?>

                <form method="post">
                    <button type="submit" name="reg">Regisztráció</button>
                </form>


                <form method="post">
                    <button type="submit" name="login">Bejelentkezés</button>
                </form>
            <?php endif; ?>


            <?php if ($users['loggedin']['username'] !== "") : ?>

                <span>Bejelentkezve mint: </span> <span><a href="userdetails.php"> <?= $users['loggedin']['username'] ?></a></span>
                <br>

                <?php if ($users['loggedin']['username'] != "admin") : ?>
                    <span> <?= "Vagyon:  " . $users['loggedin']['money'] . " 💰" ?></span>
                <?php endif; ?>
                <form method="post">
                    <button type="submit" name="logout">Kijelentkezés</button>
                </form>

            <?php endif; ?>

        </div>
    </header>
    <div id="selector">
        <label for="card-type-filter">Szűrés típus szerint:</label>
        <select id="card-type-filter" onchange="filterCards(this.value)">
            <option value="all" selected>Összes</option>
            <option value="bug">Bug</option>
            <option value="dark">Dark</option>
            <option value="electric">Electric</option>
            <option value="fighting">Fighting</option>
            <option value="fire">Fire</option>
            <option value="ghost">Ghost</option>
            <option value="ground">Ground</option>
            <option value="ice">Ice</option>
            <option value="normal">Normal</option>
            <option value="poison">Poison</option>
            <option value="psychic">Psychic</option>
            <option value="rock">Rock</option>
            <option value="steel">Steel</option>
            <option value="water">Water</option>
            <option value="grass">Grass</option>
        </select>
    </div>

    <div id="content">
        <div id="card-list">


            <?php
            $cards = json_decode(file_get_contents('cards.json'), true);


            ?>

            <?php foreach ($cards as $key => $card) : ?>
                <div class="pokemon-card">
                    <div class="image clr-<?php echo strtolower($card['type']); ?>">
                        <img src="<?php echo $card['image']; ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?php echo $key; ?>"><?php echo $card['name']; ?></a></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?php echo $card['type']; ?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?php echo $card['hp']; ?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?php echo $card['attack']; ?></span>
                            <span class="card-defense"><span class="icon">🛡</span> <?php echo $card['defense']; ?></span>
                        </span>
                    </div>
                    <div class="buy">
                        <span class="card-price"><span class="icon">💰</span> <?php echo $card['price']; ?></span>


                        <?php if ($isloggedin && $userid != "admin" && $card["owner"] == "admin") : ?>

                            <?php if (count($users['loggedin']['cards']) > 4) : ?>
                                <br>
                                <span>Maximum 5 kártyád lehet!</span>
                            <?php elseif ($users['loggedin']['money'] < $card['price']) : ?>
                                <br>
                                <span>Nincs elegendő pénzed!</span>
                            <?php else : ?>
                                <form method="post">
                                    <input type="hidden" name="card_key" value="<?php echo $key; ?>">
                                    <button type="submit" name="purchase_button">Vásárlás</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>


            <?php endforeach; ?>
            <?php
            if ($isloggedin && isset($_POST['purchase_button'])) {

                $selectedcardkey = $_POST['card_key'];

                if (($users['loggedin']['money'] > $cards[$selectedcardkey]['price']) && count($users['loggedin']['cards']) < 5) {

                    $cards[$selectedcardkey]['owner'] = $userid;

                    $users['loggedin']['cards'][$selectedcardkey] = $cards[$selectedcardkey];
                    $users['loggedin']['money'] -= $cards[$selectedcardkey]['price'];

                    $users['admin']['money'] += $cards[$selectedcardkey]['price'];
                    unset($users['admin']['cards'][$selectedcardkey]);

                    $users[$userid]['cards'][$selectedcardkey] = $cards[$selectedcardkey];
                    $users[$userid]['money'] -= $cards[$selectedcardkey]['price'];

                    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    file_put_contents('cards.json', json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    header("Location: index.php");
                    exit();
                }
            }
            ?>

        </div>
    </div>

    <script>
        function filterCards(type) {
            var cards = document.getElementsByClassName('pokemon-card');

            for (var i = 0; i < cards.length; i++) {
                var cardType = cards[i].querySelector('.card-type').innerText.split(' ')[1];
                if (type === 'all' || cardType.toLowerCase() === type) {
                    cards[i].style.display = 'block';
                } else {
                    cards[i].style.display = 'none';
                }
            }
        }
    </script>


    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
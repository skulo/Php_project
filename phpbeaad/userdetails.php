<?php



ob_start();
$users = json_decode(file_get_contents('users.json'), true);
$cards = json_decode(file_get_contents('cards.json'), true);
$loggedinuserscards = $users['loggedin']['cards'];
$userid = $users['loggedin']['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?= $users['loggedin']['username'] ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > <?= $users['loggedin']['username'] ?> </h1>


        <div id="datadetails">

            <?= "Felhasználónév:  " . $users['loggedin']['username'] ?>
            <br>
            <?php if ($users['loggedin']['username'] != "admin") : ?>
                <?= "Email:  " . $users['loggedin']['email'] ?>
                <br>
                <?= "Vagyon:  " . $users['loggedin']['money'] . " 💰" ?>
                <br>
            <?php endif; ?>
            <a href='index.php'>Vissza a főoldalra</a>
        </div>
    </header>
    <div id="content">
        <div id="selector">
            <?php if (count($users['loggedin']['cards']) > 0) : ?>

                Kártyáid:

            <?php else : ?>
                Nincsenek kártyáid.
            <?php endif; ?>
        </div>
        <div id="card-list">


            <?php foreach ($loggedinuserscards as $key => $card) : ?>
                <div class="pokemon-card">
                    <div class="image clr-<?php echo strtolower($card['type']); ?>">
                        <img src="<?php echo $card['image']; ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><?php echo $card['name']; ?></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?php echo $card['type']; ?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?php echo $card['hp']; ?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?php echo $card['attack']; ?></span>
                            <span class="card-defense"><span class="icon">🛡</span> <?php echo $card['defense']; ?></span>
                        </span>
                    </div>
                    <div class="buy">

                        <?php if ($userid != "admin") : ?>
                            <form action="userdetails.php" method="post">
                                <input type="hidden" name="card_key" value="<?php echo $key; ?>">
                                <button type="submit" name="sell_button">Eladás: 💰 <?php echo round($card['price'] * 0.9); ?></button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>


            <?php endforeach; ?>


            <?php
            if (isset($_POST['sell_button'])) {

                $selectedcardkey = $_POST['card_key'];

                $users['loggedin']['cards'][$selectedcardkey]["owner"] = "admin";
                $cards[$selectedcardkey]["owner"] = "admin";
                $users['admin']['cards'][$selectedcardkey] = $users['loggedin']['cards'][$selectedcardkey];

                $users['admin']['money'] -= (round($loggedinuserscards[$selectedcardkey]['price'] * 0.9));



                unset($users['loggedin']['cards'][$selectedcardkey]);
                $users['loggedin']['money'] += (round($loggedinuserscards[$selectedcardkey]['price'] * 0.9));

                unset($users[$userid]['cards'][$selectedcardkey]);
                $users[$userid]['money'] += (round($loggedinuserscards[$selectedcardkey]['price'] * 0.9));


                file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                file_put_contents('cards.json', json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                header("Location: userdetails.php");
                exit();
            }
            ?>
        </div>
    </div>


    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
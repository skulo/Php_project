<?php
$errors = [];
$data = [];
$input = $_POST;
$loginid = '';



if ($_POST) {
    $users = json_decode(file_get_contents('users.json'), true);
    $is_valid = validate($data, $errors, $input, $users, $loginid);
}

function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== '');
}
function validate(&$data, &$errors, $input, &$users, &$loginid)
{




    if (is_empty($input, 'username')) {
        $errors['username'] = 'A felhasználónév megadása kötelező!';
    } else if (!in_array(trim($input['username']), array_map('trim', array_column($users, 'username')))) {
        $errors['username'] = 'Helytelen felhasználónév';
    } {
        $data['username'] = $input['username'];
    }


    foreach ($users as $key => $userData) {
        if ($userData['username'] === $input['username']) {
            $loginid = $key;

            if (is_empty($input, 'pw1')) {
                $errors['pw1'] = 'A jelszó megadása kötelező!';
            } else if ($input["pw1"] != $users[$loginid]["pw1"]) {
                $errors['pw1'] = 'Helytelen jelszó!';
            } else {
                $data['pw1'] = $input['pw1'];
            }



            break;
        }
    }


    return count($errors) == 0;
}

if ($_POST && $is_valid) {

    $newuser = [
        "username" => $data["username"],
        "email" => $users[$loginid]["email"],
        "pw1" => $data["pw1"],
        "money" => $users[$loginid]["money"],
        "cards" => $users[$loginid]["cards"],
        "id" => $users[$loginid]["id"],
    ];


    $users['loggedin'] = $newuser;

    $jsonWriteSuccess = file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $input = [];
}

if ($_POST && $is_valid) {
    header("Location: index.php");
}
if ($_POST && !$is_valid) {
    $errorMessage = "Sikertelen bejelentkezés!";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">
    <style>
        form {
            display: grid;
            grid-column-gap: 20px;
        }

        form h3 {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Bejelentkezés</h1>
        <h2><a href='index.php'>Vissza a főoldalra</a></h2>
    </header>

    <div id="content">


        <form action="login.php" method="post" novalidate>
            <div>
                <h3><label for="username">Felhasználónév</label></h3>
                <input type="text" id="username" name="username" value="<?= isset($input['username']) ? $input['username'] : "" ?>">
                <span style="color: red;"><?= isset($errors["username"]) ? $errors["username"] : "" ?></span>


                <h3><label for="pw1">Jelszó</label></h3>
                <input type="password" id="pw1" name="pw1" value="<?= isset($input['pw1']) ? $input['pw1'] : "" ?>">
                <span style="color: red;"><?= isset($errors["pw1"]) ? $errors["pw1"] : "" ?></span>




                <br>
                <button type="submit">Bejelentkezés</button>



            </div>

        </form>



</body>

</html>
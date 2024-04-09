<?php
$errors = [];
$data = [];
$input = $_POST;




if ($_POST) {
    $users = json_decode(file_get_contents('users.json'), true);
    $is_valid = validate($data, $errors, $input, $users);
}

function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== '');
}
function validate(&$data, &$errors, $input, $users)
{

    if (is_empty($input, 'username')) {
        $errors['username'] = 'A felhasználónév megadása kötelező!';
    } else if (strlen(trim($input["username"])) < 4) {
        $errors['username'] = 'A felhasználónév hossza legalább 4 karakter legyen!';
    } else if (in_array(trim($input['username']), array_map('trim', array_column($users, 'username')))) {
        $errors['username'] = 'Ez a felhasználónév már foglalt!';
    } else {
        $data['username'] = $input['username'];
    }


    if (is_empty($input, 'email')) {
        $errors['email'] = 'Az email megadása kötelező!';
    } else if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Az email formátuma helytelen!';
    } else {
        $data['email'] = $input['email'];
    }

    if (is_empty($input, 'pw1')) {
        $errors['pw1'] = 'A jelszó megadása kötelező!';
    } else if (strlen(trim($input["pw1"])) < 7) {
        $errors['pw1'] = 'A jelszó hossza legalább 8 karakter legyen!';
    } else if ($input["pw1"] != $input["pw2"]) {
        $errors['pw1'] = 'A jelszavak nem egyeznek meg!';
    } else {
        $data['pw1'] = $input['pw1'];
    }

    if (is_empty($input, 'pw2')) {
        $errors['pw2'] = 'A jelszó újbóli megadása kötelező!';
    } else if (strlen(trim($input["pw2"])) < 7) {
        $errors['pw2'] = 'A jelszó hossza legalább 8 karakter legyen!';
    } else if ($input["pw1"] != $input["pw2"]) {
        $errors['pw2'] = 'A jelszavak nem egyeznek meg!';
    } else {
        $data['pw1'] = $input['pw1'];
    }


    return count($errors) == 0;
}

if ($_POST && $is_valid) {

    $newuser = [
        "username" => $data["username"],
        "email" => $data["email"],
        "pw1" => $data["pw1"],
        "money" => 500,
        "cards" => [],
        "id" => uniqid(),
    ];


    $users[$newuser['id']] = $newuser;
    $users['loggedin'] = $newuser;

    $jsonWriteSuccess = file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));


    $input = [];
}

if ($_POST && $is_valid) {
    header("Location: index.php");
}
if ($_POST && !$is_valid) {
    $errorMessage = "Sikertelen regisztráció!";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
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
        <h1>Regisztráció</h1>
        <h2><a href='index.php'>Vissza a főoldalra</a></h2>
    </header>

    <div id="content">
        <form action="register.php" method="post" novalidate>
            <div>
                <h3><label for="username">Felhasználónév</label></h3>
                <input type="text" id="username" name="username" value="<?= isset($input['username']) ? $input['username'] : "" ?>">
                <span style="color: red;"><?= isset($errors["username"]) ? $errors["username"] : "" ?></span>

                <h3><label for="email">Email cím</label></h3>
                <input type="text" id="email" name="email" value="<?= isset($input['email']) ? $input['email'] : "" ?>">
                <span style="color: red;"><?= isset($errors["email"]) ? $errors["email"] : "" ?></span>

                <h3><label for="pw1">Jelszó</label></h3>
                <input type="password" id="pw1" name="pw1" value="<?= isset($input['pw1']) ? $input['pw1'] : "" ?>">
                <span style="color: red;"><?= isset($errors["pw1"]) ? $errors["pw1"] : "" ?></span>

                <h3><label for="pw2">Jelszó mégegyszer</label></h3>
                <input type="password" id="pw2" name="pw2" value="<?= isset($input['pw2']) ? $input['pw2'] : "" ?>">
                <span style="color: red;"><?= isset($errors["pw2"]) ? $errors["pw2"] : "" ?></span>


                <br>
                <button type="submit">Regisztráció</button>



            </div>

        </form>


</body>

</html>
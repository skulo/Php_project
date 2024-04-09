<?php
$errors = [];
$data = [];
$input = $_GET;


$users = json_decode(file_get_contents('users.json'), true);

if ($_GET) {
  $is_valid = validate($data, $errors, $input);
}

function is_empty($input, $key)
{
  return !(isset($input[$key]) && trim($input[$key]) !== '');
}
function validate(&$data, &$errors, $input)
{


  if (is_empty($input, 'name')) {
    $errors['name'] = 'A név megadása kötelező!';
  } else if (strlen(trim($input["name"])) < 4) {
    $errors['name'] = 'A név hossza legalább 4 legyen!';
  } else {
    $data['name'] = $input['name'];
  }

  if (is_empty($input, 'type')) {
    $errors['type'] = 'A típus megadása kötelező!';
  } else if (!in_array($input['type'], ["bug", "dark", "electric", "fighting", "fire", "ghost", "ground", "ice", "normal", "poison", "psychic", "rock", "steel", "water", "grass"])) {
    $errors['type'] = 'A típus csak a megadott értékeket veheti fel!';
  } else {
    $data['type'] = $input['type'];
  }

  if (is_empty($input, 'hp')) {
    $errors['hp'] = 'Az életerő megadása kötelező!';
  } else if (!filter_var($input["hp"], FILTER_VALIDATE_INT)) {
    $errors['hp'] = 'Az életerő csak egész szám lehet!';
  } else if ($input["hp"] < 1) {
    $errors['hp'] = 'Az életerő legalább 1 legyen!';
  } else {
    $data['hp'] = $input['hp'];
  }

  if (is_empty($input, 'attack')) {
    $errors['attack'] = 'A támadás megadása kötelező!';
  } else if (!filter_var($input["attack"], FILTER_VALIDATE_INT)) {
    $errors['attack'] = 'A támadás csak egész szám lehet!';
  } else if ($input["attack"] < 1) {
    $errors['attack'] = 'A támadás legalább 1 legyen!';
  } else {
    $data['attack'] = $input['attack'];
  }


  if (is_empty($input, 'defense')) {
    $errors['defense'] = 'A védekezés megadása kötelező!';
  } else if (!filter_var($input["defense"], FILTER_VALIDATE_INT)) {
    $errors['defense'] = 'A védekezés csak egész szám lehet!';
  } else if ($input["defense"] < 1) {
    $errors['defense'] = 'A védekezés legalább 1 legyen!';
  } else {
    $data['defense'] = $input['defense'];
  }


  if (is_empty($input, 'price')) {
    $errors['price'] = 'Az ár megadása kötelező!';
  } else if (!filter_var($input["price"], FILTER_VALIDATE_INT)) {
    $errors['price'] = 'Az ár csak egész szám lehet!';
  } else if ($input["price"] < 1) {
    $errors['price'] = 'Az ár legalább 1 legyen!';
  } else {
    $data['price'] = $input['price'];
  }




  if (is_empty($input, 'description')) {
    $errors['description'] = 'Add meg a leírást!';
  } else if (strlen(trim($input["description"])) < 3) {
    $errors['description'] = 'A leírás legalább 3 karakter!';
  } else {
    $data['description'] = $input['description'];
  }



  if (is_empty($input, 'image')) {
    $errors['image'] = 'Add meg a kép URL-jét!';
  } else if (strlen(trim($input["image"])) < 5) {
    $errors['image'] = 'Az URL legalább 5 karakter!';
  } else {
    $data['image'] = $input['image'];
  }


  return count($errors) == 0;
}

if ($_GET && $is_valid) {
  $cards = json_decode(file_get_contents('cards.json'), true);

  $newCard = [
    "name" => $data["name"],
    "type" => $data["type"],
    "hp" => $data["hp"],
    "attack" => $data["attack"],
    "defense" => $data["defense"],
    "price" => $data["price"],
    "description" => $data["description"],
    "image" => $data["image"],
    "owner" => "admin",
    "id" => "card" . uniqid(),
  ];


  $cards[$newCard['id']] = $newCard;
  $users['admin']['cards'][$newCard['id']] = $newCard;

  $jsonWriteSuccess = file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  $jsonWriteSuccess = file_put_contents('cards.json', json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

  $input = [];
}

if ($_GET && $is_valid) {
  $successMessage = "Sikeres hozzáadás!";
}
if ($_GET && !$is_valid) {
  $errorMessage = "Sikertelen hozzáadás!";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Új kártya hozzáadása</title>
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/cards.css">
  <style>
    form {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-column-gap: 20px;
    }

    form h3 {
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  <header>
    <h1>Új kártya hozzáadása</h1>
    <h2><a href='index.php'>Vissza a főoldalra</a></h2>
  </header>

  <div id="content">
    <form action="addcard.php" method="get" novalidate>
      <div>
        <h3><label for="name">Név:</label></h3>
        <input type="text" id="name" name="name" value="<?= isset($input['name']) ? $input['name'] : "" ?>">
        <span style="color: red;"><?= isset($errors["name"]) ? $errors["name"] : "" ?></span>


        <h3>Típus</h3>
        <select name="type">
          <option value="">Kérem, válasszon típust!</option>
          <option value="bug" <?php echo (isset($input['type']) && ($input['type'] == "bug")) ? "selected" : "" ?>>Bug</option>
          <option value="dark" <?php echo (isset($input['type']) && ($input['type'] == "dark")) ? "selected" : "" ?>>Dark</option>
          <option value="electric" <?php echo (isset($input['type']) && ($input['type'] == "electric")) ? "selected" : "" ?>>Electric</option>
          <option value="fighting" <?php echo (isset($input['type']) && ($input['type'] == "fighting")) ? "selected" : "" ?>>Fighting</option>
          <option value="fire" <?php echo (isset($input['type']) && ($input['type'] == "fire")) ? "selected" : "" ?>>Fire</option>
          <option value="ghost" <?php echo (isset($input['type']) && ($input['type'] == "ghost")) ? "selected" : "" ?>>Ghost</option>
          <option value="ground" <?php echo (isset($input['type']) && ($input['type'] == "ground")) ? "selected" : "" ?>>Ground</option>
          <option value="ice" <?php echo (isset($input['type']) && ($input['type'] == "ice")) ? "selected" : "" ?>>Ice</option>
          <option value="normal" <?php echo (isset($input['type']) && ($input['type'] == "normal")) ? "selected" : "" ?>>Normal</option>
          <option value="poison" <?php echo (isset($input['type']) && ($input['type'] == "poison")) ? "selected" : "" ?>>Poison</option>
          <option value="psychic" <?php echo (isset($input['type']) && ($input['type'] == "psychic")) ? "selected" : "" ?>>Psychic</option>
          <option value="rock" <?php echo (isset($input['type']) && ($input['type'] == "rock")) ? "selected" : "" ?>>Rock</option>
          <option value="steel" <?php echo (isset($input['type']) && ($input['type'] == "steel")) ? "selected" : "" ?>>Steel</option>
          <option value="water" <?php echo (isset($input['type']) && ($input['type'] == "water")) ? "selected" : "" ?>>Water</option>
          <option value="grass" <?php echo (isset($input['type']) && ($input['type'] == "grass")) ? "selected" : "" ?>>Grass</option>
        </select>
        <span style="color: red;"><?= isset($errors["type"]) ? $errors["type"] : "" ?></span>

        <h3><label for="hp">Életerő:</label></h3>
        <input type="text" id="hp" name="hp" value="<?= isset($input['hp']) ? $input['hp'] : "" ?>">
        <span style="color: red;"><?= isset($errors["hp"]) ? $errors["hp"] : "" ?></span>

        <h3><label for="attack">Támadás:</label></h3>
        <input type="text" id="attack" name="attack" value="<?= isset($input['attack']) ? $input['attack'] : "" ?>">
        <span style="color: red;"><?= isset($errors["attack"]) ? $errors["attack"] : "" ?></span>



        <h3><label for="defense">Védelem:</label></h3>
        <input type="text" id="defense" name="defense" value="<?= isset($input['defense']) ? $input['defense'] : "" ?>">
        <span style="color: red;"><?= isset($errors["defense"]) ? $errors["defense"] : "" ?></span>
      </div>
      <div>
        <h3><label for="price">Ár:</label></h3>
        <input type="text" id="price" name="price" value="<?= isset($input['price']) ? $input['price'] : "" ?>">
        <span style="color: red;"><?= isset($errors["price"]) ? $errors["price"] : "" ?></span>

        <h3><label for="description">Leírás:</label></h3>
        <textarea id="description" name="description" rows="4"><?= isset($input['description']) ? $input['description'] : "" ?></textarea>
        <span style="color: red;"><?= isset($errors["description"]) ? $errors["description"] : "" ?></span>

        <h3><label for="image">Kép URL:</label></h3>
        <input type="text" id="image" name="image" value="<?= isset($input['image']) ? $input['image'] : "" ?>">
        <span style="color: red;"><?= isset($errors["image"]) ? $errors["image"] : "" ?></span>


        <br>
        <input type="submit" value="Hozzáadás">



      </div>

    </form>


    <?php if ($_GET) : ?>

      <?php if (isset($successMessage)) : ?>
        <div class="success-message">
          <h2><?= $successMessage ?></h2>
        </div>
      <?php endif; ?>

      <?php if (isset($errorMessage)) : ?>
        <div class="error-message">
          <h2><?= $errorMessage ?></h2>
        </div>
      <?php endif; ?>


    <?php endif; ?>
</body>

</html>
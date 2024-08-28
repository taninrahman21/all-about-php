<?php
session_start();
require_once "inc/functions.php";
$info = "";
$task = isset($_GET['task']) ? $_GET['task'] : "report";
$error = isset($_GET['error']) ? $_GET['error'] : "0";

if ("seed" == $task) {
  seedData();
  $info = "Successfully Generated Singers Data.";
}

$name = "";
$age = "";
$genre = "";
$country = "";
if (isset($_POST['submit'])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
  $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);
  $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
  $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

  if ($id) {
    if ($name != "" && $age != 0 && $genre != "" && $country != "") {
      $isUpdate = updateSinger($id, $name, $age, $genre, $country);
      if ($isUpdate) {
        header('Location: index.php?task=report');
      } else {
        $error = 1;
      }
    }
  } else {
    if ($name != "" && $age != 0 && $genre != "" && $country != "") {
      $isAdded = addSinger($name, $age, $genre, $country);
      if ($isAdded) {
        header('Location: index.php?task=report');
      } else {
        $error = 1;
      }
    }
  }
}

if ("delete" == $task) {
  if (!isAdmin()) {
    header('Location: index.php');
    exit;
  }
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  if ($id) {
    $is_delete = deleteSinger($id);
    if ($is_delete) {
      header('Location: index.php?task=report');
    } else {
      $delete_error = "Can't Delete!";
    }
  }
}

if ("edit" == $task) {
  if (!(!isAdmin() || !isEditor())) {
    header("Location: index.php");
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crud Operation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="lg:w-2/5 md:w-3/4 m-auto mt-5">
    <header>
      <h2 class="text-3xl font-bold text-center">CRUD Operation Through PHP</h2>
      <p class="text-sm text-center text-gray-500 mt-3">In this webpage, We are going to do an operation call CRUD
        Operation through PHP. So Let's do it with all together. Lorem ipsum dolor sit amet consectetur adipisicing
        elit. Nemo, voluptatem. Libero ut delectus eveniet quae soluta.</p>
    </header>

    <!-- Add Nav -->
    <?php include_once("./inc/tamplates/nav.php"); ?>
    <!-- Add Nav -->

    <?php if ($info !== ""): ?>
      <div class="flex justify-center mt-5 text-xl text-center text-green-700">
        <?php echo "<p>{$info}</p>"; ?>
      </div>
    <?php endif; ?>

    <?php if ("1" == $error): ?>
      <div class="flex justify-center mt-5 text-xl text-center text-red-700">
        <blockquote>Failed to <?php echo ($id ? "update {$name}." : "Add {$name}."); ?> Already Added <?php echo $name; ?>
        </blockquote>
      </div>
    <?php endif; ?>

    <?php if ("report" == $task): ?>
      <div class="flex flex-col justify-center mt-5 text-xl">
        <?php generateReport(); ?>
        <!-- <div class="mt-3">
          <pre>
              <?php echo "Working Not supported"; ?>
            </pre>
        </div> -->
      </div>
    <?php endif; ?>

    <?php if ("add" == $task): ?>
      <form method="POST" action="index.php?task=add" class="mt-10">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
          <input type="text" id="name" name="name"
            class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
            placeholder="Enter Singer Name" value="<?php echo $name; ?>">
        </div>
        <div class="mb-4">
          <label for="age" class="block text-gray-700 text-sm font-bold mb-2">Age</label>
          <input type="number" id="age" name="age"
            class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
            placeholder="Enter Singer's Age" value="<?php echo $age; ?>">
        </div>
        <div class="mb-4">
          <label for="genre" class="block text-gray-700 text-sm font-bold mb-2">Genre</label>
          <input type="text" id="genre" name="genre"
            class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
            placeholder="Enter Genre" value="<?php echo $genre; ?>">
        </div>
        <div class="mb-4">
          <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
          <input type="text" id="country" name="country"
            class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
            placeholder="Enter Country Name" value="<?php echo $country; ?>">
        </div>

        <div class="flex items-center justify-center">
          <input type="submit" name="submit" value="Add New Singer"
            class="py-2 px-5 bg-green-500 text-white hover:bg-black transition-all duration-300 cursor-pointer">
        </div>
      </form>
    <?php endif; ?>

    <?php if ("edit" == $task):
      $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
      $singer = getSinger($id);
      if ($singer):
        ?>
        <form method="POST" class="mt-10">
          <input type="hidden" name="id" value="<?php echo $id; ?>">

          <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name"
              class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
              placeholder="Enter Singer Name" value="<?php echo htmlspecialchars($name ? $name : $singer['name']); ?>">
          </div>
          <div class="mb-4">
            <label for="age" class="block text-gray-700 text-sm font-bold mb-2">Age</label>
            <input type="number" id="age" name="age"
              class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
              placeholder="Enter Singer's Age" value="<?php echo htmlspecialchars($age ? $age : $singer['age']); ?>">
          </div>
          <div class="mb-4">
            <label for="genre" class="block text-gray-700 text-sm font-bold mb-2">Genre</label>
            <input type="text" id="genre" name="genre"
              class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
              placeholder="Enter Genre" value="<?php echo htmlspecialchars($genre ? $genre : $singer['genre']); ?>">
          </div>
          <div class="mb-4">
            <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
            <input type="text" id="country" name="country"
              class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-green-600 outline-none"
              placeholder="Enter Country Name"
              value="<?php echo htmlspecialchars($country ? $country : $singer['country']); ?>">
          </div>

          <div class="flex items-center justify-center">
            <input type="submit" name="submit" value="Update Singer"
              class="py-2 px-5 bg-green-500 text-white hover:bg-black transition-all duration-300 cursor-pointer">
          </div>
        </form>
        <?php
      endif;
    endif;
    ?>

  </div>
</body>

<script type="text/javascript" src="assets/script.js"></script>

</html>
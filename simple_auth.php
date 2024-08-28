<?php
session_start([
  "cookie_lifetime" => 300, // 5 minutes
]);

$error = false;
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

if ($username && $password) {
  $users_file = fopen(".\\data\\users.txt", "r");
  $login_success = false;
  session_unset();

  while ($users_data = fgetcsv($users_file)) {
    if ($users_data[0] == $username && $users_data[1] == sha1($password)) {
      $_SESSION["loggedin"] = true;
      $_SESSION["username"] = $username;
      $_SESSION["role"] = $users_data[2];
      $login_success = true;
      break; // Exit loop once the user is found
    }
  }

  fclose($users_file);

  if ($login_success) {
    header("Location: index.php");
    exit;
  } else {
    $error = true;
  }
}

if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - CRUD Operation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="lg:w-2/5 md:w-3/4 m-auto mt-10">
    <h3 class="text-3xl text-bold text-center">Login</h3>
    <p class="text-sm text-center text-gray-500">Please enter your credentials to access the system.</p>

    <?php if ($error): ?>
      <div class="flex justify-center mt-5 text-xl text-center text-red-700">
        <blockquote>Invalid Username or Password</blockquote>
      </div>
    <?php endif; ?>


    <form class="mt-10" method="POST">
      <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
        <input type="text" id="username" name="username"
          class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-[orangered] outline-none"
          placeholder="Enter Username">
      </div>

      <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input type="password" id="password" name="password"
          class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-[orangered] outline-none"
          placeholder="Enter Your Password">
      </div>

      <div class="flex items-center justify-center">
        <input type="submit" value="Log In"
          class="py-2 px-5 bg-[orangered] text-white hover:bg-black transition-all duration-300 cursor-pointer">
      </div>
    </form>

  </div>
</body>

</html>
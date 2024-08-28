<?php
define('DB_SINGERS', 'D:\\xampp\\htdocs\\php\\html-webpage\\crud_operation\\data\\db.txt');

function seedData()
{
  $singers = array(
    array(
      'id' => 1,
      'name' => 'Justin Bieber',
      'age' => 30,
      'genre' => 'R&B and Pop',
      'country' => 'Canada'
    ),
    array(
      'id' => 2,
      'name' => 'The Kid Laroi',
      'age' => 21,
      'genre' => 'Rap and Pop',
      'country' => 'Australia'
    ),
    array(
      'id' => 3,
      'name' => 'Charlie Puth',
      'age' => 29,
      'genre' => 'Pop',
      'country' => 'US'
    ),
    array(
      'id' => 4,
      'name' => 'Ed Sheeran',
      'age' => 31,
      'genre' => 'Pop',
      'country' => 'America'
    )
  );
  $serialized_data = serialize($singers);

  file_put_contents(DB_SINGERS, $serialized_data, LOCK_EX);
}

function generateReport()
{
  $data = file_get_contents(DB_SINGERS);
  $singers = unserialize($data);
  ?>
  <table class="w-full">
    <tr>
      <th
        class="py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
        Name</th>
      <th
        class="py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
        Age</th>
      <th
        class="py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
        Genre</th>
      <th
        class="py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
        Country</th>
      <?php if (isAdmin() || isEditor()): ?>
        <th
          class="py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider text-center">
          Action</th>
      <?php endif; ?>
    </tr>
    <?php
    foreach ($singers as $singer) {
      ?>
      <tbody>
        <tr>
          <td class="py-4 border-b border-gray-200 text-base text-center text-green-700"><?php echo $singer['name']; ?></td>
          <td class="py-4 border-b border-gray-200 text-base text-center text-green-700"><?php echo $singer['age']; ?></td>
          <td class="py-4 border-b border-gray-200 text-base text-center text-green-700"><?php echo $singer['genre']; ?>
          </td>
          <td class="py-4 border-b border-gray-200 text-base text-center text-green-700"><?php echo $singer['country']; ?>
          </td>
          <?php if (isAdmin()): ?>
            <td class="py-4 border-b border-gray-200 text-base text-center text-green-700">
              <?php printf("<a class='text-black' href='index.php?task=edit&id=%s'>Edit</a> | <a class='text-red-700 delete' href='index.php?task=delete&id=%s'>Delete</a>", $singer["id"], $singer["id"]); ?>
            </td>
          <?php elseif (isEditor()): ?>
            <td class="py-4 border-b border-gray-200 text-base text-center text-green-700">
              <?php printf("<a class='text-black' href='index.php?task=edit&id=%s'>Edit</a>", $singer["id"]); ?>
            </td>
          <?php endif; ?>
        </tr>
      </tbody>
      <?php
    }
    ?>
  </table>
  <?php
}

// Add New Singer

function addSinger($name, $age, $genre, $country)
{
  $data = file_get_contents(DB_SINGERS);
  $singers = unserialize($data);
  $addedSinger = false;

  foreach ($singers as $singer) {
    if ($singer['name'] == $name) {
      $addedSinger = true;
      break;
    }
  }
  if (!$addedSinger) {
    $singer = array(
      'id' => getNewId($singers),
      'name' => $name,
      'age' => $age,
      'genre' => $genre,
      'country' => $country
    );

    array_push($singers, $singer);

    $serialized_data = serialize($singers);

    file_put_contents(DB_SINGERS, $serialized_data, LOCK_EX);
    return true;
  } else {
    return false;
  }
}


function getSinger($id)
{
  $data = file_get_contents(DB_SINGERS);
  $singers = unserialize($data);
  foreach ($singers as $singer) {
    if ($singer['id'] == $id) {
      return $singer;
    }
  }
  return false;
}


function updateSinger($id, $name, $age, $genre, $country)
{
  $data = file_get_contents(DB_SINGERS);
  $singers = unserialize($data);

  // Check if the singer with the same name already exists (except the one being updated)
  $nameExists = array_search($name, array_column($singers, 'name')) !== false && $singers[$id - 1]['name'] !== $name;


  if (!$nameExists) {
    // Use array_map to update the singer's information
    $singers = array_map(function ($singer) use ($id, $name, $age, $genre, $country) {
      if ($singer['id'] == $id) {
        $singer['name'] = $name;
        $singer['age'] = $age;
        $singer['genre'] = $genre;
        $singer['country'] = $country;
      }
      return $singer;
    }, $singers);

    $serialized_data = serialize($singers);
    file_put_contents(DB_SINGERS, $serialized_data, LOCK_EX);
    return true;
  } else {
    return false;
  }
}


function deleteSinger($id)
{
  $data = file_get_contents(DB_SINGERS);
  $singers = unserialize($data);
  $countBefore = count($singers);

  $new_singers = array_filter($singers, function ($singer) use ($id) {
    return $singer['id'] != $id;
  });
  $countAfter = count($new_singers);
  if ($countBefore > $countAfter) {
    $data = serialize($new_singers);
    file_put_contents(DB_SINGERS, $data, LOCK_EX);
    return true;
  } else {
    return false;
  }
}

function getNewId($singers)
{
  // $maxId = 0;
  // foreach ($singers as $singer) {
  //   if ($singer['id'] > $maxId) {
  //     $maxId = $singer['id'];
  //   }
  // }
  $max_id = max(array_column($singers, 'id'));
  return $max_id + 1;
}

function isAdmin()
{
  if (isset($_SESSION['role'])) {
    return "admin" === $_SESSION['role'];
  } else {
    return false;
  }
}
function isEditor()
{
  if (isset($_SESSION['role'])) {
    return "editor" === $_SESSION['role'];
  } else {
    return false;
  }
}

// function printArray() {
//   $data = file_get_contents(DB_SINGERS);
//   $singers = unserialize($data);
//   print_r($singers);
// }
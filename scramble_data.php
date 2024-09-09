<?php
include_once "scramble_data_f.php";
$task = isset($_GET['task']) ?? "encode";
$original_data = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
$scramble_data = isset($_REQUEST['result']) ? $_REQUEST['result'] : "";
$key = isset($_GET['key']) ? $_GET['key'] : "";


if("generate-key" == $task){
  $key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
  $generated_key = str_split($key);
  shuffle($generated_key);
  $key = implode("", $generated_key);
  $data = isset($_REQUEST['data'])? $_REQUEST['data'] : "";
  if($data !== "") {
    $scramble_data = scrambleData($data, $key);
  }
} elseif(isset($_POST['key']) && $_POST['key'] != ""){
  $key = $_POST['key'];
}



if("encode" == $task){
  $data = isset($_REQUEST['data'])? $_REQUEST['data'] : "";
  if($data !== "") {
    $scramble_data = scrambleData($data, $key);
  }
}

if("decode" == $task){
  $data = isset($_REQUEST['data'])? $_REQUEST['data'] : "";
  if($data !== "") {
    $scramble_data = decodeData($data, $key);
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Scrambler</title>
  <script src="https://cdn.tailwindcss.com"></script>  
</head>
<body>
  <div class="lg:w-2/5 md:w-3/4 m-auto mt-5">
    <h2 class="text-2xl font-bold text-center">Data Scrambler</h2>
    <p class="text-sm text-center text-gray-500">Use this application to scramble your data</p>

    <div class="text-center mt-5">
      <a href="/php/html-webpage/scramble_data/scramble_data.php?task=encode" class="text-[orangered] hover:text-black">Encode</a> |
      <a href="/php/html-webpage/scramble_data/scramble_data.php?task=decode" class="text-[orangered] hover:text-black">Decode</a> | 
      <a href="/php/html-webpage/scramble_data/scramble_data.php?task=generate-key&data=<?php echo urlencode($original_data); ?>&result=<?php echo urlencode($scramble_data); ?>" class="text-[orangered] hover:text-black">Generate Code</a>
    </div>

    <form method="POST" action="scramble_data.php<?php if("decode" === $task) { echo "?task=decode"; }; ?>" class="mt-10">

      <div class="mb-4">
        <label for="key" class="block text-gray-700 text-sm font-bold mb-2">Key</label>
        <input type="text" id="key" name="key" class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-[orangered] outline-none" placeholder="Enter Key" <?php displayKey($key); ?> >
      </div>

      <div class="mb-4">
        <label for="data" class="block text-gray-700 text-sm font-bold mb-2">Simple Data</label>
        <textarea type="text" id="data" name="data" class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-[orangered] outline-none" placeholder="Enter Your Data" ><?php echo htmlspecialchars($original_data); ?></textarea>
      </div>

      <div class="mb-4">
        <label for="result" class="block text-gray-700 text-sm font-bold mb-2">Scramble Data</label>
        <textarea type="text" id="result" name="result" class="appearance-none block w-full bg-gray-200 text-gray-600 border border-gray-400 rounded-lg py-2 px-4 mb-3 focus:border-[orangered] outline-none" placeholder="Result Will Display Here"><?php echo htmlspecialchars($scramble_data);?></textarea>
      </div>

      
      <div class="flex items-center justify-center">
        <input type="submit" value="Generate Scramble Data" class="py-2 px-5 bg-[orangered] text-white hover:bg-black transition-all duration-300 cursor-pointer">
      </div>

    </form>
  </div>

  <!-- Footer -->
   <footer class="fixed bottom-0 left-0 py-4 w-full bg-white">
     <p class="text-center text-gray-500 text-sm">Copyright &copy; 2024 Data Scrambler. All rights reserved Tanin Rahman.</p> 
    </footer>
  <!-- Footer -->

</body>
</html>
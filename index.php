<?php

$conn = mysqli_connect("localhost", "root", "", "slightlink");

if (isset($_GET['id']) && strlen($_GET['id']) == 5) {
  $url = mysqli_real_escape_string($conn, $_GET['id']);
  $initial_url = "";
  $clicks = 0;
  $query = mysqli_query($conn, "SELECT `initial_url`, `clicks` FROM `urls` WHERE `final_url` = '$url'");
  while ($row = mysqli_fetch_assoc($query)) {
    $initial_url = $row['initial_url'];
    $clicks = $row['clicks'] + 1;
  }
  $new_query = mysqli_query($conn, "UPDATE `urls` SET `clicks`= $clicks WHERE `final_url` = '$url'");
  if (!($new_query)) {
    echo "Unable to update...";
  }
  header("location: $initial_url");
  die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
  <title>SlightLink - Free Link Shortner</title>
</head>

<body>

  <header class="text-gray-600 body-font">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
      <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000000"><path d="M440-280H280q-83 0-141.5-58.5T80-480q0-83 58.5-141.5T280-680h160v80H280q-50 0-85 35t-35 85q0 50 35 85t85 35h160v80ZM320-440v-80h320v80H320Zm200 160v-80h160q50 0 85-35t35-85q0-50-35-85t-85-35H520v-80h160q83 0 141.5 58.5T880-480q0 83-58.5 141.5T680-280H520Z" /></svg>
        <span class="ml-1 text-xl">SlightLink</span>
      </a>
      <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
        <a href="https://www.github.com/anurag-dwivedi-live" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0 ml-4">
          <svg aria-hidden="true" class="octicon octicon-mark-github" height="20" version="1.1" viewBox="0 0 16 16" width="20"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
          <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-1" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
        </a>
      </nav>
    </div>
  </header>

  <main>

    <section class="text-gray-600 body-font relative">
      <div class="container px-5 py-16 mx-auto">
        <div class="flex flex-col text-center w-full mb-12">
          <h1 class="sm:text-3xl text-2xl font-bold title-font mb-4 text-cyan-500">Short URL</h1>
          <p class="lg:w-2/3 font-medium text-lg mx-auto leading-relaxed">Paste the URL to be shortened</p>
        </div>
        <div class="lg:w-1/2 md:w-2/3 mx-auto">
          <form class="flex flex-wrap -m-2 justify-center">
            <div class="p-2 w-full sm:w-1/2">
              <div class="relative">
                <input type="text" id="url" name="url" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" placeholder="Enter the URL here">
              </div>
            </div>
            <div class="p-2">
              <button type="submit" id="submitBtn" class="flex mx-auto text-white bg-cyan-500 border-0 py-2 px-8 focus:outline-none hover:bg-cyan-600 rounded text-lg text-medium">Shorten URL</button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="text-gray-600 body-font mb-5">
      <div id="shortedLinks" class="container px-5 mx-auto">
      <?php 

        $cookies = [];
        foreach ($_COOKIE as $key => $value) {
          $cookies[$key] = $value;
        }
        $cookies = array_reverse($cookies, true);      
        foreach ($cookies as $key => $value) {
        
        ?>
        <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
          <div class="p-2 w-full">
            <div class="bg-gray-100 rounded flex p-4 h-full justify-between flex flex-wrap">
              <div class="flex overflow-hidden">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" class="text-cyan-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                  <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                  <path d="M22 4L12 14.01l-3-3"></path>
                </svg>
                <span class="title-font font-medium"><?= urldecode($value) ?></span>
              </div>
              <div class="flex mt-3 sm:mt-0">
                <div class="title-font font-medium mr-2 shorted-link text-blue-500">localhost/link/<?= $key ?></div>
                <span class="hover:cursor-pointer copyLink" data-clipboard-target="+ .shorted-link">
                  <svg class="copy-svg" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z" /></svg>
                  <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="display:none;">
                  <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/>
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>
        <?php 
          }
        ?>
      </div>

    </section>

  </main>
</body>
<script src="script.js"></script>
</html>
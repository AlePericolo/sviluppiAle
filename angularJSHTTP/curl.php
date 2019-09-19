<?php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://www.w3schools.com/angular/customers.php');
$result = curl_exec($curl);
return $result;


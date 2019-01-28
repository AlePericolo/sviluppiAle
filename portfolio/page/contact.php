<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 28/01/19
 * Time: 11.34
 */

$data = json_decode(file_get_contents("php://input"));

//email information
$to = "ale.pericolo@gmail.com";

$userEmail = $data->email;
$subject = $data->subject;
$message = $data->message;

$headers = 'From: ' . $userEmail . "\r\n" .
           'Reply-To:'.$userEmail . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

//php mail function to send email on your email address
return mail($to, $subject, $message, $headers);
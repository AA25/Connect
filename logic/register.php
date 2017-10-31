<?php

require "../includes/init.inc.php";
$pdo = get_db();

echo $_POST['firstName']."<br>";
echo $_POST['lastName']."<br>";
echo $_POST['dob']."<br>";
echo $_POST['languages']."<br>";
echo $_POST['email']."<br>";
echo $_POST['password']."<br>";
echo $_POST['devBio']."<br>";
echo $_POST['phone']."<br>";

if(1 == 1){
  //isset($_POST['firstName'])
  //register
  //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $r = $pdo->prepare(
    "insert into
    developers (firstName, lastName, dob, languages, email, password, devBio, phone)
    values(:firstName, :lastName, :dob, :languages, :email, :password, :devBio, :phone);"
  );
  $r->execute([
    'firstName' => $_POST['firstName'],
    'lastName' => $_POST['lastName'],
    'dob' => $_POST['dob'],
    'languages' => $_POST['languages'],
    'email' => $_POST['password'],
    'password' => $_POST['email'],
    'devBio' => $_POST['devBio'],
    'phone' => $_POST['phone']
  ]);

  //header('Location: ../index.php');

}
?>

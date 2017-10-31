<?php 
    // important to tell your browser what we will be sending
    header('Content-type: application/json; charset=utf-8');
    
    require "../includes/init.inc.php";
    $pdo = get_db();

    if (isset($_POST['email']) && isset($_POST['password'])){
        
        //Attempt to retrieve user details from provided login details
        $r = $pdo->prepare(
            "select firstName, lastName, dob, languages, email, devBio, phone from developers where email = :email and password = :password"
        );
        $r->execute([
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ]);
        //If num of rows returned is greater than 0 we know we have a result
        if($r->rowCount() > 0){
            $devInfo = [];
            foreach($r as $dev){
                array_push($devInfo, $dev['firstName'], $dev['lastName'], $dev['dob'], $dev['languages'], $dev['email'], $dev['devBio'], $dev['phone']);
            }
            echo json_encode($devInfo);
        }else{
            echo json_encode(array('Error' => 'Incorrect login details'));
        }
    }else{
        echo json_encode(array('Error' => 'Incorrect login details'));
    }

// if (isset($_POST['email']) && isset($_POST['password'])){
//     try{
//         //Attempt to retrieve user details from provided login details
//         $r = $pdo->prepare(
//             "select firstName, lastName, dob, languages, email, devBio, phone from users where email = :email and password = :password"
//         );
//         $r->execute([
//             'email' => $_POST['email'],
//             'password' => $_POST['password'],
//         ]);
//         echo ($r->rowCount() > 0);
//     }catch(Exception $e){
//         echo $e;
//     }
// }else{
//     echo 'empty field';
// }
?>


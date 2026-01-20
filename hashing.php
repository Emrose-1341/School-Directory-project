<?php
//get users.json in order to hash stuff
$username_pass = json_decode(file_get_contents('users.json'), true);

//Go through every line (user) one by one
foreach ($username_pass as &$user) {
	//For each users password hash it based on the original
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
}
//Create new file with the hashed versions 
file_put_contents('users_hashed.json', json_encode($username_pass));
echo "Worked:) Yippie";
?>
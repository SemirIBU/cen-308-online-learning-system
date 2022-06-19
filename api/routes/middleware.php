<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/* middleware for students */
Flight::route('/student/*', function(){
  $headers = getallheaders();
  try {
    $user = (array)JWT::decode($headers['Authentication'], new Key(Config::JWT_SECRET, 'HS256'));   
    if ($user['r'] != "student" and $user['r'] != "admin"){
      throw new Exception("Student access required", 403);
    }
    Flight::set('user', $user);
    return TRUE;
  } catch (\Exception $e) {
    Flight::json(["message" => $e->getMessage()], 401);
    die;
  }
});

/* middleware for professors */
Flight::route('/professor/*', function(){
  $headers = getallheaders();
  try {
    $user = (array)JWT::decode($headers['Authentication'], new Key(Config::JWT_SECRET, 'HS256'));   
    if ($user['r'] != "professor" and $user['r'] != "admin"){
      throw new Exception("Professor access required", 403);
    }
    Flight::set('user', $user);
    return TRUE;
  } catch (\Exception $e) {
    Flight::json(["message" => $e->getMessage()], 401);
    die;
  }
});
/* middleware for admins */
Flight::route('/admin/*', function(){
  $headers = getallheaders();
  try {
    $user = (array)JWT::decode($headers['Authentication'], new Key(Config::JWT_SECRET, 'HS256'));   
    if ($user['r'] != "admin"){
      throw new Exception("Admin access required", 403);
    }
    Flight::set('user', $user);
    return TRUE;
  } catch (\Exception $e) {
    Flight::json(["message" => $e->getMessage()], 401);
    die;
  }
});

?>
<?php
require_once dirname(__FILE__)."/BaseDao.class.php";

class StudentDao extends BaseDao{

  public function __construct(){
    parent::__construct("students");
  }

  public function get_student_by_email($email){
    return $this->query_unique("SELECT * FROM students WHERE email = :email", ["email" => $email]);
  }

  public function update_student_by_email($email, $student){
    $this->execute_update("students", $email, $student, "email");
  }

  public function get_student_by_token($token){
    return $this->query_unique("SELECT * FROM students WHERE token = :token", ["token" => $token]);
  }

}

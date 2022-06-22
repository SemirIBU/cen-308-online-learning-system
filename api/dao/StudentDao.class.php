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
  public function get_student_profile_by_aid($aid)
  {
    return $this->query_unique("SELECT * FROM students WHERE account_id = :aid", ["aid" => $aid]);
  }

  public function get_students($search, $offset, $limit, $order){
    list($order_column, $order_direction)= self::parse_order($order);
    
      return $this->query("SELECT * 
                           FROM students 
                           WHERE LOWER(name) LIKE CONCAT('%', :name, '%') AND LOWER(role) LIKE 'student'
                           ORDER BY {$order_column} {$order_direction}
                           LIMIT ${limit} OFFSET ${offset}", 
                           ["name" => strtolower($search)]);
  }

  public function get_all_students($offset, $limit,$order){
    list($order_column, $order_direction)= self::parse_order($order);

    return $this->query("SELECT * 
                           FROM students 
                           WHERE LOWER(role) LIKE 'student'
                           ORDER BY {$order_column} {$order_direction}
                           LIMIT ${limit} OFFSET ${offset}",[]);
  }
}

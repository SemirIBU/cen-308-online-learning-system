<?php
require_once dirname(__FILE__)."/BaseDao.class.php";

class CourseDao extends BaseDao{

  public function __construct(){
    parent::__construct("courses");
  }

  public function get_course_by_account_and_id($account_id, $id){
    return $this->query_unique("SELECT * FROM courses WHERE account_id = :account_id AND id = :id", ["account_id" => $account_id, "id" => $id]);
  }

  public function count_courses(){
    $params=[];
    $query = "SELECT COUNT(*) AS total FROM courses";
    return $this->query_unique($query, $params);
  }

 
  public function get_courses($offset, $limit, $search, $order){
    list($order_column, $order_direction) = self::parse_order($order);
    $params = [];
    $query = "SELECT * ";
    $query .= "FROM courses
               WHERE status = 'active' ";

    if (isset($search)){
      $query .= "AND ( LOWER(name) LIKE CONCAT('%', :search, '%') OR LOWER(description) LIKE CONCAT('%', :search, '%'))";
      $params['search'] = strtolower($search);
    }
    
      $query .="ORDER BY ${order_column} ${order_direction} ";
      $query .="LIMIT ${limit} OFFSET ${offset}";

      return $this->query($query, $params);
    

  }
}

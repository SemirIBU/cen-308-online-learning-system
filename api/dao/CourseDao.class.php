<?php
require_once dirname(__FILE__)."/BaseDao.class.php";

class CourseDao extends BaseDao{

  public function __construct(){
    parent::__construct("courses");
  }
  
  public function get_courses($search, $offset, $limit, $order){
    list($order_column, $order_direction)= self::parse_order($order);
    
      return $this->query("SELECT * 
                           FROM courses 
                           WHERE LOWER(name) LIKE CONCAT('%', :name, '%') 
                           ORDER BY {$order_column} {$order_direction}
                           LIMIT ${limit} OFFSET ${offset}", 
                           ["name" => strtolower($search)]);
  }

  public function add($course){
    try{
      return parent::add($course);
    } catch(\Exception $e){
      if(str_contains($e->getMessage(), 'courses.uq_course_name')){
        throw new Exception("Course with the same name already exists", 400, $e);
      }else{
        throw $e;
      }
    }
  }


}

<?php

require_once dirname(__FILE__). '/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/CourseDao.class.php';

class CourseService extends BaseService{

  public function __construct(){
    $this->dao = new CourseDao();
  }

  public function get_course_by_account_and_id($account_id, $id){
    return $this->dao->get_course_by_account_and_id($account_id, $id);
  }

  public function get_courses($offset, $limit, $order){
    return $this->dao->get_courses($offset, $limit, $order);
  }
  public function get_all_courses($offset, $limit, $order){
    return $this->dao->get_all_courses($offset, $limit, $order);
  }
  
  public function count_courses(){
    return $this->dao->count_courses();
  }



  public function add_course($student, $course){
    try {
      $data = [
        "name" => $course["name"],
        "description" => $course["description"],        
        "account_id" => $student['aid']
      ];
      return parent::add($data);
    } catch (\Exception $e) {
      if (str_contains($e->getMessage(), 'courses.uq_course_name')) {
        throw new Exception("Course with same name already exists", 400, $e);
      }else{
        throw new Exception($e->getMessage(), 400, $e);
      }
    }
  }

  public function update_course($id, $course){
    
    return $this->update($id, $course);
  }
}
?>
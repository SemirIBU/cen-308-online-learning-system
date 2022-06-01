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

  public function get_courses($account_id, $offset, $limit, $search, $order, $total = FALSE){
    return $this->dao->get_courses($account_id, $offset, $limit, $search, $order, $total);
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

  public function update_course($student, $id, $course){
    $db_template = $this->dao->get_by_id($id);
    if ($db_template['account_id'] != $student['aid']){
      throw new Exception("Invalid course", 403);
    }
    return $this->update($id, $course);
  }
}
?>
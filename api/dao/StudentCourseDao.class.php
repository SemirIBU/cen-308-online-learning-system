<?php
require_once dirname(__FILE__)."/BaseDao.class.php";

class StudentCourseDao extends BaseDao{

  public function __construct(){
    parent::__construct("student_course");
  }



}

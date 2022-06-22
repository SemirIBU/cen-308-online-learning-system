<?php
require_once dirname(__FILE__) . "/BaseDao.class.php";

class CourseDao extends BaseDao
{

  public function __construct()
  {
    parent::__construct("courses");
  }

  public function get_course_by_account_and_id($account_id, $id)
  {
    return $this->query_unique("SELECT * FROM courses WHERE account_id = :account_id AND id = :id", ["account_id" => $account_id, "id" => $id]);
  }

  public function count_courses()
  {
    $params = [];
    $query = "SELECT COUNT(*) AS total FROM courses";
    return $this->query_unique($query, $params);
  }


  public function get_courses($studentid,$offset,$limit,$order)
  {
    list($order_column, $order_direction)= self::parse_order($order);
    $params = ["student_id"=>$studentid];
    $query = "SELECT courses.id, courses.name
              FROM courses
              JOIN student_course ON courses.id = student_course.course_id
              JOIN students ON student_course.student_id = students.id
              WHERE student_course.student_id = :student_id
              ORDER BY {$order_column} {$order_direction}
              LIMIT ${limit} OFFSET ${offset}";

    return $this->query($query, $params);
  }
}

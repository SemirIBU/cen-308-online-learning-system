<?php
require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/CourseDao.class.php';

class CourseService extends BaseService{

    public function __construct()
    {
        $this->dao = new CourseDao();
    }

    public function get_courses($search, $offset, $limit, $order){
        if($search){
            return $this->dao->get_courses($search, $offset, $limit, $order);
        }else{
            return $this->dao->get_all($offset, $limit, $order);
        }
    }

}
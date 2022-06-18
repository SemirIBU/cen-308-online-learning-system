<?php
require_once dirname(__FILE__) . '/BaseService.class.php';
require_once dirname(__FILE__) . '/../dao/ProfessorDao.class.php';
require_once dirname(__FILE__) . '/../dao/AccountDao.class.php';

require_once dirname(__FILE__) . '/../config.php';



class ProfessorService extends BaseService
{

  private $accountDao;

  public function __construct()
  {
    $this->dao = new professorDao();
    $this->accountDao = new AccountDao();
  }

}

<?php
require_once dirname(__FILE__) . '/BaseService.class.php';
require_once dirname(__FILE__) . '/../dao/StudentDao.class.php';
require_once dirname(__FILE__) . '/../dao/AccountDao.class.php';
require_once dirname(__FILE__) . '/../dao/StudentCourseDao.class.php';

require_once dirname(__FILE__) . '/../clients/SMTPClient.class.php';
require_once dirname(__FILE__) . '/../config.php';



class StudentService extends BaseService
{

  private $accountDao;
  private $studentCourseDao;

  public function __construct()
  {
    $this->dao = new studentDao();
    $this->accountDao = new AccountDao();
    $this->smtpClient = new SMTPClient();
    $this->studentCourseDao = new StudentCourseDao();
  }

  public function reset($student)
  {
    $db_student = $this->dao->get_student_by_token($student['token']);

    if (!isset($db_student['id'])) throw new Exception("Invalid token", 400);

    if (strtotime(date(Config::DATE_FORMAT)) - strtotime($db_student['token_created_at']) > 300) throw new Exception("Token expired", 400);

    $this->dao->update($db_student['id'], ['password' => md5($student['password']), 'token' => NULL]);

    return $db_student;
  }
  public function forgot($student)
  {
    $db_student = $this->dao->get_student_by_email($student['email']);

    if (!isset($db_student['id'])) throw new Exception("student doesn't exist", 400);

    $currentDate =  date("Y-m-d H:i:s");
    if (strtotime($currentDate) - strtotime($db_student['token_created_at']) < 300) throw new Exception("Token already sent", 400);

    $db_student = $this->update($db_student['id'], ['token' => md5(random_bytes(16)), 'token_created_at' => date(Config::DATE_FORMAT)]);

    if (Config::ENVIRONMENT() != 'local') $this->smtpClient->send_user_recovery_token($db_student);
  }

  public function login($student)
  {
    $db_student = $this->dao->get_student_by_email($student['email']);

    if (!isset($db_student['id'])) throw new Exception("student doesn't exist", 400);

    if ($db_student['status'] != 'ACTIVE') throw new Exception("Account not active", 400);

    $account = $this->accountDao->get_by_id($db_student['account_id']);
    if (!isset($account['id']) || $account['status'] != 'ACTIVE') throw new Exception("Account not active", 400);

    if ($db_student['password'] != md5($student['password'])) throw new Exception("Invalid password", 400);

    return $db_student;
  }

  public function register($student)
  {

    try {
      $this->dao->beginTransaction();
      $account = $this->accountDao->add([
        "status" => "PENDING",
        "created_at" => date(Config::DATE_FORMAT)
      ]);

      $student = parent::add([
        "account_id" => $account['id'],
        "first_name" => $student['first_name'],
        "last_name" => $student['last_name'],
        "email" => $student['email'],
        "password" => md5($student['password']),
        "phone" => $student['phone'],
        "country" => $student['country'],
        "city" => $student['city'],
        "zip_code" => $student['zip_code'],
        "address" => $student['address'],
        "status" => "PENDING",
        "created_at" => date(Config::DATE_FORMAT),
        "token" => md5(random_bytes(16)),
        "role" => "student",
      ]);
      $this->dao->commit();
    } catch (\Exception $e) {
      $this->dao->rollBack();
      if (str_contains($e->getMessage(), 'students.uq_student_email')) {
        throw new Exception("Account with the same email already exists", 400, $e);
      } else {
        throw $e;
      }
    }

    if (Config::ENVIRONMENT() != 'local') $this->smtpClient->send_register_user_token($student);

    return $student;
  }

  public function confirm($token)
  {
    $student = $this->dao->get_student_by_token($token);

    if (!isset($student['id'])) throw new Exception("Invalid token", 400);

    $this->dao->update($student['id'], ["status" => "ACTIVE", "token" => NULL]);
    $this->accountDao->update($student['account_id'], ["status" => "ACTIVE"]);

    return $student;
  }

  public function get_students($search, $offset, $limit, $order)
  {
      return $this->dao->get_students($search, $offset, $limit, $order);
  }

  public function get_all_students($offset,$limit,$order){
    return $this->dao->get_all_students($offset, $limit, $order);
  }

  public function enrol($studentid,$courseid){

    $studentCourse = $this->studentCourseDao->add([
      "course_id" => $courseid['course_id'],
      "student_id" => $studentid,      
    ]);

    return $studentCourse;
  }
  public function unenrol($studentid,$courseid){

    $studentCourse = $this->studentCourseDao->unenrol($studentid,$courseid['course_id']);

    return $studentCourse;
  }

}

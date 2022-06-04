<?php
require_once dirname(__FILE__) . '/BaseService.class.php';
require_once dirname(__FILE__) . '/../dao/studentDao.class.php';
require_once dirname(__FILE__) . '/../dao/AccountDao.class.php';

require_once dirname(__FILE__) . '/../clients/SMTPClient.class.php';
require_once dirname(__FILE__) . '/../config.php';



class studentservice extends BaseService
{

  private $accountDao;

  public function __construct()
  {
    $this->dao = new studentDao();
    $this->accountDao = new AccountDao();
    $this->smtpClient = new SMTPClient();
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

    if (strtotime(date(Config::DATE_FORMAT)) - strtotime($db_student['token_created_at']) < 300) throw new Exception("Token already sent", 400);

    //generate token and save it to db
    $db_student = $this->update($db_student['id'], ['token' => md5(random_bytes(16)), 'token_created_at' => date(Config::DATE_FORMAT)]);

    //send email
    if (Config::ENVIRONMENT() != 'local') $this->smtpClient->send_student_recovery_token($db_student);
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
    if (!isset($student['account'])) throw new Exception("Account field is required");

    try {
      $this->dao->beginTransaction();
      $account = $this->accountDao->add([
        "name" => $student['account'],
        "status" => "PENDING",
        "created_at" => date(Config::DATE_FORMAT)
      ]);

      $student = parent::add([
        "account_id" => $account['id'],
        "name" => $student['name'],
        "email" => $student['email'],
        "password" => md5($student['password']),
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

    if (Config::ENVIRONMENT() != 'local') $this->smtpClient->send_register_student_token($student);

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
}
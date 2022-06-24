<?php
use Firebase\JWT\JWT;

require_once dirname(__FILE__) . '/../vendor/autoload.php';

//include dao classes
require_once dirname(__FILE__) . '/services/AccountService.class.php';
require_once dirname(__FILE__) . '/services/StudentService.class.php';
require_once dirname(__FILE__) . '/services/CourseService.class.php';
require_once dirname(__FILE__) . '/services/ProfessorService.class.php';
require_once dirname(__FILE__) . '/config.php';

require_once dirname(__FILE__).'/clients/CDNClient.class.php';

if (Config::ENVIRONMENT() == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

Flight::set('flight.log_errors', TRUE);
if(Config::ENVIRONMENT()!='local'){
Flight::map('error', function (Exception $ex) {
    Flight::json(["message" => $ex->getMessage()], $ex->getCode() ? $ex->getCode() : 500);
});
}

Flight::map('query', function ($name, $default_value = NULL) {
    $request = Flight::request();
    $query_param = @$request->query->getData()[$name];
    $query_param = $query_param ? $query_param : $default_value;
    return urldecode($query_param);
});


/*register Business Logic layer*/
Flight::register('accountService', 'AccountService');
Flight::register('studentService', 'StudentService');
Flight::register('courseService', 'CourseService');
Flight::register('professorService', 'ProfessorService');

/*register CDN*/
Flight::register('cdnClient', 'CDNClient');


/* utility function for getting header parameters */
Flight::map('header', function ($name) {
    $headers = getallheaders();
    return @$headers[$name];
});

/* utility function for generating JWT token */
Flight::map('jwt', function ($user) {
    $jwt = JWT::encode(["exp" => (time() + Config::JWT_TOKEN_TIME), "id" => $user["id"], "aid" => $user["account_id"], "r" => $user["role"]], Config::JWT_SECRET, 'HS256');
    return ["token" => $jwt];
});

Flight::route('GET /docs.json', function () {
    $openapi = @\OpenApi\scan(dirname(__FILE__)."/routes");
    header('Content-Type: application/json');
    echo $openapi->toJson();
});

Flight::route('GET /',function(){
    Flight::redirect('/docs');
});



/*include routes*/
require_once dirname(__FILE__) . '/routes/middleware.php';
require_once dirname(__FILE__) . '/routes/accounts.php';
require_once dirname(__FILE__) . '/routes/students.php';
require_once dirname(__FILE__) . '/routes/courses.php';
require_once dirname(__FILE__) . '/routes/professors.php';
require_once dirname(__FILE__) . '/routes/admins.php';
require_once dirname(__FILE__) . '/routes/cdn.php';



Flight::start();

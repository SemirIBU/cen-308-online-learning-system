<?php
/**
 * @OA\Post(path="/register", tags={"login"},     
 *     @OA\RequestBody(
 *          description="Basic student info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="first_name", required="true", type="string", example="First name", description="First of the student"),
 *    				 @OA\Property(property="last_name", required="true", type="string", example="Last name", description="Last name of the student"),
 *    				 @OA\Property(property="email", required="true", type="string", example="admir.sahman@stu.ibu.edu.ba", description="student's email address"),
 *    				 @OA\Property(property="password", required="true", type="string", example="12345", description="Pasword"),
 *    				 @OA\Property(property="phone", required="true", type="string", example="+38762616940", description="phone"),
 *    				 @OA\Property(property="country", required="true", type="string", example="BiH", description="country"),
 *    				 @OA\Property(property="city", required="true", type="string", example="Sarajevo", description="city"),
 *    				 @OA\Property(property="address", required="true", type="string", example="Podigmanska 4", description="address"),
 *    				 @OA\Property(property="zip_code", required="true", type="string", example="71210", description="zip_code"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that student has been created.")
 *
 * )
 */
Flight::route('POST /register', function(){
  $data = Flight::request()->data->getData();
  Flight::studentService()->register($data);
  
  Flight::json(["message" => "Confirmation email has been sent, please check your inbox"]);
});

/**
 * @OA\Post(path="/login", tags={"login"},     
 *     @OA\RequestBody(
 *          description="Basic student info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="email", required="true", type="string", example="admir.sahman@stu.ibu.edu.ba", description="student's email address"),
 *    				 @OA\Property(property="password", required="true", type="string", example="12345", description="Pasword"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that student has been created.")
 *
 * )
 */
Flight::route('POST /login', function(){
  Flight::json(Flight::jwt(Flight::studentService()->login(Flight::request()->data->getData())));
});

/**
 * @OA\Post(path="/forgot", tags={"login"}, description="Send recovery URL to students email address",
 *     @OA\RequestBody(
 *          description="Basic student info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="email", required="true", type="string", example="admir.sahman@stu.ibu.edu.ba", description="student's email address"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that recovery link has been sent.")
 *
 * )
 */
Flight::route('POST /forgot', function(){
  $data = Flight::request()->data->getData();
  Flight::studentService()->forgot($data);
  Flight::json(["message" => "Recovery link has been sent to your email address"]);
});

/**
 * @OA\Post(path="/reset", tags={"login"}, description="Reset students password using recovery token",
 *     @OA\RequestBody(
 *          description="Basic student info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="token", required="true", type="string", example="1561494946984984", description="Recovery token"),
 *    				 @OA\Property(property="password", required="true", type="string", example="123456", description="New password"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that student has changed password.")
 *
 * )
 */
Flight::route('POST /reset', function(){
  Flight::json(Flight::jwt(Flight::studentService()->reset(Flight::request()->data->getData())));
});

/**
 * @OA\Get(path="/confirm/{token}", tags={"login"},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", allowReserved=true, name="token", default=12345, description="Temporary token for activating account"),
 *     @OA\Response(response="200", description="Message on successful activation"),
 * )
 */
Flight::route('GET /confirm/@token', function($token){
  Flight::studentService()->confirm($token);
  Flight::render('account-confirmed');
});

/**
 * @OA\Get(path="/student/profile", tags={"student"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch student profile")
 * )
 */
Flight::route('GET /student/profile', function(){
  Flight::json(Flight::studentService()->get_by_aid(Flight::get('user')['aid']));
});
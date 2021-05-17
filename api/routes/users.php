<?php
/**
 * @OA\Post(path="/register", tags={"login"},     
 *     @OA\RequestBody(
 *          description="Basic user info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="account", required="true", type="string", example="My test account", description="Name of the account"),
 *    				 @OA\Property(property="name", required="true", type="string", example="First Last name", description="Name of the user"),
 *    				 @OA\Property(property="email", required="true", type="string", example="myemail@gmail.com", description="User's email address"),
 *    				 @OA\Property(property="password", required="true", type="string", example="12345", description="Pasword"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that user has been created.")
 *
 * )
 */
Flight::route('POST /register', function(){
  $data = Flight::request()->data->getData();
  Flight::userService()->register($data);
  
  Flight::json(["message" => "Confirmation email has been sent, please check your inbox"]);
});

/**
 * @OA\Post(path="/login", tags={"login"},     
 *     @OA\RequestBody(
 *          description="Basic user info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="email", required="true", type="string", example="myemail@gmail.com", description="User's email address"),
 *    				 @OA\Property(property="password", required="true", type="string", example="12345", description="Pasword"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that user has been created.")
 *
 * )
 */
Flight::route('POST /login', function(){
  Flight::json(Flight::jwt(Flight::userService()->login(Flight::request()->data->getData())));
});

/**
 * @OA\Post(path="/forgot", tags={"login"}, description="Send recovery URL to users email address",
 *     @OA\RequestBody(
 *          description="Basic user info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="email", required="true", type="string", example="myemail@gmail.com", description="User's email address"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that recovery link has been sent.")
 *
 * )
 */
Flight::route('POST /forgot', function(){
  $data = Flight::request()->data->getData();
  Flight::userService()->forgot($data);
  Flight::json(["message" => "Recovery link has been sent to your email address"]);
});

/**
 * @OA\Post(path="/reset", tags={"login"}, description="Reset users password using recovery token",
 *     @OA\RequestBody(
 *          description="Basic user info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="token", required="true", type="string", example="1561494946984984", description="Recovery token"),
 *    				 @OA\Property(property="password", required="true", type="string", example="123456", description="New password"),
 *             )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Message that user has changed password.")
 *
 * )
 */
Flight::route('POST /reset', function(){
  Flight::json(Flight::jwt(Flight::userService()->reset(Flight::request()->data->getData())));
});

/**
 * @OA\Get(path="/confirm/{token}", tags={"login"},
 *     @OA\Parameter(@OA\Schema(type="string"), in="path", allowReserved=true, name="token", default=12345, description="Temporary token for activating account"),
 *     @OA\Response(response="200", description="Message on successful activation"),
 * )
 */
Flight::route('GET /confirm/@token', function($token){
  Flight::json(Flight::jwt(Flight::userService()->confirm($token)));
});

Flight::route('GET /@id', function($id){
  $user = Flight::userService()->get_by_id($id);
  Flight::json($user);
});
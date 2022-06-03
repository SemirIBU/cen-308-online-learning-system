<?php
/* Swagger documentation*/

/**
 * @OA\Get(
 *     path="/admin/accounts", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string from accounts. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for returned elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List accounts from database")
 * )
 */
Flight::route('GET /admin/accounts',function(){    
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 25);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");
    Flight::json(Flight::accountService()->get_accounts($search, $offset, $limit, $order));
});

/**
 * @OA\Get(path="/admin/accounts/{id}", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", allowReserved=true, name="id", default=1, description="id of account"),
 *     @OA\Response(response="200", description="Account that has been added into database with ID assigned."),
 * )
 */
Flight::route('GET /admin/accounts/@id',function($id){     
    Flight::json(Flight::accountService()->get_by_id($id));
});

/**
 * @OA\Post(path="/admin/accounts", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},    
 *     @OA\RequestBody(
 *          description="Basic account info", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="Test account", description="Name of the account"),
 *    				 @OA\Property(property="status", type="string", example="ACTIVE", description="Account status"),
 *              )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Add account")
 *
 * )
 */
Flight::route('POST /admin/accounts/',function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::accountService()->add($data));
});

/**
 * @OA\Put(path="/admin/accounts/{id}", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(@OA\Schema(type="integer"), in="path", name="id", default=1),
 *     @OA\RequestBody(
 *          description="Basic account info that is going to be updated", required="true",
 *          @OA\MediaType(
 *    			mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="Test account", description="Name of the account"),
 *    				 @OA\Property(property="status", type="string", example="ACTIVE", description="Account status"),
 *              )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Update account based on id")
 * )
 */
Flight::route('PUT /admin/accounts/@id',function($id){    
    $data = Flight::request()->data->getData();                
    Flight::json(Flight::accountService()->update($id, $data));
});

/**
 * @OA\Get(path="/student/account", tags={"student", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch student account")
 * )
 */
Flight::route('GET /student/account', function(){
    Flight::json(Flight::accountService()->get_by_id(Flight::get('user')['aid']));
  });
  

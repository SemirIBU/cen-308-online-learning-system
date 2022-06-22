<?php
/**
 * @OA\Get(path="/student/courses", tags={"student", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string for accounts. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List courses for student")
 * )
 */
Flight::route('GET /student/courses', function(){  
  $offset = Flight::query('offset', 0);
  $limit = Flight::query('limit', 25);
  $order = Flight::query('order', '-id');

  $studentid = Flight::studentService()->get_by_aid(Flight::get('user')['aid'])['id'];
  Flight::json(Flight::courseService()->get_courses($studentid,$offset, $limit,$order));
});

/**
 * @OA\Get(path="/student/available", tags={"student", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string for accounts. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List available courses for student")
 * )
 */
Flight::route('GET /student/available', function(){  
  $offset = Flight::query('offset', 0);
  $limit = Flight::query('limit', 25);
  $order = Flight::query('order', '-id');

  $studentid = Flight::studentService()->get_by_aid(Flight::get('user')['aid'])['id'];
  Flight::json(Flight::courseService()->get_available_courses($studentid,$offset, $limit,$order));
});


/**
 * @OA\Get(path="/student/courses/{id}", tags={"student", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of course"),
 *     @OA\Response(response="200", description="Fetch individual course")
 * )
 */
Flight::route('GET /student/courses/@id', function($id){
  Flight::json(Flight::courseService()->get_course_by_account_and_id(Flight::get('user')['aid'], $id));
});

/**
 * @OA\Post(path="/student/courses", tags={"student", "courses"}, security={{"ApiKeyAuth": {}}},
 *   @OA\RequestBody(description="Basic course info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="name",	description="Name of the course" ),
 *    				 @OA\Property(property="description", required="true", type="string", example="description",	description="Description of the course" )    				 
 *          )
 *       )
 *     ),
 *  @OA\Response(response="200", description="Saved course")
 * )
 */
Flight::route('POST /student/courses', function(){
  Flight::json(Flight::courseService()->add_course(Flight::get('user'), Flight::request()->data->getData()));
});


/**
 * @OA\Get(path="/admin/courses", tags={"admin", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for return elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List all courses")
 * )
 */
Flight::route('GET /admin/courses', function(){
  $offset = Flight::query('offset', 0);
  $limit = Flight::query('limit', 25);
  $order = Flight::query('order', '-id');

  Flight::json(Flight::courseService()->get_all_courses($offset, $limit,$order));
}); 

/**
 * @OA\Get(path="/admin/courses/{id}", tags={"admin", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of course"),
 *     @OA\Response(response="200", description="Fetch individual course")
 * )
 */
Flight::route('GET /admin/courses/@id', function($id){
  Flight::json(Flight::courseService()->get_by_id($id));
});

/**
 * @OA\Post(path="/admin/courses", tags={"admin", "courses"}, security={{"ApiKeyAuth": {}}},
 *   @OA\RequestBody(description="Basic course info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="name",	description="Name of the course" ),
 *    				 @OA\Property(property="description", required="true", type="string", example="description",	description="Description of the course" ),
 *          )
 *       )
 *     ),
 *  @OA\Response(response="200", description="Saved course")
 * )
 */
Flight::route('POST /admin/courses', function(){
  Flight::json(Flight::courseService()->add_course(Flight::get('user'),Flight::request()->data->getData()));
});


 /**
 * @OA\Put(path="/admin/courses/{id}", tags={"admin", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of course"),
 *     @OA\RequestBody(description="Basic course info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="name",	description="Name of the updated course" ),
 *    				 @OA\Property(property="description", required="true", type="string", example="description",	description="Description of the updated course" ),
 *          )
 *       )
 *     ),
 *     @OA\Response(response="200", description="Update course")
 * )
 */
Flight::route('PUT /admin/courses/@id', function($id){
  Flight::json(Flight::courseService()->update_course($id,Flight::request()->data->getData()));
});

 /**
 * @OA\Delete(path="/admin/courses/{id}", tags={"admin", "courses"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="path", name="id", default=1, description="Id of course"),
 *     @OA\Response(response="200", description="Delete course")
 * )
 */
Flight::route('DELETE /admin/courses/@id', function($id){
  Flight::json(Flight::courseService()->delete_course($id));
});

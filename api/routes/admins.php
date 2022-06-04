<?php

/**
 * @OA\Get(path="/admin/account", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch admin account")
 * )
 */
Flight::route('GET /admin/account', function () {
    Flight::json(Flight::accountService()->get_by_id(Flight::get('user')['aid']));
});

/**
 * @OA\Get(
 *     path="/admin/students", tags={"admin", "students"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(type="integer", in="query", name="offset", default=0, description="Offset for pagination"),
 *     @OA\Parameter(type="integer", in="query", name="limit", default=25, description="Limit for pagination"),
 *     @OA\Parameter(type="string", in="query", name="search", description="Search string from students. Case insensitive search."),
 *     @OA\Parameter(type="string", in="query", name="order", default="-id", description="Sorting for returned elements. -column_name ascending order by column_name or +column_name descending order by column_name"),
 *     @OA\Response(response="200", description="List students from database")
 * )
 */
Flight::route('GET /admin/students',function(){    
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 25);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");
    Flight::json(Flight::studentService()->get_students($search, $offset, $limit, $order));
});

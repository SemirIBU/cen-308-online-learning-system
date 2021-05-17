<?php

Flight::route('GET /courses',function(){    
    $offset = Flight::query('offset', 0);
    $limit = Flight::query('limit', 25);
    $search = Flight::query('search');
    $order = Flight::query('order', "-id");
    Flight::json(Flight::courseService()->get_courses($search, $offset, $limit, $order));
});

Flight::route('GET /courses/@id',function($id){    
    Flight::json(Flight::courseService()->get_by_id($id));
});

Flight::route('POST /courses',function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::courseService()->add($data));
});

Flight::route('PUT /courses/@id',function($id){    
    $data = Flight::request()->data->getData();                
    Flight::json(Flight::courseService()->update($id, $data));
});


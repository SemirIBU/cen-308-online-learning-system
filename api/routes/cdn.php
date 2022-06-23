<?php

/**
 * @OA\Post(path="/student/profile-picture  ", tags={"student", "cdn"}, security={{"ApiKeyAuth": {}}},
 *   @OA\RequestBody(description="Change profile picture", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *    				 @OA\Property(property="name", required="true", type="string", example="name",	description="FileName" ),
 *    				 @OA\Property(property="content", required="true", type="string", example="test",	description="Base64 encoded content" )
 *          )
 *       )
 *     ),
 *  @OA\Response(response="200", description="Change profile picture")
 * )
 */
Flight::route('POST /student/profile-picture', function () {
  $data = Flight::request()->data->getData();
  $student = Flight::studentService()->get_by_aid(Flight::get('user')['aid']);
  $picName = $student['first_name'] . $student['last_name'] . $student['id'];
  $url = Flight::cdnClient()->upload($picName, $data['content']);
  Flight::json(["url" => $url]);
});

/**
 * @OA\Get(path="/student/profile-picture  ", tags={"student", "cdn"}, security={{"ApiKeyAuth": {}}},
 *  @OA\Response(response="200", description="Change profile picture")
 * )
 */
Flight::route('GET /student/profile-picture', function () {
  $student = Flight::studentService()->get_by_aid(Flight::get('user')['aid']);
  $picName = $student['first_name'] . $student['last_name'] . $student['id'];
  if (Flight::cdnClient()->if_file_exists($picName)) {
    $url = Flight::cdnClient()->get_url($picName);
    Flight::json(["url" => $url]);
  } else Flight::json(["url" => 0]);
});

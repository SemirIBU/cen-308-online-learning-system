<?php

/**
 * @OA\Get(path="/professor/account", tags={"professor", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch student account")
 * )
 */
Flight::route('GET /professor/account', function () {
  Flight::json(Flight::accountService()->get_by_id(Flight::get('user')['aid']));
});

/**
 * @OA\Get(path="/professor/profile", tags={"professor"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch professor profile")
 * )
 */
Flight::route('GET /professor/profile', function(){
  Flight::json(Flight::professorService()->get_by_aid(Flight::get('user')['aid']));
});

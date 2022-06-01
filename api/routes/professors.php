<?php

/**
 * @OA\Get(path="/professor/account", tags={"professor", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch student account")
 * )
 */
Flight::route('GET /professor/account', function () {
  if (Flight::get('user')['r'] == 'professor' or Flight::get('user')['r'] == 'admin') Flight::json(Flight::accountService()->get_by_id(Flight::get('user')['aid']));
});

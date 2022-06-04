<?php

/**
 * @OA\Get(path="/admin/account", tags={"admin", "account"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Response(response="200", description="Fetch admin account")
 * )
 */
Flight::route('GET /admin/account', function () {
    Flight::json(Flight::accountService()->get_by_id(Flight::get('user')['aid']));
});

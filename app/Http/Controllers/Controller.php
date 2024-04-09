<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Portals OpenApi Documentation",
 *      description="Portals Swagger OpenApi description",
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Portals API Server",
 *      @OA\SecurityScheme(
 *          securityScheme="bearerAuth",
 *          type="http",
 *          scheme="bearer",
 *      ),
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with to get the authentication token",
 *     name="Token based",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="apiAuth",
 * )
 */
abstract class Controller
{
    //
}

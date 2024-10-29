<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Info(
 *     title="Nova Forum Api",
 *     version="1.0.0"
 * )
 * @OA\PathItem(
 *     path="/api/"
 * ),
 * @OA\Components(
 *      @OA\SecurityScheme(
 *          securityScheme="bearerAuth",
 *          type="http",
 *          scheme="bearer"
 *      )
 * )
 */
abstract class Controller
{
    //
}

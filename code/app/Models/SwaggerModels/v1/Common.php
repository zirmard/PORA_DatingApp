<?php
/**
 * @OA\Info(
 *      version=API_VERSION,
 *      title=APP_NAME
 * )
 * @OA\Server(
 *      url=SWAGGER_HOST,
 *      description=SWAGGER_HOST_DESC,
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *     )
 * )
 */

 /**
 * @OA\Schema(
 *   schema="CommonFields",
 *   type="object",
 *   @OA\Property(
 *      property="responseCode", 
 *      type="integer",
 *      format="int32"
 *   ),
 *   @OA\Property(
 *      property="responseMessage", 
 *      type="string"
 *   )
 * )
 */

/**
*  @OA\Parameter(
*      parameter="authorization",
*      in="header",
*      name="Authorization",
*      required=false,
*      @OA\Schema(type="string"),
* )
*/
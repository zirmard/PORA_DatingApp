<?php
/**
 * @OA\Get(
 *      path="/user/logout",
 *      tags={USER_TAG},
 *      summary="SignOut User",
 *      operationId="signOut",
 *      security={{ "bearerAuth":{} }},
 *      @OA\Parameter(
 *         in="header",
 *         name="Authorization",
 *         required=true,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      ),
 * )
 */

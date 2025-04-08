<?php
/**
 * @OA\Get(
 *      path="/contact-reasons",
 *      tags={CONTACT_REASON},
 *      summary="List of Contact Reasons",
 *      operationId="contactReasonsList",
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/GetContactReasonResponse"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetContactReasonResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetContactReasonResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="GetContactReasonResponseFields",
 *   @OA\Property(
 *       property="iContactReasonId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vContactReason",
 *       type="string"
 *   )
 * )
 */


/**
 * @OA\Post(
 *      path="/user/create-contact-reason",
 *      tags={CONTACT_REASON},
 *      summary="Create Contact Reason",
 *      operationId="createContactReason",
 *      security={{ "bearerAuth":{} }},
 *      @OA\Parameter(
 *         in="header",
 *         name="Authorization",
 *         required=true,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"iContactReasonId","txDetails"},
 *                 @OA\Property(property="iContactReasonId", type="integer"),
 *                 @OA\Property(property="txDetails", type="string"),
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/CreateContactReasonResponse"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */

 /**
 * @OA\Schema(
 *   schema="CreateContactReasonResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/CreateContactReasonResponseFields"
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="CreateContactReasonResponseFields",
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="iContactReasonId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="txDetails",
 *       type="string"
 *   )
 * )
 */

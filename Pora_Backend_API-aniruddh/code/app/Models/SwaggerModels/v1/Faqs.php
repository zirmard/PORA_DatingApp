<?php
/**
 * @OA\Get(
 *      path="/faqs",
 *      tags={COMMON_TAG},
 *      summary="List of FAQs",
 *      operationId="faqsList",
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/FaqResponse"),
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
 *   schema="FaqResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/FaqResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="FaqResponseFields",
 *   @OA\Property(
 *       property="vQuestionCategory",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vQuestion",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="txAnswer",
 *       type="string"
 *   ),
 * )
 */

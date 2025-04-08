<?php
/**
 * @OA\Get(
 *      path="/report-reasons",
 *      tags={REPORT_REASON},
 *      summary="List of Report Reasons",
 *      operationId="reportReasonsList",
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/ReportReasonResponse"),
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
 *   schema="ReportReasonResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/ReportReasonResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="ReportReasonResponseFields",
 *   @OA\Property(
 *       property="iReportReasonId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vReportReason",
 *       type="string"
 *   )
 * )
 */

 /**
 * @OA\Post(
 *      path="/user/create-report-reason",
 *      tags={REPORT_REASON},
 *      summary="Create Report Reason",
 *      operationId="createReportReason",
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
 *                 required={"iReportReasonId","iReportedUserId","txDetails"},
 *                 @OA\Property(property="iReportReasonId", type="integer"),
 *                 @OA\Property(property="iReportedUserId", type="integer"),
 *                 @OA\Property(property="txDetails", type="string")
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/CreateReportReasonResponse"),
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
 *   schema="CreateReportReasonResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/CreateReportReasonResponseFields"
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="CreateReportReasonResponseFields",
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="iReportReasonId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="txDetails",
 *       type="string"
 *   )
 * )
 */

<?php

namespace Yjtec\LaravelSms;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SmsController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/sms",
     *     tags={"SMS"},
     *     summary="发送验证码",
     *     description="发送验证码",
     *     operationId="FoodStore",
     *     @OA\RequestBody(ref="#/components/requestBodies/SmsRequest"),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function index(SmsRequest $request)
    {
        $phone = $request->phone;
        $type = $request->type;
        $data = app('sms')->type($type)->send($phone);
        $postData = [
            'phone' => $phone,
            'code' => $data['code'],
            'type' => $type,
            'expired_at' => $data['expiredAt'],
        ];
        $sms = SmsModel::create($postData);
        return new SmsResource($sms);
    }

    /**
     * @OA\Post(
     *     path="/sms/check",
     *     tags={"SMS"},
     *     summary="效验短信验证码",
     *     description="注意is_final参数",
     *     operationId="smsCheck",
     *     @OA\RequestBody(ref="#/components/requestBodies/SmsCheckRequest"),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function check(SmsCheckRequest $request)
    {
        $phone = $request->phone;
        $type = $request->type;
        $verificationCode = $request->verification_code;
        $isFinal = isset($request->is_final) ? (int) $request->is_final : 1;
        $isFinal !== 0 && $isFinal = 1;
        $data = app('sms')->type($type)->check($phone, $verificationCode, $isFinal);
        return new SmsCheckResource(collect($data));
    }
}

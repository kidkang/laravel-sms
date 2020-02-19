<?php

namespace Yjtec\LaravelSms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
/**
 * @OA\RequestBody(
 *     request="SmsRequest",
 *     description="发送验证码请求体",
 *     required=true,
 *     @OA\MediaType(
 *         mediaType="multipart/form-data",
 *         @OA\Schema(
 *             type="object",
 *             required={"phone","type"},
 *             @OA\Property(
 *                property="phone",
 *                description="手机号",
 *                type="string",
 *                example="13072419652"
 *             ),
 *             @OA\Property(
 *                property="type",
 *                type="array",
 *                @OA\Items(ref="#/components/schemas/SmsTypes")
 *             )
 *         )
 *     )
 * )
 */
class SmsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $defaultRule = ['required', 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0-3,5-8])|(18[0-9])|166|165|198|199|(147))\d{8}$/'];
        if ($rules = config('sms.rules.' . $this->type)) {
            $phoneRules = array_merge($defaultRule, $rules);
        } else {
            $phoneRules = $defaultRule;
        }
        return [
            'phone' => $phoneRules,
            'type' => [
                'required',
                Rule::in(config('sms.type')),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'phone' => '手机号',
            'type' => '请求类型',
        ];
    }
}

<?php

namespace Yjtec\LaravelSms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Yjtec\LaravelSms\SmsRule;
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
        $defaultRule = ['required', 'regex:' . SmsRule::getPhonePregMatchRule()];
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

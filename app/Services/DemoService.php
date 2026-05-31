<?php

namespace Services;
use Models\Demo;

class DemoService
{
    use \HasThrowable;
    /**
     * 基于的Laravel的 示例创建
     * @param array $data
     * @return mixed
     */
    public static function create(array $data)
    {
        self::tryCatch(function () use ($data) {

            // 验证并获取通过验证的数据
            $valiData = validator($data, [
                'username'  => 'required|string|max:100',
                'nickname' => 'required|string|max:100',
            ])->validate();     // validate() 会自动抛出 ValidationException
            //== 注解 ==
            // $valiData 能明确告诉阅读代码的人（包括未来的自己）：“这个变量是经过验证后的数据”。
            // $input 只能表示“输入的数据”，无法体现它已经被验证处理过。
            // 在函数稍长的时候，这种语义清晰度很重要。习惯Snake变量的可以用$vali_data

            $valiProfileData = validator($data, [
                'desc'  => 'string|max:100',
                'contact_way' => 'required|integer',
            ])->validate();

            $user = User::create($valiData);
            $valiProfileData['uid'] = $user->uid;
            $userProfile = UserProfile::create($valiProfileData);
        }, true);
    }

    /**
     * 基于的Laravel的 示例创建
     * @param array $data
     * @return mixed
     */
    public static function update(array $data)
    {
        try {
            // 验证（id 必须存在且有效）
            $valiData = validator($data, [
                'id'                => 'required|integer|exists:users,id',
                'username'          => 'sometimes|required|string|max:100',//sometimes 的意思就是有这个key时才验证
                'nickname'          => 'sometimes|required|string|max:100',
                'desc'              => 'sometimes|string|max:100',
                'contact_way'       => 'sometimes|required|integer',
                'status'            => 'sometimes|required|integer',
                'onlyUpdateProfile' => 'boolean',//放在最后会好一点，因为有boolean加持明眼人一看就明白
            ])->validate();

            $id = $valiData['id'];
            unset($valiData['id']);        // 重要！移除 id，防止意外更新 id 字段

            // 更新 User 表
            if (empty($valiData['onlyUpdateProfile'])) {
                User::where('id', $id)->update($valiData);
            }

            // 更新 UserProfile 表
            UserProfile::where('user_id', $id)->update([
                'desc'        => $valiData['desc'] ?? null,
                'contact_way' => $valiData['contact_way'] ?? null,
            ]);

            return true;

        } catch (\Throwable $e) {
            \Log::error('Update user failed', ['data' => $data, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}

Demo::update([

    //没想到PHPStorm连 onlyUpdateProfile 这种来自方法成的数组中的变量都能提示出来太强了。
    'onlyUpdateProfile' => true
]);
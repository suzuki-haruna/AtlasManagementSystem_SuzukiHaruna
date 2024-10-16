<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() // ログインしているかどうかをチェックするもの
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
        $data = $this->all(); // prepareForValidation用に追加

        return [
            'post_title' => 'min:4|max:50',
            'post_body' => 'min:10|max:500',

            // 追加
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|regex:/\A[ァ-ヴー]+\z/u|max:30',
            'under_name_kana' => 'required|string|regex:/\A[ァ-ヴー]+\z/u|max:30',
            'mail_address' => 'required|unique:users,mail_address|email|max:100',
            'sex' => 'required',
            'role' => 'required',
            'password' => 'confirmed|required|min:8|max:30',
            //'password' => 'confirmed|required|regex:/^[A-Za-z0-9]+$/u|min:8|max:30',

            'birth_day' => 'required|date|before:'.date('Y-m-d'),
            /*'old_year' => 'required|numeric|birth_day:old_year,old_month,old_day',
            'old_month' => 'required|numeric',
            'old_day' => 'required|numeric',*/
            /*'old_year' => 'nullable|present|numeric|required_with:old_month,old_day',
            'old_month' => 'nullable|present|numeric|required_with:old_year,old_day',
            'old_day' => 'nullable|present|numeric|required_with:old_year,old_mouth',
            'birth_day' => 'nullable|date|before_or_equal:' . today()->format('Y-m-d'),*/
            //'birth_day' => 'date|before:today',　before:'.date('Y-m-d'),
            /*'old_year' => 'required',
            'old_month' => 'required',
            'old_day' => 'required',*/
        ];
    }

    public function messages(){
        return [
            'post_title.min' => 'タイトルは4文字以上入力してください。',
            'post_title.max' => 'タイトルは50文字以内で入力してください。',
            'post_body.min' => '内容は10文字以上入力してください。',
            'post_body.max' => '最大文字数は500文字です。',

            // 追加
            'over_name.max' => '性は10文字以内で入力してください。',
            'under_name.max' => '名は10文字以内で入力してください。',
            'over_name_kana.regex' => 'セイはカタカナで入力してください。',
            'over_name_kana.max' => 'セイは30文字以内で入力してください。',
            'under_name_kana.regex' => 'メイはカタカナで入力してください。',
            'over_name_kana.max' => 'メイは30文字以内で入力してください。',
            'mail_address.unique' => '既に登録されているメールアドレスです。',
            'mail_address.email' => 'メールアドレスの形式で入力してください。',
            'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
            'birth_day.before' => '正しい生年月日を入力してください。',
            'birth_day.date' => '正しい生年月日を入力してください。',
            'password.confirmed' => '確認用パスワードと一致しません。',
            'password.min' => 'パスワードは8文字以上入力してください。',
            'password.max' => 'パスワードは30文字以下で入力してください。',
        ];
    }

    // 追加
    protected function prepareForValidation()
    {
        $data = [];
        $data['birth_day'] = sprintf('%04d-%02d-%02d', $this->old_year, $this->old_month, $this->old_day);
        if ($data['birth_day'] == '0000-00-00') $data['birth_day'] = null;
        //$data['title'] = mb_convert_kana($this->title, 'aKV');
        //$data['body'] = mb_convert_kana($this->body, 'aKV');

        $this->merge($data);
    }
    /*protected function prepareForValidation()
    {
    $birth_day = ($this->filled(['old_year', 'old_month', 'old_day'])) ? $this->date .' '. $this->time : '';
    $this->merge([
       'birth_day' => $birth_day
    ]);
    }*/
}

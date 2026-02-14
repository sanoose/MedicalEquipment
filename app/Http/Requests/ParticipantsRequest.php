<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;


class ParticipantsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

     public function rules(): array
     {
         return [
             'first_name'         => ['required', 'string', 'max:255'],
             'last_name'          => ['required', 'string', 'max:255'],
             'gender'             => ['required', 'in:1,2'],
             'nationality'        => ['required', 'string'],
             'profile_photo_path' => ['nullable', 'image'],
 
             'participant_type'   => ['required', 'in:1,2'],
             'country_id'         => ['required_if:participant_type,2'],
 
             'organization'       => ['required', 'string'],
             'job_title'          => ['required', 'string'],
             'participant_role'   => ['required', 'in:1,2,3'],
             'attendance_type'   => ['required', 'in:1,2,3'],
 
             'phone' => [
                 'required',
                 'regex:/^[1-9][0-9]{8,10}$/', // فقط أرقام، لا يبدأ بـ 0، من 9 إلى 11 رقم
                 Rule::unique('participants', 'phone')
             ],
 
             'has_whatsapp'       => ['nullable', 'boolean'],
             'email'              => ['required', 'email', 'confirmed', Rule::unique('users', 'email')],
             'password'           => ['required', 'min:3', 'confirmed'],
 
             'visa_required' => [
                 'required_if:participant_type,2',
                 'nullable',
                 'in:1,2',
             ],
             'passport_file'      => ['required_if:participant_type,2', 'nullable', 'image'],
 
             'payment_method'     => ['required', 'in:1,2'],
             'payment_receipt'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf'],
             'notes'              => ['nullable', 'string'],
         ];
     }
 
     public function messages(): array
     {
         return [
             'first_name.required' => 'الاسم الأول مطلوب.',
             'last_name.required' => 'اسم العائلة مطلوب.',
             'gender.required' => 'يرجى اختيار النوع.',
             'gender.in' => 'القيمة المحددة للنوع غير صحيحة.',
             'nationality.required' => 'الجنسية مطلوبة.',
             'profile_photo_path.image' => 'يجب أن تكون الصورة الشخصية صورة صحيحة.',
 
             'participant_type.required' => 'نوع المشاركة مطلوب.',
             'participant_type.in' => 'القيمة المحددة لنوع المشاركة غير صحيحة.',
             'country_id.required_if' => 'يرجى تحديد الدولة إذا كنت مشاركًا من خارج ليبيا.',
 
             'organization.required' => 'جهة العمل مطلوبة.',
             'job_title.required' => 'المسمى الوظيفي مطلوب.',
             'participant_role.required' => 'نوع المشارك مطلوب.',
             'participant_role.in' => 'القيمة المحددة لنوع المشارك غير صحيحة.',
 
             'phone.required' => 'رقم الهاتف مطلوب.',
             'phone.regex' => 'رقم الهاتف يجب أن يتكون من 9 إلى 11 رقمًا، ولا يبدأ بصفر.',
             'phone.unique' => 'رقم الهاتف مستخدم مسبقًا.',
 
             'has_whatsapp.boolean' => 'قيمة واتساب يجب أن تكون صحيحة.',
 
             'email.required' => 'البريد الإلكتروني مطلوب.',
             'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
             'email.confirmed' => 'تأكيد البريد الإلكتروني غير مطابق.',
             'email.unique' => 'البريد الإلكتروني مستخدم مسبقًا.',
 
             'password.required' => 'كلمة المرور مطلوبة.',
             'password.min' => 'كلمة المرور يجب أن تكون على الأقل 3 أحرف.',
             'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
 
             'visa_required.required_if' => 'يرجى تحديد ما إذا كنت بحاجة لتأشيرة إذا كنت مشاركًا دوليًا.',
             'visa_required.in' => 'القيمة المحددة لخيار التأشيرة غير صحيحة.',
 
             'passport_file.required_if' => 'يرجى رفع صورة جواز السفر للمشاركين الدوليين.',
             'passport_file.image' => 'يجب أن تكون صورة جواز السفر صورة صحيحة.',
 
             'payment_method.required' => 'يرجى اختيار طريقة الدفع.',
             'payment_method.in' => 'القيمة المحددة لطريقة الدفع غير صحيحة.',
 
             'payment_receipt.mimes' => 'يجب أن يكون إيصال الدفع بصيغة PDF أو صورة.',
             'notes.string' => 'صيغة الملاحظات غير صحيحة.',
         ];
     }
     
    // public function rules(): array
    // {
    //     return [
    //         'first_name'         => ['required', 'string', 'max:255'],
    //         'last_name'          => ['required', 'string', 'max:255'],
    //         'gender'             => ['required', 'in:1,2'],
    //         'nationality'        => ['required', 'string'],
    //         'profile_photo_path' => ['nullable', 'image'],
    
    //         'participant_type'   => ['required', 'in:1,2'],
    //         'country_id'         => ['required_if:participant_type,2'],
    
    //         'organization'       => ['required', 'string'],
    //         'job_title'          => ['required', 'string'],
    //         'participant_role'   => ['required', 'in:1,2,3'],
    
    //         'phone'              => ['required', 'string', 'regex:/^[0-9+\s\-()]+$/', Rule::unique('participants', 'phone')],

    //         'has_whatsapp'       => ['nullable', 'boolean'],
    //         'email'              => ['required', 'email', 'confirmed', Rule::unique('users', 'email')],
    //         'password'           => ['required', 'min:3', 'confirmed'],
            
    //         'visa_required' => [
    //                 'required_if:participant_type,2', // required   في حال اشتغلت تسقط  نللبل   في حال لم تشتغل تعمل نللبل وتسقط إن 
    //                 'nullable',  'in:1,2',
    //             ],
    //         'passport_file'      => ['required_if:participant_type,2', 'nullable', 'image'],
    
    //         'payment_method'     => ['required', 'in:1,2'],
    //         'payment_receipt'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf'],
    //         'notes'              => ['nullable', 'string'],
    //     ];
    // }
}

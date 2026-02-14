<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyParticipantEmail;

trait SendVerificationTrait
{
    public function SendVerificationEmail($user)
    {
            try {
                if (  $user->email_verified == 1 ||  ($user->token_expires &&  now()->diffInMinutes($user->updated_at,  true )   <  1  )  )   {
                // if (  false  )   {
                    return false;
                }else {
                    $user->verification_token = Str::random(64);
                    $user->token_expires = Carbon::now()->addHours(24); 
               
                    $user->save();
            
                    Mail::to($user->email)->send(new VerifyParticipantEmail($user));
                    return true  ; 
                }
            } catch (\Throwable $th) {
                throw $th;
            }
 
  

    }
}

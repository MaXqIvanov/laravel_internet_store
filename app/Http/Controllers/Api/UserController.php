<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MailPost;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use  Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOne(Request $request, $email)
    {
        //

        try {
            $email = $request->email;
            $user = users::where("email", $email)->select('activation', 'email')->get();
            // $oldEmail = $user[0]->email;
            return $user[0];
        } catch (\Throwable $th) {
            //throw $th;
            return "false";
        }
    }



    public function auth(Request $request)
    {
        //
        try {
            $email = $request->email;
            $password = $request->password;
            $old = users::where("email", $email)->get();
            $oldEmail = $old[0]->email;
            $oldPassword = $old[0]->password;
            $oldId = $old[0]->id;

            if (password_verify($password, $oldPassword)) {
                $obj = ["email" => $oldEmail, "id" => $oldId];
                return $obj;
            } else return "false";
        } catch (\Throwable $th) {
            return "false";
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNew(Request $request)
    {
        //
        try {
            //code...
            $email = $request->email;
            $password = $request->password;
            $token = $request->token;
            $old = users::where("email", $email)->get();
            try {
                $old[0];
            } catch (\Throwable $th) {
                $old[0] = "";
            }
            if ($old[0] == "") {
                $capcha = false;
                try {
                    $capcha = Http::post("https://www.google.com/recaptcha/api/siteverify?secret=6LeGQ4IfAAAAAJz0v4gRA63JpIe8mCqgBZ8P1Jfk&response=$token");
                    $capchaNew = $capcha->json();
                    $capcha = $capchaNew['success'];
                } catch (\Throwable $th) {
                    $capcha = true;
                }
                if ($capcha === true) {
                    $activationLink = (string) Str::uuid();
                    $bcpassword = password_hash($password, PASSWORD_DEFAULT);
                    try {
                        Mail::to($email)->send(new MailPost($activationLink));
                    } catch (\Throwable $th) {
                    }
                    $device = users::create([
                        "email" => $email, "password" => $bcpassword,
                        "verification" => $activationLink
                    ]);
                    return response()->json(["message" => "Регистрация прошла успешно"]);
                } else return "false";
            } else return "false";
        } catch (\Throwable $th) {
            return "false";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setRaitingProod(Request $request, $id)
    {
        //
        try {
            //code...
            $email = $request->email;
            $nameProods = $request->nameProods;
            $old = users::where("email", $email)->get();
            $old = $old[0]->voited;
            $arrayOld = explode(',', $old);
            $filter = 0;
            foreach ($arrayOld as $word) {
                if ($word == $nameProods) {
                    $filter = 1;
                }
            }
            if ($filter > 0) {
                return "false";
            } else {
                $summ = $nameProods . "," . $old;
                $device = users::where("email", $email)->update(["voited" => $summ]);
                return $device;
            }
            return $filter;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setActivated(Request $request, $link)
    {
        //
        try {
            $verification = $link;
            $old = users::where("verification", $verification)->select('verification', 'activation')->get();
            $oldVerification = $old[0]->verification;
            $oldActivation = $old[0]->activation;
            if ($verification == $oldVerification) {
                $oldActivation = true;
                users::where("verification", $oldVerification)->update(["activation" => $oldActivation]);
            }
            return redirect('https://store.web-liter.ru/');
        } catch (\Throwable $th) {
            //throw $th;
            return response('<a href="https://store.web-liter.ru/">Ваша ссылка не 
            действительна <br></br>
            вернуться на главную
            </a>');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


// 
class MailServiceFunc
{
    public function registe($email, $activationLink)
    {
        mail("maksivanov35@ya.ru", "Отправка через SSMTP агента", "Это проверка отправки");
    }
}

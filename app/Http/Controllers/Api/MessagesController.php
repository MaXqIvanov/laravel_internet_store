<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Messages;
use App\Models\users;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Http;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        //
        try {
            //code...
            $id = $request->id;
            $message = Messages::where("idProods", $id)->select('id', 'idProods', 'messages', 'imgPerson', 'namePerson', 'email', 'activation')->paginate(10);
            return $message;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNewPost(Request $request, $id)
    {
        //
        try {
            //code...
            $idProods = $request->idProods;
            $messages = $request->messages;
            $imgPerson = $request->imgPerson;
            $namePerson = $request->namePerson;
            $email = $request->email;

            $newMessages = Messages::create([
                "idProods" => $idProods,
                "messages" => $messages,
                "imgPerson" => $imgPerson,
                "namePerson" => $namePerson,
                "email" => $email
            ]);
            return $newMessages;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePost(Request $request, $id)
    {
        //
        try {
            //code...
            $password = $request->password;
            $email = $request->email;
            $user = users::where("email", $email)->get();
            $oldPassword = $user[0]->password;
            $oldRole = $user[0]->role;
            if (password_verify($password, $oldPassword)) {
                if ($oldRole == "ADMIN") {
                    $messages = Messages::find($id)->delete();
                    return $messages;
                }
                return "Вы не обладаите правами доступа";
            }
            return "false";
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendTelegram(Request $request)
    {
        try {
            $messageTeleg = $request->messageTeleg;
            $token = $request->token;

            $capcha = false;
            try {
                $capcha = Http::post("https://www.google.com/recaptcha/api/siteverify?secret=6LeGQ4IfAAAAAJz0v4gRA63JpIe8mCqgBZ8P1Jfk&response=$token");
                $capchaNew = $capcha->json();
                $capcha = $capchaNew['success'];
            } catch (\Throwable $th) {
                $capcha = true;
            }
            if ($capcha === true) {
                $newMessage = rawurlencode($messageTeleg);
                $telegram = Http::post("https://api.telegram.org/bot5060229836:AAEaHIgNQv9tY4skvJU7KH1ifu95t4yglMk/sendMessage?chat_id=-602739820&parse_mode=html&text=$newMessage");

                return response()->json(["message" => "Спасибо за вашу помощь"]);
            } else return response()->json(["message" => "К сожалению отправить сообщение не получилось"]);
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interstores;
use App\Models\users;
use Illuminate\Http\Request;

class ProductsAddControllerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        try {
            //code...
            $limit = $request->collect('limit');
            $sort = $request->collect('sort');
            $name = $request->name;
            if ($name) {
                $goods = Interstores::where('name', 'LIKE', "%" . $name . "%")->paginate($limit[0]);

                if (!$goods) {
                    return response()->json(["message" => "Товара с таким именем не существует"]);
                }
                if ($goods[0] == []) {
                    return response()->json(["message" => "Товара с таким именем не существует"]);
                }

                return $goods;
            }

            if ((string)$sort[0] == "true") {
                return Interstores::where('type', $type)->orderBy('price', 'asc')->paginate($limit[0]);
            }
            return Interstores::where('type', $type)->paginate($limit[0]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        try {
            //code...
            $name = $request->name;
            $description = $request->description;
            $price = $request->price;
            $url = $request->url;
            $type = $request->type;
            $email = $request->email;
            $password = $request->password;
            $role = users::where("email", $email)->select('role', 'password')->get();
            $oldPassword = $role[0]->password;
            if (password_verify($password, $oldPassword)) {
                if ($role[0]->role == "ADMIN") {
                    $device = Interstores::create([
                        "name" => $name,
                        "description" => $description,
                        "price" => $price,
                        "url" => $url,
                        "type" => $type
                    ]);
                    return  $device;
                }
            } else {
                return response()->json("Вы ввели не правильный пароль");
            }


            return "Вы не обладаите правами доступа";
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => $th]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setRaiting(Request $request, $id)
    {
        try {
            $old = Interstores::find($id)->raiting;
            $email = $request->collect('email');
            $nameProods = $request->collect('nameProods');
            $user = users::where('email', $email)->get()[0];
            $voitedUser = $user->voited;
            if ($voitedUser == null) {
                $voitedUser = "";
            }
            $arrayOld = explode(',', $voitedUser);
            $filter = 0;
            foreach ($arrayOld as $word) {
                if ($word == $nameProods[0]) {
                    $filter = 1;
                }
            }
            if ($filter > 0) {
                return "false";
            } else {
                $isRainting = $request->raiting;
                $isAuth = $request->auth;
                if ($isRainting !== null && $isAuth !== null) {
                    $sum = $old . "," . $isRainting[0];
                    try {
                        $device = Interstores::findOrfail($id)->update(['raiting' => $sum]);
                        return "true";
                    } catch (\Throwable $th) {
                        return "false";
                    }


                    return $device;
                }
                return "false";
            }
        } catch (\Throwable $th) {
            //throw $th;
            return "false";
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteOne(Request $request, $id)
    {
        //
        try {
            //code...
            $email = $request->email;
            $password = $request->password;
            $role = users::where("email", $email)->select('role', 'password')->get();
            $oldPassword = $role[0]->password;
            if (password_verify($password, $oldPassword)) {
                if ($role[0]->role == "ADMIN") {
                    $device = Interstores::find($id)->delete();
                    return $device;
                }
            } else {
                return response()->json(["message" => "Вы ввели не правильный пароль"]);
            }
            return response()->json(["message" => "Вы не обладаите правами доступа"]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["message" => $th]);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointTrxs;
use Input;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ApiController extends Controller
{

    public function setUserPoint(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'point_type_id' => 'required',
            'amount' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => '01', 'message' => $validator->errors()], 422);
        }

        $token = JWTAuth::getToken();
        $user = self::authorize_user($token);

        if($user){
            $data['user_id']        = $user->id;
            $data['point_type_id']  = $input['point_type_id'];
            $data['amount']         = $input['amount'];
            $data['description']    = $input['description'];

            $trans = PointTrxs::create($data);
            if($trans)
                return response()->json(['response_code' => '00', 'message' => 'success'], 200);
            else
                return response()->json(['response_code' => '03', 'message' => 'insert data failed'], 500);
        } else {
            return response()->json(['response_code' => '01', 'message' => 'Unauthorized'], 401);
        }
    }

    private function authorize_user($token)
    {
        $user = JWTAuth::toUser($token);

        return $user;
    }
	
}
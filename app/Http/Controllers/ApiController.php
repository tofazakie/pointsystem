<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointTrxs;
use App\Models\PointTypes;
use App\Models\UserPoints;
use Input;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ApiController extends Controller
{
    /**
     * @OA\Post(
     *   path="/pointsystem/public/v1/setuserpoint",
     *   tags={"SetUserPoint"},
     *   summary="SetUserPoint. Set point both addition and reduction",
     *   description="SetUserPoint",
     *   operationId="setuserpoint",
     *
     *   @OA\Parameter(
     *      name="point_type_id",
     *      description="Type of point",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="amount",
     *      description="Amount of point",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      description="Description of point",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Incomplete parameter(s)",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=500,
     *      description="Point process failed",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     **/
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


    public function getUserPoint(Request $request)
    {
        $token = JWTAuth::getToken();
        $user = self::authorize_user($token);

        if($user){
            $arrPointTypes = PointTypes::arrPointTypes();
            $points = UserPoints::getUserPoint($user->id);

            $data = [];
            foreach($points as $point){
                $dt['point_type_id'] = $point->point_type_id;
                $dt['point_type_name'] = $arrPointTypes[$point->point_type_id];
                $dt['amount'] = $point->amount;
                $data[] = $dt;
            }
            
            return response()->json(['response_code' => '00', 'message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['response_code' => '01', 'message' => 'Unauthorized'], 401);
        }
    }
	
}
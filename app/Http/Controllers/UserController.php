<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get(['id' ,'name', 'dob', 'gender']);

        foreach ($users as $user) {
            $userData = [
                'id'   => $user->id,
                'name' => $user->name,
                'dob'  => $user->dob->format('d-M-Y'),
                'gender' => $user->gender,
            ];

            $responseData[] = $userData;
        }

        return Responses::success($responseData, 'List Users.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'dob'   => 'required|date',
            'gender'    => 'required|in:man,women',
        ]);

        if ($validator->fails()) {
            return Responses::error($validator->errors(), 'Error Validation');
        }

        try {
            $createUser = User::create($validator->validated());

            $responseData = [
                'id'   => $createUser->id,
                'name' => $createUser->name,
                'dob'  => $createUser->dob->format('d-F-Y'),
                'gender' => $createUser->gender,
            ];

            return Responses::success($responseData, 'Success Create User.', Response::HTTP_CREATED);

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            $responseData = [
                'id'   => $user->id,
                'name' => $user->name,
                'dob'  => $user->dob->format('d-F-Y'),
                'gender' => $user->gender,
            ];

            return Responses::success($responseData, 'User Data');

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

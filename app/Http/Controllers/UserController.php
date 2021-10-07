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
        try {
            $users = User::orderByDesc('id')->get(['id' ,'name', 'dob', 'gender']);

            $responseData = [];
            $message = "Data seluruh user";

            if (count($users) == 0) {
                $message = "Tidak ada data user";
            } else {
                foreach ($users as $user) {
                    $userData = [
                        'id'   => $user->id,
                        'name' => $user->name,
                        'dob'  => $user->dob->format('d-F-Y'),
                        'gender' => $user->gender,
                    ];

                    $responseData[] = $userData;
                }
            }

            return Responses::success($responseData, $message);

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
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
            return Responses::error($validator->errors(), 'Validasi error cek kembali, pastikan sesuai.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $createUser = User::create($validator->validated());

            $responseData = [
                'id'   => $createUser->id,
                'name' => $createUser->name,
                'dob'  => $createUser->dob->format('d-F-Y'),
                'gender' => $createUser->gender,
            ];

            return Responses::success($responseData, 'Berhasil menambahkan data user', Response::HTTP_CREATED);

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

            return Responses::success($responseData, 'Data user '. $user->name);

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
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'dob'   => 'required|date',
            'gender'    => 'required|in:man,women',
        ]);

        if ($validator->fails()) {
            return Responses::error($validator->errors(), 'Validasi error cek kembali, pastikan sesuai.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::findOrFail($id);

            $user->update($validator->validated());

            $responseData = [
                'id'   => $user->id,
                'name' => $user->name,
                'dob'  => $user->dob->format('d-F-Y'),
                'gender' => $user->gender,
            ];

            return Responses::success($responseData, 'Berhasil mengubah data user');

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
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
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return Responses::success(null, 'data '. $user->name .' berhasil di delete');

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }
}

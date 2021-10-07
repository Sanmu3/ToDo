<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\Rent;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $rents = Rent::orderByDesc('id')->with(['user:id,name', 'book:id,title'])->get(['id', 'user_id', 'book_id']);

            $responseData = [];
            $message = "Transaksi peminjaman buku";

            if (count($rents) == 0) {
                $message = "Tidak ada peminjaman buku";
            } else {
                foreach ($rents as $rent) {

                    $rentData = [
                        'id'   => $rent->id,
                        'user' => $rent->user,
                        'books' => $rent->book,
                    ];

                    $responseData[] = $rentData;
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
            'user_id'  => 'required',
            'book_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return Responses::error($validator->errors(), 'Validasi error cek kembali, pastikan sesuai.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $createRent = Rent::create($validator->validated());

            return Responses::success("Transaksi ID " . $createRent->id, 'Berhasil meminjam buku', Response::HTTP_CREATED);
        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {

            $userRent = User::where('id', $userId)->with(['book:id,title'])->firstOrFail();

            foreach ($userRent->book as $book) {
                $books[] = [
                    'id'    => $book->id,
                    'title' => $book->title,
                ];
            }

            $responseData = [
                'id'   => $userRent->id,
                'name' => $userRent->name,
                'books' => $books,
            ];

            return Responses::success($responseData, 'Data buku yang dipinjam oleh ' . $userRent->name);
        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }
}

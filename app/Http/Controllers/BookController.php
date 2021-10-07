<?php

namespace App\Http\Controllers;

use App\Book;
use App\Helpers\Responses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $books = Book::orderByDesc('id')->get(['id' ,'title']);

            $responseData = [];
            $message = "Data seluruh buku";

            if (count($books) == 0) {
                $message = "Tidak ada data buku";
            } else {
                foreach ($books as $book) {
                    $bookData = [
                        'id'   => $book->id,
                        'title' => $book->title,
                    ];

                    $responseData[] = $bookData;
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
            'title'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return Responses::error($validator->errors(), 'Validasi error cek kembali, pastikan sesuai.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $createBook = Book::create($validator->validated());

            $responseData = [
                'id'   => $createBook->id,
                'title' => $createBook->title,
            ];

            return Responses::success($responseData, 'Berhasil menambahkan data buku', Response::HTTP_CREATED);

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $book = Book::findOrFail($id);

            $responseData = [
                'id'   => $book->id,
                'title' => $book->title,
            ];

            return Responses::success($responseData, 'Data buku '. $book->title);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return Responses::error($validator->errors(), 'Validasi error cek kembali, pastikan sesuai.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $book = Book::findOrFail($id);

            $book->update($validator->validated());

            $responseData = [
                'id'   => $book->id,
                'title' => $book->title,
            ];

            return Responses::success($responseData, 'Berhasil mengubah data buku');

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

            $book = Book::findOrFail($id);

            $book->delete();

            return Responses::success(null, 'buku '. $book->title .' berhasil di delete');

        } catch (Exception $e) {
            report($e);
            Log::error($e->getMessage());

            return Responses::error(null, $e->getMessage());
        }
    }
}

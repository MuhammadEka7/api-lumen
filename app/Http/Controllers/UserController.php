<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Lending;
use App\models\Restoration;
use App\models\User;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormatter;

class UserController extends Controller
{
    public function index(){
        $user = User::all();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Lihat semua barang',
        //     'data' => $user
        // ],200);

        return ApiFormatter::sendResponse(200, true, "Lihat semua barang", $user);
    }

    public function store(Request $request)
    {
        // $validator = Validator::make
        // ($request->all(), [
        //     'username' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        //     'role' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //      'success' => false,
        //      'message' => 'Semua kolom wajib disi!',
        //      'data' => $validator->errors()
        //     ],400);
        // } else {
        //     $user = user::create([
        //         'username' => $request->input('username'),
        //         'email' => $request->input('email'),
        //         'password' => $request->input('password'),
        //         'role' => $request->input('role'),
        //     ]);
        // }


        // if ($user) {
        //     return response()->json([
        //     'success' => true,
        //     'message' => 'Barang berhasil ditambahkan',
        //         'data' => $user
        //     ],201);
        // } else{
        //     return response()->json([
        //     'success' => false,
        //     'message' => 'Barang gagal ditambahkan',
        //     ],400);
        // }

        try {
            $this->validate($request, [
                'username' => 'required',
                'email' => 'required',
                'password' => 'required',
                'role' => 'required',
            ]);
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => $request->input('role'),
            ]);
            return ApiFormatter::sendResponse(201, true, 'barang berhasil ditambahkan!', $user);
        } catch (\Throwable $th) {
            if ($th->validator->errors()) {
                return ApiFormatter::sendResponse(400, false, 'semua kolom wajib diisi!', $th->validator->errors());
            } else {
                return ApiFormatter::sendResponse(400, false, 'barang gagal ditambahkan!', $th->getMessage());
            }
        }
    }

    public function show($id){
        // try{
        //     $user = user::findOrFail($id);
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Lihat Barang dengan id $id',
        //         'data' => $user
        //     ],200);
        // } catch(\Exception $th) {
        //     return response()->json([
        //     'success' => false,
        // 'message' => 'Data dengan id $id tidak ditemukan',
        //     ],400);
        // }

        try {
            $user = User::findOrFail($id);

            return ApiFormatter::sendResponse(200, true, "Lihat stock dengan id $id", $user);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Data stock dengan id $id tidak ditemukan", $th->getMessage());
        }
    }

    public function update(Request $request, $id){
        // try{
        //     $user = user::findOrFail($id);
        //     $username = ($request->username) ? $request->username : $user->username;
        //     $email = ($request->email)? $request->email : $user->email;
        //     $password = ($request->password)? $request->password : $user->password;
        //     $role = ($request->role)? $request->role : $user->role;

        //     if ($user) {
        //         $user->update([
        //             'username' => $username,
        //             'email,' => $email,
        //             'password,' => $password,
        //             'role,' => $role,
        //         ]);

        //         return response()->json([
        //             'success' => true,
        //             'message' => 'Barang Ubah Data dengan id $id',
        //                 'data' => $user
        //             ],200);
        //     } else {
        //         return response()->json([
        //         'success' => false,
        //         'message' => 'Proses gagal',
        //         ],400);
        //     }
        // } catch(\Throwable $th){
        //     return response()->json([
        //     'success' => false,
        //     'message' => 'Proses gagal! data dengan id $id tidak ditemukan',
        //     ],400);
        // }

        try {
            $user = User::findOrFail($id);

            $username = ($request->username) ? $request->username : $user->username;
            $email = ($request->email)? $request->email : $user->email;
            $password = ($request->password)? $request->password : $user->password;
            $role = ($request->role)? $request->role : $user->role;

            $user->update([
                'username' => $username,
                'email,' => $email,
                'password,' => $password,
                'role,' => $role
            ]);

            return ApiFormatter::sendResponse(200, true, "Berhasil mengubah data barang dengan id $id", $user);
        } catch (\Throwable $th) {
            if ($th->$user) {
                return ApiFormatter::sendResponse(404, false, "Proses gagal! data barang dengan id $id tidak ditemukan", $th->getMessage());
            } else {
                return ApiFormatter::sendResponse(404, false, "Proses gagal!", $th->getMessage());
            }
        }
    }

    public function destroy($id){
        // try{
        //     $user = user::findOrFail($id);

        //     $user->delete();

        //     return response()->json([
        //     'success' => true,
        //     'message' => 'User dihapus Data dengan id $id',
        //         'data' => $user
        //     ],200);
        // } catch(\Throwable $th){
        //     return response()->json([
        //     'success' => false,
        //     'message' => 'Proses gagal! data dengan id $id tidak ditemukan',
        //     ],400);
        // }

        try {
            $user = User::findOrFail($id);

            $user->delete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus data dengan id $id", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function deleted()
    {
        try {
            $user = User::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, true, "Lihat data stock barang yang dihapus", $user);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restore($id)
    {
        // try {
        //     $user = User::onlyTrashed()->where('id', $id);
            
        //     $user->restore();

        //     if ($user) {
        //         $data = user::find($id);
        //         return ApiFormatter::sendResponse(200, true, "Berhasil mengembalikan data yang telah di hapus!", ['id' => $id]);
        //     } else {
        //         return ApiFormatter::sendResponse(404, false, "data stock sudah ada, tidak boleh ada duplikat data stock untuk satu barang silahkan update data stok dengan id stock");
        //     }

            
        // } catch (\Throwable $th) {
        //     return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        // }

        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $has_user = User::where('username', $user->username)->get();

            if ($has_user->count() == 'username') {
                $message = "data user sudah ada, tidak boleh ada duplikat data user untuk satu barang silahkan update data stok dengan id user $user->username";
            } else {
                $user->restore();
                $message = "berhasil mengembalikkan data yang yang telah di hapus";
            }

            return ApiFormatter::sendResponse(200, true, $message, ['id' => $id, 'username' => $user->username]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restoreAll()
    {
        try {
            $user = User::onlyTrashed()->restore();

            return ApiFormatter::sendResponse(200, true, "Berhasil mengembalikan semya data yang telah di hapus!");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $user = User::onlyTrashed()->where('id', $id)->forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen data yang telah dihapus!", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! Silahkan coba lagi!", $th->getMassage);
        }
    }

    public function permanentDeleteAll()
    {
        try {
            $user = User::onlyTrashed()->forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen data yang telah dihapus!");
        } catch (\Throwable $th) {
            //throw $th;
            return ApiFormatter::sendResponse(404, false, "Proses gagal! Silahkan coba lagi!", $th->getMassage);
        }
    }

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
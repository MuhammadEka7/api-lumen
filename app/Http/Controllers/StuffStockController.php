<?php

namespace App\Http\Controllers;

use App\Models\Stuff;
use App\Models\StuffStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormatter;

class StuffStockController extends Controller
{
    public function index()
    {
        $stuffStock = StuffStock::with('stuff')->get();
        // $stuff = Stuff::get();
        // $stock = StuffStock::get();

        // $data = ['barang' => $stuff, 'stock' => $stock];

        return ApiFormatter::sendResponse(200, true, "Lihat semua stock", $stuffStock);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Lihat semua barang masuk',
        //     'data' => $stuffStock
        // ], 200);
    }

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'stuff_id' => 'required',
        //     'total_avaible' => 'required',
        //     'total_defect' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'semua kolom wajib di isi',
        //         'data' => $validator->errors()
        //     ], 400);
        // } else {

        //     $stock = StuffStock::updateOrCreate([
        //         'stuff_id' => $request->input('stuff_id'),
        //     ], [
        //         'total_avaible' => $request->input('total_avaible'),
        //         'total_defect' => $request->input('total_defect'),
        //     ]);

        //     if ($stock) {
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'stock berhasil disimpan',
        //             'data' => $stock,
        //         ], 201);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'stock gagal disimpan',
        //         ], 400);
        //     }
        // }

        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'total_avaible' => 'required',
                'total_defect' => 'required',
            ]);
            $stock = StuffStock::updateOrCreate([
                'stuff_id' => $request->input('stuff_id'),
                'total_avaible' => $request->input('total_avaible'),
                'total_defect' => $request->input('total_defect'),
            ]);
            return ApiFormatter::sendResponse(201, true, 'stock berhasil disimpan!', $stock);
            } catch (\Throwable $th) {
                if ($th->validator->errors()) {
                    return ApiFormatter::sendResponse(400, false, 'Terdapat Kesalahan Input Silahkan Coba Lagi!', $th->validator->errors());
                } else {
                    return ApiFormatter::sendResponse(400, false, 'Terdapat Kesalahan Input Silahkan Coba Lagi!', $th->getMessage());
                }
            }
    }

    public function show($id)
    {
        // try {
        //     $stock = StuffStock::with('stuff')->find($id);

        //     return response()->json([
        //         'success' => true,
        //         'message' => "lihat stock dengan id $id",
        //         'data' => $stock
        //     ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "data stock dengan id $id tidak ditemukan"
        //     ], 404);
        // }

        try {
            $stock = StuffStock::with('stuff')->findOrFail($id);

            return ApiFormatter::sendResponse(200, true, "Lihat stock dengan id $id", $stock);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Data stock dengan id $id tidak ditemukan");
        }
    }

    public function update(Request $request, $id)
    {
        // try {
        //     $stock = StuffStock::with('stuff')->find($id);

        //     $stuff_id = ($request->stuff_id) ? $request->stuff_id : $stock->stuff_id;
        //     $total_avaible = ($request->total_avaible) ? $request->total_avaible : $stock->total_avaible;
        //     $total_defect = ($request->total_defect) ? $request->total_defect : $stock->total_defect;

        //     if ($stock) {
        //         $stock->update([
        //             'stuff_id' => $stuff_id,
        //             'total_avaible' => $total_avaible,
        //             'total_defect' => $total_defect
        //         ]);

        //         return response()->json([
        //             'success' => true,
        //             'message' => "Berhasil mengubah data stock dengan id $id",
        //             'data' => $stock,
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => "Proses Gagal!"
        //         ], 404);
        //     }
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "Proses Gagal! data stock dengan id $id tidak ditemukan!",
        //     ], 404);
        // }

        try {
            $stock = StuffStock::findOrFail($id);

            $stuff_id = ($request->stuff_id) ? $request->stuff_id : $stock->stuff_id;
            $total_avaible = ($request->total_avaible) ? $request->total_avaible : $stock->total_avaible;
            $total_defect = ($request->total_defect) ? $request->total_defect : $stock->total_defect;

            $stock->update([
                'stuff_id' => $stuff_id,
                'total_avaible' => $total_avaible,
                'total_defect' => $total_defect
            ]);

            return ApiFormatter::sendResponse(200, true, "Berhasil mengubah data stock dengan id $id", $stock);
        } catch (\Throwable $th) {
            if ($th->$stock) {
                return ApiFormatter::sendResponse(404, false, "Proses gagal! data stock dengan id $id tidak ditemukan", $th->getMessage());
            } else {
                return ApiFormatter::sendResponse(404, false, "Proses gagal!", $th->getMessage());
            }
        }
    }

    public function destroy($id)
    {
        // try {
        //     $stuffstock = StuffStock::findOrFail($id);

        //     $stuffstock->delete();

        //     return response()->json([
        //         'success' => true,
        //         'message' => "Berhasil hapus data stock dengan id $id",
        //         'data' => ['id' => $id,]
        //     ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "Proses gagal! data stock dengan id $id tidak ditemukan",
        //     ], 404);
        // }

        try {
            $stock = StuffStock::findOrFail($id);

            $stock->delete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus data dengan id $id", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function deleted()
    {
        try {
            $stock = StuffStock::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, true, "Lihat data stock barang yang dihapus", $stock);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $stock = StuffStock::onlyTrashed()->findOrFail($id);
            $has_stock = StuffStock::where('stuff_id', $stock->stuff_id)->get();

            if ($has_stock->count() == 1) {
                $message = "data stock sudah ada, tidak boleh ada duplikat data stock untuk satu barang silahkan update data stok dengan id stock $stock->stuff_id";
            } else {
                $stock->restore();
                $message = "berhasil mengembalikkan data yang yang telah di hapus";
            }

            return ApiFormatter::sendResponse(200, true, $message, ['id' => $id, 'stuff_id' => $stock->stuff_id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restoreAll()
    {
        try {
            $stock = StuffStock::onlyTrashed()->restore();

            return ApiFormatter::sendResponse(200, true, "Berhasil mengembalikan semua data yang telah di hapus!");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $stock = StuffStock::onlyTrashed()->where('id', $id)->forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen data yang telah dihapus!", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! Silahkan coba lagi!", $th->getMassage);
        }
    }

    public function permanentDeleteAll()
    {
        try {
            $user = StuffStock::onlyTrashed();

            $user->forceDelete();

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
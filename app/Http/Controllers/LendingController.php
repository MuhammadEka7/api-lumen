<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\StuffStock;
use App\Models\Lending;
use Illuminate\Http\Request;
use App\Models\Restoration;

class LendingController extends Controller
{
    //





    public function index()
    {
        try {
            $data = Lending::with('stuff', 'user', 'restoration')->get();

            return ApiFormatter::sendResponse(200, true, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, false, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'date_time' => 'required',
                'name' => 'required',
                'total_stuff' => 'required',
            ]);
            // user_id tidak masuk ke validasi karena valuenya bukan bersumber dari luar (dipilih user)

            // cek total_available stuff terkait
            $totalAvaible = StuffStock::where('stuff_id', $request->stuff_id)->value('total_avaible');

            if (is_null($totalAvaible)) {
                return ApiFormatter::sendResponse(400, false, 'bad request', 'Belum ada data inbound!');
            } elseif ((int)$request->total_stuff > (int)$totalAvaible) {
                return ApiFormatter::sendResponse(400, false, 'bad request', 'Stok tidak tersedia!');
            } else {
                $lending = Lending::create([
                    'stuff_id' => $request->stuff_id,
                    'date_time' => $request->date_time,
                    'name' => $request->name,
                    'notes' => $request->notes ? $request->notes : '-',
                    'total_stuff' => $request->total_stuff,
                    'user_id' => auth()->user()->id,
                ]);

                $totalAvaibleNow = (int)$totalAvaible - (int)$request->total_stuff;
                $stuffStock = StuffStock::where('stuff_id', $request->stuff_id)->update(['total_avaible' => $totalAvaibleNow ]);

                $dataLending = Lending::where('id', $lending['id'])->with('user', 'stuff', 'stuff.stuffStock')->first();

                return ApiFormatter::sendResponse(200, true, 'success', $dataLending);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, false, 'bad request', $err->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = Lending::where('id', $id)->with('user', 'restoration', 'stuff', 'stuff.stuffStock')->first();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}

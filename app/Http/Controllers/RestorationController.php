<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use App\Models\Restoration;
use Illuminate\Support\Facades\Validator;
use App\Models\Lending;
use App\Models\StuffStock;

class RestorationController extends Controller
{
    //








    public function store (Request $request, $lending_id)
    {
        try {
            $this->validate($request, [
                'date_time' => 'required',
                'total_good_stuff' => 'required',
                'total_defec_stuff' => 'required',
            ]);

            $lending = Lending::where('id', $lending_id)->first();

            $totalStuffRestoration = (int)$request->total_good_stuff + (int)$request->total_defec_stuff;
            if ((int)$totalStuffRestoration > (int)$lending['total_stuff']) {
                return ApiFormatter::sendResponse(400, 'bad request', 'Total barang kembali lebih banyak dari barang dipinjam');
            } else {
                $restoration = Restoration::updateOrCreate([
                    'lending_id' => $lending_id
                ], [
                    'date_time' => $request->date_time,
                    'total_good_stuff' => $request->total_good_stuff,
                    'total_defec_stuff' => $request->total_defec_stuff,
                    'user_id' => auth()->user()->id,
                ]);

                $stuffStock = StuffStock::where('stuff_id', $lending['stuff_id'])->first();
                $totalAvaibleStock = (int)$stuffStock['total_avaible'] + (int)$request->total_good_stuff;
                $totalDefecStock = (int)$stuffStock['total_defec'] + (int)$request->total_defec_stuff;

                $stuffStock->update([
                    'total_avaible' => $totalAvaibleStock,
                    'total_defec' => $totalDefecStock,
                ]);

                $lendingRestoration = Lending::where('id', $lending_id)->with('user', ' restoration', 'restoration.user', 'stuff', 'stuff.stuffStock')->first();
                return ApiFormatter::sendResponse(200, 'success', $lendingRestoration);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}

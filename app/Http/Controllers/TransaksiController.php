<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Menu;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Implement filter and offset
        $limit = (int)$request->query("limit", 10);
        $offset = (int)$request->query("offset", 0);
        $data = Transaksi::offset($offset)->limit($limit)->get();
        return response()->json([
            "status_code" => 200,
            "data" => [
                "list" => $data,
                "total_items" => Transaksi::count(),
                "offset" => $offset,
                "limit" => $limit
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()) return response()->json([
            "status_code" => 401,
            "message" => "Unauthorized"
        ], 401);

        $fields = $request->validate([
            "nama_pembeli" => "required|string|max:255",
            "uang" => "nullable|integer",
            "menu" => "required|array",
        ]);

        $fields["kasir_id"] = auth()->user()->id;
        $fields["total_bayar"] = 0;

        $menu_holder = [];
        foreach ($fields["menu"] as $menu) {
            // Check if menu array has id and jumlah
            if (!isset($menu["id"]) || !isset($menu["jumlah"])) {
                return response()->json([
                    "status_code" => 400,
                    "message" => "Menu array must have id and jumlah"
                ], 400);
            }
            $data = Menu::find($menu["id"]);
            if (!$data) {
                return response()->json([
                    "status_code" => 400,
                    "message" => "Menu with id " . $menu["id"] . " not found"
                ], 400);
            }

            // Check if jumlah is valid
            if ($menu["jumlah"] <= 0) {
                return response()->json([
                    "status_code" => 400,
                    "message" => "Jumlah must be greater than 0"
                ], 400);
            }

            $data->jumlah = $menu["jumlah"];

            // Push to menu holder
            $menu_holder[] = $data;

            // Calculate total bayar
            $fields["total_bayar"] += $data->harga * $data->jumlah;
        }

        $fields["menu"] = $menu_holder;

        if (!$fields["uang"]) {
            $fields["is_paid"] = 0;
        } else {
            if ($fields["uang"] < $fields["total_bayar"]) {
                return response()->json([
                    "status_code" => 400,
                    "message" => "Uang must be greater than total bayar which is " . $fields["total_bayar"]
                ], 400);
            }
            $fields["kembalian"] = $fields["uang"] - $fields["total_bayar"];
            $fields["is_paid"] = 1;
        }

        $transaksi_data = Transaksi::create($fields);

        foreach ($fields["menu"] as $menu) {
            TransaksiDetail::create([
                "transaksi_id" => $transaksi_data->id,
                "menu_id" => $menu->id,
                "jumlah" => $menu->jumlah
            ]);
        }

        return response()->json([
            "status_code" => 200,
            "data" => $fields
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi_data = Transaksi::find($id);
        $menu = $transaksi_data->menu;
        $transaksi_data->menu = $menu;
        $kasir = $transaksi_data->kasir;
        $transaksi_data->kasir = $kasir;

        if (!$transaksi_data) {
            return response()->json([
                "status_code" => 404,
                "message" => "Transaksi with id " . $id . " not found"
            ], 404);
        }

        return response()->json([
            "status_code" => 200,
            "data" => $transaksi_data
        ], 200);
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

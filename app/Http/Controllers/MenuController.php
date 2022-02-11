<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
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
        $menus = Menu::offset($offset)->limit($limit)->get();
        return response()->json([
            "status_code" => 200,
            "data" => [
                "list" => $menus,
                "total_items" => Menu::count(),
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
        $fields = $request->validate([
            "nama" => "required|string|max:255",
            "deskripsi" => "required|string|max:255",
            "harga" => "required|integer",
            "tipe" => "required|string|in:makanan,minuman",
        ]);

        $menu = Menu::create([
            "nama" => $fields["nama"],
            "deskripsi" => $fields["deskripsi"],
            "harga" => (int)$fields["harga"],
            "tipe" => $fields["tipe"],
        ]);

        return response()->json([
            "status_code" => 200,
            "data" => $menu
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
        $menu = Menu::find($id);

        if (!$menu) return response()->json([
            "status_code" => 404,
            "message" => "Data not found"
        ], 404);

        return response()->json([
            "status_code" => 200,
            "data" => $menu,
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
        $menu = Menu::find($id);

        if (!$menu) return response()->json([
            "status_code" => 404,
            "message" => "Data not found"
        ], 404);

        // Request only
        $fields = $request->only([
            "nama",
            "deskripsi",
            (int)"harga",
            "tipe",
        ]);

        $menu->update($fields);

        return response()->json([
            "status_code" => 200,
            "data" => $menu
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) return response()->json([
            "status_code" => 404,
            "message" => "Data not found"
        ], 404);

        $menu->delete();

        return response()->json([
            "status_code" => 200,
            "message" => "Success"
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    public function findByName($name)
    {
        $artists = Artist::where('name', $name)->first();
        return new ArtistResource(true, 'List Data Artists', $artists);
    }

    public function index()
    {
        $artists = Artist::all();
        return new ArtistResource(true, 'List Data Artists', $artists);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'bio' => 'required',
            'birthdate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/artists', $image->hashName());

        $artist = Artist::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'bio' => $request->bio,
            'birthdate' => $request->birthdate
        ]);

        return new ArtistResource(true, 'Data Artist Berhasil Ditambahkan!', $artist);
    }

    public function show($id)
    {
        $artist = Artist::find($id);
        return new ArtistResource(true, 'Detail Data Artist!', $artist);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'bio' => 'required',
            'birthdate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist not found!',
            ], 404);
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('public/artists', $image->hashName());

            Storage::delete('public/artists/' . basename($artist->image));

            $artist->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'bio' => $request->bio,
                'birthdate' => $request->birthdate
            ]);
        } else {
            $artist->update([
                'name' => $request->name,
                'bio' => $request->bio,
                'birthdate' => $request->birthdate
            ]);
        }

        return new ArtistResource(true, 'Data Artist Berhasil Diubah!', $artist);
    }

    public function destroy($id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist not found!',
            ], 404);
        }

        Storage::delete('public/artists/'.basename($artist->image));

        $artist->delete();

        return new ArtistResource(true, 'Data Artist Berhasil Dihapus!', null);
    }
}

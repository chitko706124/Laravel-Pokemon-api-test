<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItem;
use App\Http\Requests\UpdateItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->q;
        $type = $request->type;
        $power = $request->power;

        $pokemon = Item::when($search !== null, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->when($type !== null, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($power !== null, function ($query) use ($power) {
                $query->where('power', $power);
            })
            ->paginate(10)
            ->withQueryString();

        if ($pokemon->items() == null) {
            return fail('No result found');
        }
        // $data = ItemResource::collection($pokemon)->response()->getData(true);
        return success('success', $pokemon);
    }

    public function store(StoreItem $request)
    {
        $image_name = null;
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $image_name = uniqid() . '_' . time() . '.' . $image_file->getClientOriginalExtension();
            Storage::disk('public')->put('Pokemon/' . $image_name, file_get_contents($image_file));
        }

        $pokemon = new Item();
        $pokemon->name = $request->name;
        $pokemon->type = $request->type;
        $pokemon->power = $request->power;
        $pokemon->qty = $request->qty;
        $pokemon->price = $request->price;
        $pokemon->image = $image_name;
        $pokemon->save();

        // $data = new ItemResource($pokemon);
        return success('Pokemon create successful', $pokemon);
    }

    public function show($id)
    {
        $pokemon = Item::find($id);
        if ($pokemon == null) {
            return fail('No result found');
        }

        // $data = new ItemResource($pokemon);
        return success('Success', $pokemon);
    }

    public function update(UpdateItem $request, $id)
    {
        $pokemon = Item::find($id);
        if ($pokemon == null) {
            return fail('No result found');
        }

        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            Storage::disk('public')->delete('Pokemon/' . $pokemon->image);

            // $image_name = uniqid() . '_' . time() . '.' . $image_file->getClientOriginalExtension();
            Storage::disk('public')->put('Pokemon/' . $pokemon->image, file_get_contents($image_file));
        }

        $pokemon->name = $request->name;
        $pokemon->type = $request->type;
        $pokemon->qty = $request->qty;
        $pokemon->power = $request->power;
        $pokemon->price = $request->price;
        $pokemon->update();

        // $data = new ItemResource($pokemon);
        return success('Pokemon update successful', $pokemon);
    }

    public function destroy($id)
    {
        $pokemon = Item::find($id);
        if ($pokemon == null) {
            return fail('No result found');
        }

        $pokemon->delete();
        return success('Pokemon delete successful', null);
    }
}

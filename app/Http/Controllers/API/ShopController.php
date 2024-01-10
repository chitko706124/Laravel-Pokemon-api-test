<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use App\Http\Requests\Test;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $array = [];
            $total = 0;
            foreach ($request->id as $key => $id) {
                $pokemon = Item::find($id);
                if ($pokemon == null) {
                    return fail('No result found');
                }
                $qty = $request->qty[$key];
                if ($pokemon->qty < $qty) {
                    return fail('Not enough item at ' . $pokemon->name);
                }
                $itemDetails = [
                    'name'  => $pokemon->name,
                    'type'  => $pokemon->type,
                    'price' => $pokemon->price,
                    'power' => $pokemon->power,
                    'qty'   => $qty,
                    'image' => $pokemon->image
                ];

                $array[] = $itemDetails;
                $pokemon->decrement('qty', $qty);
                $pokemon->update();
                $total += $pokemon->price * $qty;
            }
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->data = $array;
            $transaction->total = $total;
            $transaction->save();

            DB::commit();
            return success('Checkout successful', $transaction);
        } catch (\Exception $error) {
            DB::rollback();
            return fail($error);
        }
    }
}

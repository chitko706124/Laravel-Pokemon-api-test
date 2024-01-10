<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->q;
        $users = User::when($search !== null, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })
            ->paginate(10)
            ->withQueryString();

        if ($users->items() == null) {
            return fail('No result found');
        }
        
        $data = UserResource::collection($users)
            ->response()
            ->getData(true);

        return success('Success', $data);
    }
}

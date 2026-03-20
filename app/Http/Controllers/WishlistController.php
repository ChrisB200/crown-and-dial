<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $watches = $request->user()
            ->wishlistWatches()
            ->with(['brand', 'firstImage'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('account.wishlist.index', compact('watches'));
    }

    public function store(Request $request, Watch $watch)
    {
        $request->user()->wishlistWatches()->syncWithoutDetaching([$watch->id]);

        return back()->with('status', 'Watch added to your wishlist.');
    }

    public function destroy(Request $request, Watch $watch)
    {
        $request->user()->wishlistWatches()->detach($watch->id);

        return back()->with('status', 'Watch removed from your wishlist.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($watchId)
    {
        $reviews = Review::with('user')
            ->where('watch_id', $watchId)
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'watch_id' => ['required', 'exists:watches,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $hasPurchased = $user->orders()
            ->whereHas('watches', function ($q) use ($request) {
                $q->where('watch_id', $request->watch_id);
            })
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'You can only review items you purchased.');
        }

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('watch_id', $request->watch_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You already reviewed this product.');
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'watch_id' => $request->watch_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Review submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        abort_if($review->user_id !== $request->user()->id, 403);

        $review->delete();

        return back()->with('success', 'Review deleted');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Product $product): JsonResponse
    {
        $existingReview = Review::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this product.',
            ], 422);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $request->integer('rating'),
            'comment' => $request->string('comment')->toString(),
        ]);

        $product->refreshRatingSummary();

        return ReviewResource::make($review->load('user'))
            ->response()
            ->setStatusCode(201);
    }
}

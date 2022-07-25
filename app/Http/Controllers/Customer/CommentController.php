<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use App\Services\CommentService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    protected $productService;

    public function __construct(CommentService $commentService, ProductService $productService)
    {
        $this->commentService = $commentService;
        $this->productService = $productService;
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Product $product): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CommentResource::collection($this->commentService->getProductComments($product));
    }

    /**
     * @param Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment): CommentResource
    {

        return new CommentResource($this->commentService->show($comment));
    }

    public function store(Product $product, CommentRequest $request)
    {
        $rating = $this->productService->makeRating($request, $product);
        if ($rating) {
            return new CommentResource($this->commentService->store($product, $request->all()));
        }

        return response()->json(['error' => 'Can not make rating'], config('response.HTTP_BAD_REQUEST'));
    }

    public function update(Comment $comment, CommentRequest $request)
    {
        $result = $this->commentService->checkCustomerAuthority($comment);
        if ($result) {
            return $this->commentService->update($comment, $request->all());
        }

        return response()->json(['error' => 'Can not delete comment'], config('response.HTTP_UNAUTHORIZED'));
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment): \Illuminate\Http\JsonResponse
    {
        $result = $this->commentService->checkCustomerAuthority($comment);
        if ($result) {
            if ($this->commentService->delete($comment)) {

                return response()->json(['success' => 'Successfully deleted'], config('response.HTTP_OK'));
            };
        }

        return response()->json(['error' => 'Can not delete comment'], config('response.HTTP_UNAUTHORIZED'));
    }
}

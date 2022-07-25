<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Product;
use App\Repositories\CommentRepository;

class CommentService
{
    protected $commentRepository;
    protected $productService;

    public function __construct(CommentRepository $commentRepository, ProductService $productService)
    {
        $this->commentRepository = $commentRepository;
        $this->productService = $productService;
    }

    public function getProductComments(Product $product): \Illuminate\Database\Eloquent\Collection
    {
        $comments= $product->comments()->latest()->get();
        $comments->load('commentable.images');

        return $comments;
    }

    public function show(Comment $comment): Comment
    {

       return $comment->load(['product', 'commentable.images']);
    }

    public function store(Product $product, array $data)
    {
        $data['commentable_id'] = auth()->user()->id;
        $data['commentable_type'] = 'App\Models\User';
        $data['product_id'] = $product->id;

        return $this->commentRepository->store($data);
    }

    public function checkCustomerAuthority(Comment $comment): bool
    {
        if (auth()->user()->id == $comment->commentable->id) {
            return true;
        }

        return false;
    }

    public function update(Comment $comment, array $data)
    {
        if ($data['rating'] !== $comment->rating) {
            $this->productService->updateRating($comment, $comment->product, $data);
        }

        return $this->commentRepository->update($comment->id, $data);
    }

    public function delete(Comment $comment): bool
    {
        $result = $this->commentRepository->delete($comment->id);
        if ($result) {
            $this->productService->recalculateRating($comment, $comment->product);

            return true;
        }

        return false;
    }
}

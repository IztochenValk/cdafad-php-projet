<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Service\CategoryService;

class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function create(): mixed
    {
        $content = file_get_contents('php://input');

        if (empty($content)) {
            return $this->renderJson([
                'msg' => 'Body JSON manquant'
            ], 400);
        }

        $data = json_decode($content, true);

        if (!is_array($data) || empty($data['name'])) {
            return $this->renderJson([
                'msg' => 'Champ name invalide'
            ], 400);
        }

        $msg = $this->categoryService->saveCategory($data);

        if (stripos($msg, 'ajout') !== false) {
            return $this->renderJson([
                'msg' => $msg
            ], 201);
        }

        return $this->renderJson([
            'msg' => $msg
        ], 400);
    }

    public function getAll(): mixed
    {
        $categories = $this->categoryService->getAllCategories();

        return $this->renderJson($categories, 200);
    }
}

<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\CategoryService;
use App\Service\QuizzService;
use App\Entity\Entity;
use App\Entity\Quizz;
use App\Entity\Category;
use App\Entity\User;

class QuizzController extends AbstractController
{
    private CategoryService $categoryService;
    private QuizzService $quizzService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->quizzService = new QuizzService();
    }

    public function addQuizz(): mixed
    {
        $categories = $this->categoryService->getAllCategories();
        $data = [];
        $data["categories"] = $categories;

        if ($this->isFormSubmitted($_POST)) {
           $data["msg"] = $this->quizzService->addQuizz($_POST);
        }

        return $this->render("add_quizz", "Ajouter un quizz", $data);
    }


    public function showQuizz(int $id): mixed
    {
        $data = [];

        $quizz = $this->quizzService->getQuizz($id);

        if (!$quizz) {
            $data["msg"] = "Quizz introuvable";
            return $this->render("show_quizz", "Afficher un quizz", $data);
        }

        $data["quizz"] = $quizz;

        return $this->render("show_quizz", "Afficher un quizz", $data);
    }


}
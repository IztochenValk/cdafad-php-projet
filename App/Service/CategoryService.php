<?php

namespace App\Service;

use App\Entity\Entity;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Utils\Tools;
use Mithridatem\Validation\Validator;
use Mithridatem\Validation\Exception\ValidationException;

class CategoryService
{
    //Attributs
    private CategoryRepository $categoryRepository;

    //Constructeur
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    //Méthodes
    public function saveCategory(array $post): string
    {
        //Test si les champs sont remplis
        if (empty($post["name"])) {
            return "Veuillez remplir tous les champs du formulaire.";
        }

        //Nettoyage (super globale $_POST)
        Tools::sanitize_array($post);

        //Test si la catégorie existe déja
        if ($this->categoryRepository->isCategoryExists($post["name"])) {
            return "La catégorie " . $post["name"] . " existe déjà en BDD.";
        }
        
        //Création d'un objet Category
        $category = new Category($_POST["name"]);
        $category->setCreatedAt(new \DateTimeImmutable());
        
        try {
            $this->validateCategory($category);

        } catch(ValidationException $ve) {
            return $ve->getMessage();
        }

        //Ajout en BDD
        $this->categoryRepository->save($category);

        return "La catégorie a été ajouté en BDD.";
    }

    public function getAllCategories(): string|array
    {
        $categories = $this->categoryRepository->findAll();
        return empty($categories)? 'liste vide' : $categories;
    }

    /**
     * Méthode pour valider une category
     * @return void
     * @throws ValidationException
     */
    private function validateCategory(Category $category): void
    {
        $validator = new Validator();
        $validator->validate($category);
    }


}

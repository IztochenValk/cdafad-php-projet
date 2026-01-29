<?php

namespace App\Repository;

use App\Repository\AbstractRepository;

use App\Entity\Entity;
use App\Entity\Quizz;
use App\Entity\Media;

class QuizzRepository extends AbstractRepository
{
    /**
     * Méthode pour retourne un Quizz par son id
     * @param int $id du Quizz
     * @return ?Quizz $quizz
     */
    public function find(int $id): ?Quizz
    {
        try {
            $sql = "SELECT q.id, q.title, q.description, m.id AS media_id, m.url, m.alt, c.id AS category_id, c.name
                FROM quizz q
                LEFT JOIN media m ON m.id = q.media_id
                LEFT JOIN quizz_category qc ON qc.quizz_id = q.id
                LEFT JOIN category c ON c.id = qc.category_id
                WHERE q.id = ?
            ";


            $req = $this->connect->prepare($sql);
            $req->execute([$id]);

            $rows = $req->fetchAll(\PDO::FETCH_ASSOC);
            if (!$rows) {
                return null;
            }

            $quizz = new Quizz();
            $quizz->setId((int)$rows[0]["id"]);
            $quizz->setTitle($rows[0]["title"]);
            $quizz->setDescription($rows[0]["description"]);

            if (!empty($rows[0]["m_id"])) {
                $media = new Media();
                $media->setId((int)$rows[0]["m_id"]);
                $media->setUrl($rows[0]["m_url"]);
                $media->setAlt($rows[0]["m_alt"]);
                $media->setMedia($m);
            }

            foreach ($rows as $row) {
                if (!empty($row["c_id"])) {
                    $category = new Category();
                    $category->setId((int)$row["c_id"]);
                    $category->setName($row["c_name"]);
                    $category->addCategory($c);
                }
            }

            return $q;

        } catch (\PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Méthode pour retourner un tableau de Quizz
     * @return array<Quizz>
     */
    public function findAll(): array
    {
        return [];
    }

    /**
     * Méthode pour ajouter un quizz en BDD
     * @param Entity $entity objet Quizz
     * @return Quizz retourne un Objet Quizz(avec son id)
     */
    private function saveQuizz(Entity $entity): ?Quizz
    {
        try {
            //1 Ecrire la requête
            $sql = "INSERT INTO quizz(title, `description`, created_at, author_id)
            VALUE(?,?,?,?)";
            //2 préparer la requête
            $req = $this->connect->prepare($sql);
            //3 Assigner les paramètres (bindValue)
            $req->bindValue(1, $entity->getTitle(), \PDO::PARAM_STR);
            $req->bindValue(2, $entity->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $entity->getCreatedAt()->format("Y-m-d"), \PDO::PARAM_STR);
            $req->bindValue(4, $entity->getAuthor()->getId(), \PDO::PARAM_INT);
            //4 Exécuter la requête
            $req->execute();
            
            //5 récupérer l'id
            $id = $this->connect->lastInsertId();
            $entity->setId($id);

        } catch(\PDOException $e){
            echo $e->getMessage();
        }
        return $entity;
    }

    /**
     * Méthode pour ajouter les categories au quizz
     * dans la table quizz_category
     * @param Quizz $quizz
     * @return void
     */
    private function saveCategories(Quizz $quizz): void
    {
        try {
            //Boucle pour ajouter toutes les categories assignées au quizz
            foreach ($quizz->getCategories() as $category) {
                //1 Ecrire la requête
                $sql = "INSERT INTO quizz_category(quizz_id, category_id)
                VALUE(?,?)";
                //2 préparer la requête
                $req = $this->connect->prepare($sql);
                //3 Assigner les paramètres (bindValue)
                $req->bindValue(1, $quizz->getId(), \PDO::PARAM_INT);
                $req->bindValue(2, $category->getId(), \PDO::PARAM_INT);
                //4 Exécuter la requête
                $req->execute();
            }
            //5 récupérer l'id
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Méthode pour ajouter un quizz et ces categories en BDD
     * @param Entity Quizz $quizz
     * @return Quizz $quizz
     */
    public function save(Entity $entity): Quizz 
    {
        try {
            //Créer le quizz
            $entity = $this->saveQuizz($entity);
            //Assigner les categories
            $this->saveCategories($entity);
        } catch(\PDOException $e) {}
        return $entity;
    }
}

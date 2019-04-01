<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres",methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genres = $repo->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => ["listGenreFull"]
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }
    /**
     * @Route("/api/genres/{id}", name="api_genres_show",methods={"GET"})
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ["listGenreSimple"]
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }
    /**
     * @Route("/api/genres", name="api_genres_create",methods={"POST"})
     */
    public function create(Request $request, ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $genre = new Genre();
        $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
        $errors = $validator->validate($genre);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($genre);
        $manager->flush();
        return new JsonResponse("Le genre à bien été crée", 201, [
            "location" => "api/genres/" . $genre->getId()
        ]);
    }
    /**
     * @Route("/api/genres/{id}", name="api_genres_update",methods={"PUT"})
     */
    public function update(Request $request, Genre $genre, ObjectManager $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
        $errors = $validator->validate($genre);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse("Le genre à bien été modifié", 200, [], true);
    }
    /**
     * @Route("/api/genres/{id}", name="api_genres_delete",methods={"DELETE"})
     */
    public function delete(Genre $genre, ObjectManager $manager)
    {
        $manager->remove($genre);
        $manager->flush();

        return new JsonResponse("Le genre à bien été supprimé", 200, []);
    }
}
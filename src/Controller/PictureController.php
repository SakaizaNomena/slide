<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @Route("/picture", name="app_picture")
     */
    public function index(): Response
    {
        return $this->render('picture/index.html.twig', [
            'controller_name' => 'PictureController',
        ]);
    }
    /**
     * @Route("/insert", name="app_insert")
     */
    public function insert(Request $request, EntityManagerInterface $em)
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class);
        $form->handleRequest($request);
        $images = $form->get('image')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getdata();
            foreach ($images as $image) {
                //donner un nom aleatoire avec un seul unique id
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //On copie l'image dans le dossier publique que nous avons declaré precedament
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $picture->setName($name);
                $picture->setImage($fichier);
                $em->persist($picture);
                $em->flush();
            }
        }
        return $this->render('picture/insert.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/show", name="app_show")
     */
    public function show(PictureRepository $repo)
    {
        $data = $repo->findAll();
        // $temp = array();
        // foreach($data as $obj){
        //     $name = $obj['name'];
        //     $array = array(
        //         "name"=>$name
        //     );
        //     array_push($temp, $array);

        // }
        return $this->render('picture/show.html.twig', [
            'data' => $data,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        //dd($encoder);
        $user = new User();
        $myForm = $this->createForm(RegisterType::class, $user);

        $myForm->handleRequest($request);

        if ($myForm->isSubmitted() && $myForm->isValid()) {
            $user = $myForm->getData();
            //$doctrine = $this->getDoctrine()->getManager();
            $passWord = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($passWord);

            //dd($user);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'myViewForm' => $myForm->createView()
        ]);
    }
}

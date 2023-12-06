<?php

namespace App\Controller\Back;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/back", name="app_back")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="_user", methods={"GET"})
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('back/user/list.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/user", name="_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user", name="_create", methods={"POST"})
     */
    public function create(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword();

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                // l'objet user
                $user,
                // le mot de passer à hasher
                $plaintextPassword
            );
            // je set le password
            $user->setPassword($hashedPassword);
            
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    

    /**
     * @Route("/{id}/update", name="_update", methods={"PUT"})
     */
    public function update(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user, ['custom_option' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_back_user', [], Response::HTTP_SEE_OTHER);
    }
}

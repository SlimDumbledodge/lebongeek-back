<?php

namespace App\Controller\Api;

use App\Service\MyMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_USER') and user === product.getUser()")
     * @Route("/api/contact", name="app_api_contact", methods={"POST"})
     */
    public function contact(Request $request, MyMailer $mailer): JsonResponse
    {
        //recupere le contenu de la requette (json)
        $content = $request->getContent();

        $jsonData = json_decode($content, true);

        $mailer->sendContact(
            $jsonData['from'],
            $jsonData['subject'],
            $jsonData['content']
        );

        return $this->json(['message' => 'Mail envoyé !']);
    }
}

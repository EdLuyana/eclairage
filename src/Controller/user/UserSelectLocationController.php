<?php

namespace App\Controller\user;

use App\Entity\Location;
use App\Form\SelectLocationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserSelectLocationController extends AbstractController
{
#[Route('/select-location', name: 'user_select_location')]
public function selectLocation(Request $request, EntityManagerInterface $em): Response
{
$this->denyAccessUnlessGranted('ROLE_USER');

$form = $this->createForm(SelectLocationForm::class);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
/** @var Location $location */
$location = $form->get('location')->getData();

$request->getSession()->set('selected_location_id', $location->getId());

// ✅ Redirection vers le tableau de bord utilisateur
return $this->redirectToRoute('user_dashboard');
}

return $this->render('user/select_location.html.twig', [
'form' => $form->createView(),
]);
}
}

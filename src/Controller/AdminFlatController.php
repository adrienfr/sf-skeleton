<?php

namespace App\Controller;

use App\Entity\Flat;
use App\Form\AdminEditFlatType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminFlatController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @ParamConverter("flat", class="App:Flat")
     */
    public function editAction(Request $request, Flat $flat)
    {
        $form = $this->createForm(AdminEditFlatType::class, $flat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin_edit_flat', ['id' => $flat->getId()]);
        }

        return $this->render('AdminEditFlat.html.twig', [
            'form' => $form->createView(),
            'flat' => $flat,
        ]);
    }
}

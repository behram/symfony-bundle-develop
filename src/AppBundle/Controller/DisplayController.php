<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DummyEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DisplayController extends Controller
{
    /**
     * test ep display bundle via test template
     * @Route("/test/display", name="app_bundle_display")
     */
    public function displayAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dummyEntity = new DummyEntity();
        $dummyEntity
            ->setTitle('Demo test title')
            ->setDescription('Hello demo test dummy desctiption')
            ;
        $em->persist($dummyEntity);
        $em->flush();

        return $this->render('AppBundle:Display:display.html.twig', [
            'entity' => $dummyEntity
        ]);
    }
}
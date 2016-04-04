<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DummyEntity;
use AppBundle\Entity\DummyRelation;
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
        $dummyEntity = $this->createSampleEntity();

        return $this->render('AppBundle:Display:display.html.twig', [
            'entity' => $dummyEntity
        ]);
    }

    /**
     * @return DummyEntity
     */
    private function createSampleEntity()
    {
        $em = $this->getDoctrine()->getManager();
        $dummyEntity = new DummyEntity();
        $dummyEntity
            ->setTitle('Demo test title')
            ->setDescription('Hello demo test dummy description')
            ->setAvatar('behramcelen.png')
            ->setSampleFile('sample.doc')
        ;

        foreach(range(1,15) as $value){
            $relation = new DummyRelation();
            $relation
                ->setName('Display test dummy entity relation name -> '. $value)
                ->setSummary('Display test dummy entity relation description')
                ;
            $relation->setDummy($dummyEntity);
            $em->persist($relation);
            $dummyEntity->addRelation($relation);
        }

        $em->persist($dummyEntity);
        $em->flush();

        return $dummyEntity;
    }
}
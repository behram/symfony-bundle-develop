<?php

namespace AppBundle\Command;

use AppBundle\Entity\DummyEntity;
use EP\DoctrineLockBundle\Exception\LockedEntityException;
use EP\DoctrineLockBundle\Exception\LockedObjectException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use EP\DoctrineLockBundle\Params\ObjectLockParams;

class LockBundleTestCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var
     */
    private $doctrine;

    protected function configure()
    {
        $this
            ->setName('doctrine:lock:test')
            ->setDescription('doctrine lock bundle test command.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->doctrine = $this->getContainer()->get('doctrine');
        $this->em = $this->doctrine->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //test object based lock system
        $this->testObjectLock($output);

        //reset entity manager
        $this->resetEntityManager();

        //test entity based lock system
        $this->testEntityLock($output);
    }

    private function testEntityLock(OutputInterface $output)
    {
        //create new dummy entity
        $newDummyEntity = new DummyEntity();
        $newDummyEntity
            ->setTitle('Dummy Entity Title 1')
            ->setDescription('Dummy Entity Description 1')
            ->setUpdateLocked(true)
            ->setDeleteLocked(true)
        ;
        $this->em->persist($newDummyEntity);
        $this->em->flush();

        $output->writeln('Lock entity againts update and delete process');

        $output->writeln('update new dummy entity');
        $newDummyEntity
            ->setTitle('Updated new Dummy 1 Title')
            ->setDescription('Updated Dummy 1 Description')
            ->setUpdateLocked(true)
        ;
        $this->em->persist($newDummyEntity);
        try{
            $this->em->flush();
        }catch(LockedEntityException $e){
            $output->writeln($e->getMessage());
        }

        $this->resetEntityManager();
        $newDummyEntity = $this->em->getRepository('AppBundle:DummyEntity')->find($newDummyEntity->getId());

        $output->writeln('Remove Locked Entity!');
        try{
            $this->em->remove($newDummyEntity);
        }catch(LockedEntityException $e){
            $output->writeln($e->getMessage());
        }
        $output->writeln('unlock delete lock');
        $newDummyEntity->setDeleteLocked(false);
        $this->em->remove($newDummyEntity);
        $this->em->flush();
    }
    private function testObjectLock(OutputInterface $output)
    {
        $container = $this->getContainer();
        $objectLocker = $container->get('ep.doctrine.object.locker');

        $dummyEntity = new DummyEntity();
        $output->writeln('### TEST LOCK PROCESS ###');
        //lock fully
        $output->writeln($objectLocker->lock(new DummyEntity()));
        //lock delete process
        $output->writeln($objectLocker->lock(new DummyEntity(), ObjectLockParams::DELETE_LOCK));
        //lock insert process
        $output->writeln($objectLocker->lock(new DummyEntity(), ObjectLockParams::INSERT_LOCK));
        //lock update process
        $output->writeln($objectLocker->lock(new DummyEntity(), ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### TEST UNLOCK PROCESS ###');
        //unlock full lock
        $output->writeln($objectLocker->unlock(new DummyEntity()));
        //unlock delete process
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::DELETE_LOCK));
        //unlock insert process
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::INSERT_LOCK));
        //unlock update process
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### TEST SWITCH PROCESS ###');
        //switch full lock
        $output->writeln($objectLocker->switchLock(new DummyEntity()));
        //switch delete process
        $output->writeln($objectLocker->switchLock(new DummyEntity(), ObjectLockParams::DELETE_LOCK));
        //switch insert process
        $output->writeln($objectLocker->switchLock(new DummyEntity(), ObjectLockParams::INSERT_LOCK));
        //unswitchlock update process
        $output->writeln($objectLocker->switchLock(new DummyEntity(), ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### IS LOCKED TEST ###');
        //switch full lock
        $output->writeln($objectLocker->isLocked(new DummyEntity()));
        //switch delete process
        $output->writeln($objectLocker->isLocked(new DummyEntity(), ObjectLockParams::DELETE_LOCK));
        //switch insert process
        $output->writeln($objectLocker->isLocked(new DummyEntity(), ObjectLockParams::INSERT_LOCK));
        //unswitchlock update process
        $output->writeln($objectLocker->isLocked(new DummyEntity(), ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### OBJECT PERSIST TEST (You must see exception) ###');
        $dummyEntity
            ->setTitle('Dummy Title')
            ->setDescription('Dummy Description')
        ;
        try{
            $this->em->persist(new DummyEntity());
        }catch(LockedObjectException $e){
            $output->write($e->getMessage());
        }

        $output->writeln('unlock full lock');
        $output->writeln($objectLocker->unlock(new DummyEntity()));
        $output->writeln('unlock insert lock');
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::INSERT_LOCK));

        $output->writeln('re-persist unlocked object');
        $this->em->persist($dummyEntity);

        $output->writeln('Update object!');
        $dummyEntity
            ->setTitle('Updated Dummy Title')
            ->setDescription('Updated Dummy Description')
        ;
        $this->em->persist($dummyEntity);
        try{
            $this->em->flush();
        }catch(LockedObjectException $e){
            $output->writeln($e->getMessage());
        }
        $output->writeln('unlock update lock');
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::UPDATE_LOCK));
        $this->em->flush();

        $output->writeln('Remove Locked Object!');
        try{
            $this->em->remove($dummyEntity);
        }catch(LockedObjectException $e){
            $output->writeln($e->getMessage());
        }
        $output->writeln('unlock delete lock');
        $output->writeln($objectLocker->unlock(new DummyEntity(), ObjectLockParams::DELETE_LOCK));
        $this->em->remove($dummyEntity);

        $this->em->flush();
    }

    private function resetEntityManager()
    {
        $this->em = $this->em->create(
            $this->em->getConnection(),
            $this->em->getConfiguration()
        );
    }
}

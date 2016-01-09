<?php

namespace AppBundle\Command;

use AppBundle\Entity\DummyEntity;
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
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $objectLocker = $container->get('ep.doctrine.object.locker');

        $dummyEntity = new DummyEntity();

        $output->writeln('### TEST LOCK PROCESS ###');
        //lock fully
        $output->writeln($objectLocker->lock($dummyEntity));
        //lock delete process
        $output->writeln($objectLocker->lock($dummyEntity, ObjectLockParams::DELETE_LOCK));
        //lock insert process
        $output->writeln($objectLocker->lock($dummyEntity, ObjectLockParams::INSERT_LOCK));
        //lock update process
        $output->writeln($objectLocker->lock($dummyEntity, ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### TEST UNLOCK PROCESS ###');
        //unlock full lock
        $output->writeln($objectLocker->unlock($dummyEntity));
        //unlock delete process
        $output->writeln($objectLocker->unlock($dummyEntity, ObjectLockParams::DELETE_LOCK));
        //unlock insert process
        $output->writeln($objectLocker->unlock($dummyEntity, ObjectLockParams::INSERT_LOCK));
        //unlock update process
        $output->writeln($objectLocker->unlock($dummyEntity, ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### TEST SWITCH PROCESS ###');
        //switch full lock
        $output->writeln($objectLocker->switchLock($dummyEntity));
        //switch delete process
        $output->writeln($objectLocker->switchLock($dummyEntity, ObjectLockParams::DELETE_LOCK));
        //switch insert process
        $output->writeln($objectLocker->switchLock($dummyEntity, ObjectLockParams::INSERT_LOCK));
        //unswitchlock update process
        $output->writeln($objectLocker->switchLock($dummyEntity, ObjectLockParams::UPDATE_LOCK));

        $output->writeln('');
        $output->writeln('### IS LOCKED TEST ###');
        //switch full lock
        $output->writeln($objectLocker->isLocked($dummyEntity));
        //switch delete process
        $output->writeln($objectLocker->isLocked($dummyEntity, ObjectLockParams::DELETE_LOCK));
        //switch insert process
        $output->writeln($objectLocker->isLocked($dummyEntity, ObjectLockParams::INSERT_LOCK));
        //unswitchlock update process
        $output->writeln($objectLocker->isLocked($dummyEntity, ObjectLockParams::UPDATE_LOCK));
    }
}

<?php

namespace AppBundle\Command;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemindCommand extends ContainerAwareCommand{


    protected $em;
    protected $notificationService;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
    public function setNotificationService($srv)
    {
        $this->notificationService = $srv;
    }

    protected function configure()
    {
        $this
            ->setName('notification:remind')
            ->setDescription('Send reminders notifications to users') ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $today = new \DateTime();
        $limitDate = clone $today;
        $limitDate->add(new \DateInterval('P'.(2).'D'));

        $qusers = $this->em->createQuery('SELECT u FROM AppBundle\Entity\User u ');
        $users = $qusers->getResult();


        foreach($users as $u)
        {
            $query = $this->em->createQuery('SELECT i FROM AppBundle\Entity\Item i JOIN i.list  l WHERE l.user = :user AND i.expirationDate >= :today AND i.expirationDate < :limitDate ')
                ->setParameter('user',$u)
                ->setParameter('today',$today)
                ->setParameter('limitDate',$limitDate)   ;

            if(!is_null($query->getResult()))
                foreach($u->getDeviceIds() as $did)
                {
                    if(strlen($did)>2)
                    {
                        $output->writeln("Sending to : ".$did.'  => '.$this->notificationService->push("Test", $did));
                    }

                }

        }


        // $output->writeln($text);
    }
}
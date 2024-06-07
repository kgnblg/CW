<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendNewsletterCommand extends Command
{
    protected static $defaultName = 'app:send-newsletter';
    private ContainerInterface $container;
    private MailerInterface $mailer;

    public function __construct(ContainerInterface $container, MailerInterface $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send newsletter to users')
            ->setHelp("
                This command sends a newsletter email to the users.
                It sends to the active users and registered during last week.
            ");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('SendNewsletterCommand is started!');
        
        $em = $this->container->get('doctrine')->getManager();

        $users = $em
            ->getRepository(User::class)
            ->findByActivityAndCreationDate();
        
        foreach ($users as $user) {
            $output->writeln('Sending newsletter to: ' . $user->getEmail());

            $email = (new Email())
                ->from('hellokaganbalga@CW.com') // sender CW
                ->to($user->getEmail())
                ->subject('CW')
                ->text('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.')
                ->html('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.</p>');
            
            $this->mailer->send($email);
        }

        $output->writeln('SendNewsletterCommand completed the process!');

        return 0;
    }
}
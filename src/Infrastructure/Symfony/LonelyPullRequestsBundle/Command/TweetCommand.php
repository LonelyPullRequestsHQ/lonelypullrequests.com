<?php

namespace LonelyPullRequests\Infrastructure\Symfony\LonelyPullRequestsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TwitterAPIExchange;

class TweetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pullrequests:tweet')
            ->setDescription('Tweet with the twitter credentials')
            ->addArgument('message', InputArgument::REQUIRED, "Message to tweet");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        /** @var TwitterAPIExchange $twitter */
        $twitter = $container->get('twitter_api_exchange');
        $twitter->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
                ->setPostfields([
                    'status' => $input->getArgument('message')
                ])
                ->performRequest();
    }
}

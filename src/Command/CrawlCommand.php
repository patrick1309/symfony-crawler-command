<?php

namespace App\Command;

use Spatie\Crawler\Crawler;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Observer\CrawlObserver as ObserverCrawlObserver;

#[AsCommand(
    name: 'crawl',
    description: 'Crawl website',
)]
class CrawlCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('url', InputOption::VALUE_REQUIRED, 'Url du site à crawler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        $observer = new ObserverCrawlObserver;
        Crawler::create()
            ->setCrawlObserver($observer)
            ->setCrawlProfile(new MyCrawlProfile)
            ->startCrawling($url)
        ;

        $this->showCrawlingReport($io, $observer->getCrawlingResults());

        return Command::SUCCESS;
    }

    private function showCrawlingReport(SymfonyStyle $io, array $results) {
        if (count($results['success'])) {
            $io->success('Successfully crawled urls');
            $io->table(['url', 'HTTP Code', 'Reason', 'Redirect To'], $results['success']);
        }

        if (count($results['failed'])) {
            $io->error('Failed crawled urls');
            $io->listing($results['failed']);
        }
    }
}

class MyCrawlProfile extends CrawlProfile
{
    public function shouldCrawl(UriInterface $url): bool
    {
        if (preg_match('/_profiler/i', $url)) {
            return false;
        }
        return true;
    }
}

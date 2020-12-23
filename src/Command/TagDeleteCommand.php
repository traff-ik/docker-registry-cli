<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category docker-registry-cli
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace Traff\Registry\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Traff\Registry\ClientFactory;
use Traff\Registry\Image;
use Traff\Registry\RegistryClientFactory;

use function Amp\Promise\any;
use function Amp\Promise\wait;

/**
 * Class TagDeleteCommand.
 *
 * @package Traff\Registry\Command
 */
class TagDeleteCommand extends Command
{
    use Traits\Command;

    protected static $defaultName = 'tag:delete';

    public function __construct(
        private ?ClientFactory $client_factory = null,
    ) {
        $this->client_factory ??= new RegistryClientFactory();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete image tag')
            ->addArgument('registry', InputArgument::REQUIRED, 'Registry URL')
            ->addOption('tag', 't', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Image tag');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->logger($output);
        $registry = $this->client_factory->createClient($input->getArgument('registry'), $logger);

        $promises = [];

        foreach ($input->getOption('tag') as $arg_name) {
            [$image_name, $tag_name] = $this->tagName($arg_name);

            $tag = (new Image($image_name, $registry))->createTag($tag_name);

            $logger->debug(\sprintf('Tag object was created: %s', $tag));

            $promise = $tag->delete();
            $promise->onResolve(
                static function (?\Throwable $error = null) use (&$logger, $tag): void {
                    if (null !== $error) {
                        $logger->error(\sprintf('%s: %s',$tag, $error->getMessage()));
                    } else {
                        $logger->info(\sprintf('%s: succeeded', $tag));
                    }
                }
            );

            $promises[(string) $tag] = $promise;
        }

        wait(any($promises));

        return Command::SUCCESS;
    }
}

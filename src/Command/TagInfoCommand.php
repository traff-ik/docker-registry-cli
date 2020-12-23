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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Traff\Registry\ClientFactory;
use Traff\Registry\Image;
use Traff\Registry\RegistryClientFactory;

use function Amp\call;
use function Amp\Promise\all;
use function Amp\Promise\wait;

/**
 * Class ImageCommand.
 *
 * @package Traff\Registry\Command
 */
class TagInfoCommand extends Command
{
    use Traits\Command;

    protected static $defaultName = 'tag:info';

    public function __construct(
        private ?ClientFactory $client_factory = null
    ) {
        $this->client_factory ??= new RegistryClientFactory();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Image tag info')
            ->addArgument('registry',InputArgument::REQUIRED, 'Registry URL')
            ->addOption('tag', 't', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Image tag');
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

            $promises[(string) $tag] = call(
                static function () use ($tag): \Generator {
                    try {
                        return \sprintf('<fg=white>%s</>', yield $tag->getDigest());
                    } catch (\Exception $e) {
                        return \sprintf('<fg=red>%s</>', $e->getMessage());
                    }
                }
            );
        }

        $tag_actions = wait(all($promises));

        $table = new Table($output);
        $table->setHeaders(['Tag', 'Digest']);

        foreach ($tag_actions as $tag_name => $result) {
            $table->addRow([$tag_name, $result]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}

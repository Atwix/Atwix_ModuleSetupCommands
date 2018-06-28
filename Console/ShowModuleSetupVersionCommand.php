<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2018 Atwix (https://www.atwix.com/)
 */
declare(strict_types=1);

namespace Atwix\ModuleSetupCommands\Console;

use Magento\Framework\Module\ModuleResource;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShowModuleSetupVersionCommand
 */
class ShowModuleSetupVersionCommand extends ConsoleCommand
{
    /**
     * @var ModuleResource
     */
    protected $setupModuleResource;

    /**
     * @param ModuleResource $setupModuleResource
     */
    public function __construct(
        ModuleResource $setupModuleResource
    ) {
        $this->setupModuleResource = $setupModuleResource;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('atwix:setup-module:show');
        $this->setDescription('Show versions of modules');

        $this->addArgument(
            'modules',
            InputArgument::IS_ARRAY,
            'Module Names'
        );

        return parent::configure();
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleNames = $input->getArgument('modules');

        foreach ($moduleNames as $moduleName) {
            $schemaVersion = $this->setupModuleResource->getDbVersion($moduleName);
            $dataVersion = $this->setupModuleResource->getDataVersion($moduleName);

            $output->writeln((string)__(
                '<info>%1 module</info>: %2 : %3',
                $moduleName,
                $schemaVersion,
                $dataVersion
            ));
        }
    }

}
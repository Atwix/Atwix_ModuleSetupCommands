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
 * Class SetModuleSetupVersionCommand
 */
class SetModuleSetupVersionCommand extends ConsoleCommand
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
        $this->setName('atwix:setup-module:set');
        $this->setDescription('Set module versions');

        $this->addArgument(
            'module',
            InputArgument::REQUIRED,
            'Module Name'
        );

        $this->addArgument(
            'schema-version',
            InputArgument::REQUIRED,
            'Module Schema Version'
        );

        $this->addArgument(
            'data-version',
            InputArgument::OPTIONAL,
            'Module Data Version'
        );

        return parent::configure();
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('module');
        $schemaVersion = $input->getArgument('schema-version');
        $dataVersion = $input->getArgument('data-version');

        if (!$dataVersion) {
            $dataVersion = $schemaVersion;
        }

        $this->setupModuleResource->setDbVersion($moduleName, $schemaVersion);
        $this->setupModuleResource->setDataVersion($moduleName, $dataVersion);

        $output->writeln((string)__(
            '<info>%1 module version has been changed ( %2 : %3 )</info>',
            $moduleName,
            $schemaVersion,
            $dataVersion
        ));
    }

}
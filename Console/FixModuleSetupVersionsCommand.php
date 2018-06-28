<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2018 Atwix (https://www.atwix.com/)
 */
declare(strict_types=1);

namespace Atwix\ModuleSetupCommands\Console;

use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Module\ModuleResource;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixModuleSetupVersionsCommand
 */
class FixModuleSetupVersionsCommand extends ConsoleCommand
{
    /**
     * @var ModuleResource
     */
    protected $setupModuleResource;

    /**
     * @var ModuleListInterface
     */
    protected $moduleList;

    /**
     * @param ModuleResource $setupModuleResource
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        ModuleResource $setupModuleResource,
        ModuleListInterface $moduleList
    ) {
        parent::__construct();

        $this->setupModuleResource = $setupModuleResource;
        $this->moduleList = $moduleList;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('atwix:setup-module:fix-versions');
        $this->setDescription('Fix inconsistency in module versions (between DB and module.xml)');

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
        $moduleNames = $input->getArgument('modules') ?? $this->moduleList->getNames();

        foreach ($moduleNames as $moduleName) {
            $moduleXmlConfig = $this->moduleList->getOne($moduleName);

            if (!isset($moduleXmlConfig['setup_version'])) {
                continue;
            }

            $currentModuleVersion = $moduleXmlConfig['setup_version'];
            $schemaVersion = $this->setupModuleResource->getDbVersion($moduleName);
            $dataVersion = $this->setupModuleResource->getDataVersion($moduleName);

            if (version_compare($currentModuleVersion, $schemaVersion, '<')) {
                $this->setupModuleResource->setDbVersion($moduleName, $schemaVersion);

                $output->writeln((string)__('<info>%1 module: %2 -> %3 (Schema)</info>'));
            }

            if (version_compare($currentModuleVersion, $dataVersion, '<')) {
                $this->setupModuleResource->setDataVersion($moduleName, $dataVersion);

                $output->writeln((string)__('<info>%1 module: %2 -> %3 (Data)</info>'));
            }
        }

        $output->writeln((string)__('<info>Modules have been checked</info>'));
    }

}
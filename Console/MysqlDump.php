<?php
namespace Hammer\MysqlDumpCommand\Console;


use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class Dump
 * @package Hammer\MysqlDumpCommand\Console
 */
class MysqlDump extends Command
{
    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var File
     */
    private $file;

    public function __construct(
        DeploymentConfig $deploymentConfig,
        DirectoryList $directoryList,
        File $file
    ) {
        $this->deploymentConfig = $deploymentConfig;
        $this->directoryList = $directoryList;
        $this->file = $file;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('hammer:dump')
            ->setDescription('Db dump using mysql command');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting the DB Backup ');

        $dbInfo = $this->deploymentConfig->get('db')['connection']['default'];

        $today = getdate();
        $user = $dbInfo['username'];
        $host = $dbInfo['host'];
        $pass = $dbInfo['password'];
        $dbname = $dbInfo['dbname'];

        $filename = $dbname . '-' . $today['mon'] . '-' . $today['mday'] . '-' . $today['hours'] . '-' . $today['minutes'] . '.sql';

        $backupsDir = $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/backups';

        if (!$this->file->isExists($backupsDir)) {
            $this->file->createDirectory($backupsDir);
        }

        $destination = $backupsDir . '/' . $filename;
        $commmand = 'mysqldump -u' . $user . ' -h' . $host . ' -p' . $pass . ' ' . $dbname . ' >>' . $destination;
        
        shell_exec($commmand);

        $output->writeln('Dump done in ' . $destination);

    }

}
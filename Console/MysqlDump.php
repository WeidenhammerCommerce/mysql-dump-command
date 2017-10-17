<?php
namespace Hammer\MysqlDumpCommand\Console;


use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\DeploymentConfig\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function __construct(
        DeploymentConfig $deploymentConfig
    ) {
        $this->deploymentConfig = $deploymentConfig;
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
        $dbInfo = $this->deploymentConfig->get('db')['connection']['default'];

        $today = getdate();
        $user = $dbInfo['username'];
        $host = $dbInfo['host'];
        $pass = $dbInfo['password'];
        $dbname = $dbInfo['dbname'];

        $filename = $dbname . '-' . $today['mon'] . '-' . $today['mday'] . '-' . $today['hours'] . '-' . $today['minutes'] . '.sql';

        $dir = 'var/support/' . $filename;
        $commmand = 'mysqldump -u' . $user . ' -h' . $host . ' -p' . $pass . ' ' . $dbname . ' >>' . $dir;
        
        shell_exec($commmand);

        $output->writeln('Dump done in ' . $dir);

    }

}
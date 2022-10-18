<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Modules\Core\Console\Installers\Installer;
use Modules\Core\Console\Installers\Traits\BlockMessage;
use Modules\Core\Console\Installers\Traits\SectionMessage;
use Symfony\Component\Console\Input\InputOption;

class SimpleInstallCommand extends Command
{
    use BlockMessage, SectionMessage;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asgard:simple-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple Install Asgard CMS, assuming that the .env file with connection to database is already set';

    /**
     * @var Installer
     */
    private $installer;

    /**
     * Create a new command instance.
     *
     * @param Installer $installer
     * @internal param Filesystem $finder
     * @internal param Application $app
     * @internal param Composer $composer
     */
    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->getLaravel()['env'] = 'local';
        $this->installer = $installer;
    }

    /**
     * Execute the actions
     *
     * @return mixed
     */
    public function handle()
    {
        $this->blockMessage('Welcome!', 'Starting the installation process...', 'comment');

        $success = $this->installer->stack([
            \Modules\Core\Console\Installers\Scripts\SetAppKey::class,
            \Modules\Core\Console\Installers\Scripts\ConfigureUserProvider::class,
            \Modules\Core\Console\Installers\Scripts\ModuleMigrator::class,
            \Modules\Core\Console\Installers\Scripts\ModuleSeeders::class,
            \Modules\Core\Console\Installers\Scripts\ModuleAssets::class,
            \Modules\Core\Console\Installers\Scripts\ThemeAssets::class,
            \Modules\Core\Console\Installers\Scripts\SetInstalledFlag::class,
        ])->install($this);

        if ($success) {
            $this->info('Platform ready! You can now login with your username and password at /backend');
        }
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force the installation, even if already installed'],
        ];
    }
}

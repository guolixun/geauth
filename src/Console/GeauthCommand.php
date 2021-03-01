<?php

namespace Bennent\Geauth\Console;

use Illuminate\Console\Command;

class GeauthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geauth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the rbac-auth-for-laravel package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        parent::__construct();
    }*/

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //$this->initDatabase();

        $this->initAdminDirectory();
    }

    /**
     * @notes:Create tables and seed it
     * @user: Bennent_G
     */
    protected function initDatabase()
    {
        $this->call('migrate');

        $userModel = config('admin.database.users_model');

        if ($userModel::count() == 0) {
            $this->call('db:seed', ['--class' => \Bennent\Geauth\Models\UserTableSeeder::class]);
        }
    }

    /**
     * Initialize the ge-auth-admin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('admin.directory');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error>");
        }

        $this->makeDir();

        $this->line('<info>Admin directory was created:</info> '.str_replace(base_path(), '', $this->directory));

        $this->createHomeController();
        $this->createAdminRoute();
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory.'/HomeController.php';
        $contents       = $this->getStub('HomeController');

        $this->laravel['files']->put(
            $homeController,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>HomeController file was created:</info> '.str_replace(base_path(), '', $homeController));
    }

    /**
     * Create route file.
     *
     * @return void
     */
    public function createAdminRoute()
    {
        $file     = config('admin.route.path').'/admin.php';
        $contents = $this->getStub('admin');

        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRepo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Repository and Interface';
    private $REPO_DIR       = "app/Repositories";  // Directory for repo/eloquent
    private $INTERFACE_DIR  = "app/RepositoriesInterface";   // Directory for interface
    protected $repo_name      = "";                     // name of repo to be created eg: {$repo_name}Repo.php or {$repo_name}Interface.php

    // it assumes BaseRepo and BaseInterface are in their respective DIR
    // eg: $REPO_DIR/$BASE_REPO or $INTERFACE_DIR/$BASE_INTERFACE
    protected $BASE_REPO      = "BaseRepository";             // file name of BaseRepo without .php
    protected $BASE_INTERFACE = "BaseRepositoryInterface";        // file name of BaseInterface without .php
    /**


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->repo_name = $this->ask("Name for repo and interface");

        $repo_content       = $this->generateRepo();
        $interface_content  = $this->generateInterface();

        echo $this->repo_name."Repo.php: \n" . $repo_content . "\n";
        echo $this->repo_name."RepositoryInterface.php: \n" . $interface_content . "\n";

        $confirmation = $this->askWithCompletion("Confirm creation ? (y/N) ", ["y","n"], "n");
        if($confirmation == "n") {
            exit();
        }
        $this->generateFiles($repo_content, $interface_content);
        echo "Files have been created successfully";

    }

    private function generateFiles($repo_content, $interface_content)
    {
        $repo_name      =  $this->repo_name . "Repository.php";
        $interface_name =  $this->repo_name . "RepositoryInterface.php";
        $repo_path      =  base_path() . "/" . $this->REPO_DIR .  "/" . $repo_name;
        $interface_path =  base_path() . "/" . $this->INTERFACE_DIR .  "/" . $interface_name;

        $create_repo    =  $this->fileCheck($repo_path, $repo_name);
        if($create_repo) {
            file_put_contents($repo_path, $repo_content);
        }

        $create_interface =  $this->fileCheck($interface_path, $interface_name);
        if($create_interface) {
            file_put_contents($interface_path, $interface_content);
        }

    }

    private function fileCheck($path, $name): bool
    {
        if(file_exists($path)) {
            $confirmation = $this->askWithCompletion("Warning: $name already exists,Overwrite existing file ? (y/N)", ["y","n"], "n");
            if ($confirmation == "n") {
                echo "Cancelled creationg of $name" . "\n";
                return false;
            }
        }
        return true;
    }
    private function generateRepo()
    {
        $content = <<<EOF
    <?php
    namespace {$this->getNamespace()[0]};
    use {$this->getUsePath()[0]};
    use {$this->getUsePath("INTERFACE")[0]};

    class {$this->repo_name}Repository extends {$this->BASE_REPO} implements {$this->repo_name}RepositoryInterface
    {
        public function __construct(\$model)
        {
             parent::__construct(\$model);
        }
    }
    EOF;
        return $content;
    }

    private function generateInterface()
    {
        $content = <<<EOF
    <?php
    namespace {$this->getNamespace()[1]};
    use {$this->getUsePath()[1]};

    interface {$this->repo_name}RepositoryInterface extends {$this->BASE_INTERFACE}
    {
    }
    EOF;
        return $content;
    }
    private function getNamespace()
    {
        $repo      = str_replace('/', "\\", $this->REPO_DIR);
        $repo      = str_replace('app', 'App', $repo);
        $interface = str_replace('/', '\\', $this->INTERFACE_DIR);
        $interface = str_replace('app', 'App', $interface);
        return [$repo,$interface];
    }
    private function getUsePath($name = "BASE")
    {
        if ($name == "INTERFACE") {
            $interface = str_replace('/', '\\', "$this->INTERFACE_DIR/$this->repo_name"."RepositoryInterface");
            $interface = str_replace('app', 'App', $interface);
            return [$interface];
        }
        if ($name == "BASE") {
            $base      = str_replace('/', '\\', "$this->REPO_DIR/$this->BASE_REPO");
            $base      = str_replace('app', 'App', $base);
            $interface = str_replace('/', '\\', "$this->INTERFACE_DIR/$this->BASE_INTERFACE");
            $interface = str_replace('app', 'App', $interface);
            return [$base,$interface];
        }
    }
}

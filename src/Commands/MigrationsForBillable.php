<?php

namespace Marqant\MarqantPay\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Marqant\MarqantPay\Traits\Billable;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Finder\SplFileInfo;

class MigrationsForBillable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marqant-pay:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create migrations for billable model(s) from marqant-pay.billables config.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get all billable models from config
        $billables = collect(config('marqant-pay.billables'));

        $billables->each(function ($model_name) {
            $this->line("create migration for '$model_name' model");

            $Billable = $this->getBillableModel($model_name);
            if (is_null($Billable)) {
                return;
            }

            $this->makeMigrationForBillable($Billable);

            $this->line("completed processing '$model_name' model");
        });

        $this->info('Done! 👍');
    }

    /**
     * Resolve model name to a model with the Billable trait attached.
     *
     * @param string $model_name
     *
     * @return Model|null
     */
    private function getBillableModel(string $model_name)
    {
        $Billable = app($model_name);

        $can_continue = $this->checkIfModelIsBillable($Billable);
        if ($can_continue === false) {
            return null;
        }

        return $Billable;
    }

    /**
     * Ensure, that the given model actually uses the Billable trait.
     * If it doesn't, print out an error message and exit the command.
     *
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return bool
     */
    private function checkIfModelIsBillable(Model $Billable): bool
    {
        $traits = class_uses($Billable);

        if (!collect($traits)->contains(Billable::class)) {
            $this->alert('The given model '. get_class($Billable) . ' is not a Billable.');

            return false;
        }

        return true;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return string
     */
    private function getTableFromBillable(Model $Billable): string
    {
        return $Billable->getTable();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $Billable
     *
     * @return void
     */
    private function makeMigrationForBillable(Model $Billable)
    {
        $table = $this->getTableFromBillable($Billable);

        $this->makeMigration($table);
    }

    /**
     * @param string $table
     */
    private function makeMigration(string $table)
    {
        $path = database_path('migrations');

        // no need to create migration if it is already exists
        $can_continue = $this->preventDuplicates($path, $table);
        if ($can_continue === false) {
            return;
        }

        $stub_path = $this->getStubPath();

        $stub = $this->getStub($stub_path);

        $this->replaceClassName($stub, $table);

        $this->replaceTableName($stub, $table);

        $this->saveMigration($stub, $table);
    }

    /**
     * @return string
     */
    private function getStubPath(): string
    {
        $stub_path = base_path('vendor/marqant-lab/marqant-pay/stubs/migration.stub');

        return $stub_path;
    }

    /**
     * Returns the blueprint for the migration about to be created.
     *
     * @param string $stub_path
     *
     * @return string
     */
    private function getStub(string $stub_path): string
    {
        return file_get_contents($stub_path);
    }

    /**
     * @param string $stub
     *
     * @param string $table
     *
     * @return string
     */
    private function replaceClassName(string &$stub, string $table): string
    {
        // table => Table
        $table = ucfirst($table);

        $class_name = "AddMarqantPayFieldsTo{$table}Table";

        $stub = str_replace('{{CLASS_NAME}}', $class_name, $stub);

        return $stub;
    }

    /**
     * @param string $stub
     *
     * @param string $table
     *
     * @return string
     */
    private function replaceTableName(string &$stub, string $table): string
    {
        $stub = str_replace('{{TABLE_NAME}}', $table, $stub);

        return $stub;
    }

    /**
     * @param string $stub
     *
     * @param string $table
     *
     * @return void
     */
    private function saveMigration(string $stub, string $table): void
    {
        $file_name = $this->getMigrationFileName($table);

        $path = database_path('migrations');

        $can_continue = $this->preventDuplicates($path, $table);
        if ($can_continue === false) {
            return;
        }

        File::put($path . '/' . $file_name, $stub);
    }

    /**
     * @return string
     */
    private function getMigrationPrefix(): string
    {
        return date('Y_m_d_His');
    }

    /**
     * @param string $table
     *
     * @return string
     */
    private function getMigrationFileName(string $table): string
    {
        $prefix = $this->getMigrationPrefix();

        return $prefix . "_add_marqant_pay_fields_to_{$table}_table.php";
    }

    /**
     * Check if migration already exists
     *
     * @param string $path
     *
     * @param string $table
     *
     * @return bool - true if can continue, false if find migration
     */
    private function preventDuplicates(string $path, string $table): bool
    {
        $file = "add_marqant_pay_fields_to_{$table}_table.php";

        $files = collect(File::files($path))
            ->map(function (SplFileInfo $file) {
                return $file->getFilename();
            })
            ->map(function (string $file_name) {
                return preg_replace('/[0-9]{4}_[0-9]{2}_[0-9]{2}_[0-9]{6}_/', '', $file_name);
            });

        if ($files->contains($file)) {
            $this->alert("Migration for marqant pay fields on '{$table}' already exists.");

            return false;
        }

        return true;
    }
}

<?php

namespace Marqant\MarqantPay\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class UpdateProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marqant-pay:providers:update {--delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the list of providers in the database based on the gateways list of the marqant-pay config.';

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
     * @return int
     */
    public function handle()
    {
        // get the provider model from the config
        $provider_model = app(config('marqant-pay.provider_model'));

        // get the list of providers from the configuration
        $providers_from_config = collect(config('marqant-pay.gateways', []))->keys();

        // get the providers from the database
        $providers_from_database = $provider_model->all()->pluck('slug');

        // add the providers
        $providers_from_config->each(function ($provider) use ($provider_model) {
            $provider_model->updateOrCreate([
                'slug' => $provider
            ], [
                'name' => Str::title($provider)
            ]);
        });

        // delete providers that are not present in the config
        if ($this->option('delete')) {
            // get the list of providers to delete
            $providers_to_delete = $providers_from_database->diff($providers_from_config);

            // delete the providers from the database
            //   that are not listed in the config
            $provider_model->whereIn('slug', $providers_to_delete)->delete();

            // let the user know which entries we have removed
            if ($providers_to_delete->count()) {
                $this->info('Removed ' . $providers_to_delete->count() . ' providers: ' . $providers_to_delete->join(', '));
            } else {
                $this->info('No providers to remove 🤷');
            }
        }

        // give back some info
        $this->info('Providers have been updated ✅');

        return 0;
    }
}

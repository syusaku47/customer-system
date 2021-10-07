<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        Blueprint::macro('companyColumns', function () {
//            $this->integer('company_id')->default(1)->comment('契約会社マスタID')->unsigned();
//            $this->foreign('company_id')->references('id')->on('m_contract_companies');
//        });
        Blueprint::macro('dropCompanyColumns', function () {
            $this->dropColumn('company_id');
        });
    }
}

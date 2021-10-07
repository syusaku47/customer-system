<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(MAreasTableSeeder::class);
        $this->call(MBuildingsTableSeeder::class);
        $this->call(MCustomerEstimatedRanksTableSeeder::class);
        $this->call(MCustomerRankKojisTableSeeder::class);
        $this->call(MCustomerRankLastCompletionsTableSeeder::class);
        $this->call(MStoresTableSeeder::class); // 店舗マスタを社員マスタより先にseedする
        $this->call(MEmployeesTableSeeder::class);
        $this->call(MLostordersTableSeeder::class);
        $this->call(MMadorisTableSeeder::class);
        $this->call(MMyCarTypesTableSeeder::class);
        $this->call(MPartsTableSeeder::class);
        $this->call(MProjectRanksTableSeeder::class);
        $this->call(MSourcesTableSeeder::class);
        $this->call(MTagsTableSeeder::class);
        $this->call(TCustomersTableSeeder::class);
        $this->call(TFamiliesTableSeeder::class);
        $this->call(TPetsTableSeeder::class);
        $this->call(TProjectsTableSeeder::class);
        $this->call(MTaxesTableSeeder::class);
        $this->call(TMaintenancesTableSeeder::class);
        $this->call(TQuotesTableSeeder::class);
        $this->call(TQuoteDetailsTableSeeder::class);
        $this->call(MCategoriesTableSeeder::class); // 大分類マスタを中分類マスタより先にseedする
        $this->call(MSubCategoriesTableSeeder::class);
        $this->call(MDetailsTableSeeder::class);
        $this->call(MInquiriesTableSeeder::class);
        $this->call(MQuotefixsTableSeeder::class);
        $this->call(MSignaturesTableSeeder::class);
        $this->call(TSupportsTableSeeder::class);
        $this->call(TOrdersTableSeeder::class);
        $this->call(TFilesTableSeeder::class);
        $this->call(MAfterMaintenancesTableSeeder::class);
        $this->call(MCreditsTableSeeder::class);
        $this->call(MFieldConfirmItemsTableSeeder::class);
        $this->call(MKojiConfirmItemsTableSeeder::class);
        $this->call(MSupportsTableSeeder::class);
        $this->call(TCustomerRankLogsTableSeeder::class);
    }
}

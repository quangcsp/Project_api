<?php

use App\Eloquent\Office;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('offices')->truncate();

        app(Office::class)->insert([
            [
                'name' => 'Ha Noi Office',
                'area' => 'Ha Noi Office',
                'wsm_workspace_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Da Nang Office',
                'area' => 'Da Nang Office',
                'wsm_workspace_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'HCMC Office',
                'area' => 'HCMC Office',
                'wsm_workspace_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tran Khat Chan',
                'area' => 'Tran Khat Chan',
                'wsm_workspace_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Handico Office',
                'area' => 'Handico Office',
                'wsm_workspace_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

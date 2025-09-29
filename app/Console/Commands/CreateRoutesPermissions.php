<?php

namespace App\Console\Commands;

use App\Permissions\PermissionsModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class CreateRoutesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:permission-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a permission routes.';

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
     */
    public function handle()
    {
        $models = PermissionsModel::$models;

        foreach ($models as $key => $model) {
            foreach ($model as $key2 => $value) {
                $permissionName = $key.".".$value;
                $permission = Permission::where('name', $permissionName)->first();

                if (is_null($permission)) {
                    permission::create(['name' => $permissionName]);
                }
            }
        }

        $this->info('Permission routes added successfully.');
    }
}

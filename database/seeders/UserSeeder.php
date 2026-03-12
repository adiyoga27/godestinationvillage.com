<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VillageDetail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      // Role Admin 
      $adminRole = new Role();
      $adminRole->name = "admin";
      $adminRole->guard_name = "web";
      $adminRole->save();

      // Role Agent
      $agentRole = new Role();
      $agentRole->name = "village";
      $agentRole->guard_name = "web";
      $agentRole->save();

      // Role Customer
      $customerRole = new Role();
      $customerRole->name = "customer";
      $customerRole->guard_name = "web";
      $customerRole->save();

      // Admin User
      $admin = new User();
      $admin->name = 'Admin';
      $admin->email = 'admin@godevi.com';
      $admin->email_verified_at = Carbon::now();
      $admin->password = bcrypt('admingodevi');
      $admin->is_active = 1;
      $admin->role_id = 1;
      $admin->phone = '085738009350';
      $admin->address = 'Gianyar';
      $admin->save();
      $admin->assignRole($adminRole);  

      // Agent User
      $agent = new User();
      $agent->name = 'Village';
      $agent->email = 'village@godevi.com';
      $agent->email_verified_at = Carbon::now();
      $agent->password = bcrypt('villagegodevi');
      $agent->is_active = 1;
      $agent->role_id = 2;
      $agent->phone = '085738009350';
      $agent->address = 'Gianyar';
      $agent->save();
      $agent->assignRole($agentRole); 

      $agent_detail = new VillageDetail();
      $agent_detail->user_id = $agent->id;
      $agent_detail->village_name = 'Mas Village';
      $agent_detail->village_address = "Gianyar, Ubud";
      $agent_detail->save();

      // Customer User
      $customer = new User();
      $customer->name = 'Customer';
      $customer->email = 'customer@godevi.com';
      $customer->email_verified_at = Carbon::now();
      $customer->password = bcrypt('customergodevi');
      $customer->is_active = 1;
      $customer->role_id = 3;
      $customer->phone = '085738009350';
      $customer->address = 'Gianyar';
      $customer->save();
      $customer->assignRole($customerRole);   

    }
}

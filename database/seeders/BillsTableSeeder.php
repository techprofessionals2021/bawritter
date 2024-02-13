<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BillService;
use App\Models\User;

class BillsTableSeeder extends Seeder
{
    private $faker;
    private $billService;

    public function __construct(BillService $billService)
    {
        $this->faker = \Faker\Factory::create();
        $this->billService = $billService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::role('staff')->has('unbilled_tasks')->get();

        if ($users->count() > 0) {
            $user = $users->first();

            $user->unbilled_tasks()
                ->orderBy('id', 'DESC')
                ->chunk(4, function ($unbilled_tasks) use ($user) {
                    $date = collect($unbilled_tasks->toArray())->max('created_at');
                    $max_order_date = (new \DateTime($date))->format('Y-m-d H:i:s');

                    $bill = $this->billService->create([
                        'name' => $user->full_name,
                        'address' => $user->meta('address'),
                        'user_id' => $user->id,
                    ], $unbilled_tasks);

                    $subject = anchor($bill->number, route('bills_show', $bill->id));
                    $log = logActivity($bill, 'requested for payment ' . $subject, $bill->from);
                    $log->created_at = $max_order_date;
                    $log->save();

                    if ($bill && $this->faker->randomElement([1, 2]) == 1) {
                        // Mark the bill as paid
                        $bill->update(['paid' => date("Y-m-d", strtotime($max_order_date))]);

                        // Log user's activity
                        $adminUser = User::role('admin')->first();
                        $subject = anchor($bill->number, route('bills_show', $bill->id));
                        $log = logActivity($bill, 'marked as paid ' . $subject, $adminUser);
                        $log->created_at = $max_order_date;
                        $log->save();
                    }
                });
        }
    }
}

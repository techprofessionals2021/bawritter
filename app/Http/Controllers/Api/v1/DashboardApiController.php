<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\models\User;
use App\models\Order;
use App\models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;

class DashboardApiController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'dashboard']);
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['activities'] = Activity::limit(5)->orderBy('created_at', 'DESC')->get();

        return apiResponseSuccess($data,' Latest 5 Activity Logs');
    }

    public function statistics()
    {
        $data = [
            'usersCount' => $this->usersCount(),
            'ordersCount' => $this->ordersCount(),
            'paidBillsAmount' => $this->paidBillsAmount(),
            'profitAmount' => $this->profitAmount(),
        ];
        
     if($data){

     return apiResponseSuccess($data,' Monthly Activities Counts');

     }

     else{
     return responseError('$data','no data error');

     }

    }

      function income_graph()

      {
          $data = [];
          for ($i = 4; $i >= 0; $i = $i - 1) {
              $date = now()->subMonths($i);

              $start  = $date->copy()->startofMonth()->toDateTimeString();
              $end   = $date->copy()->endofMonth()->toDateTimeString();

              $profit= $this->getProfit($start, $end);
              $data['values'][] = $profit;
              $data['labels'][] = $date->format('F');
              $data['formatted_values'][$profit] = format_money($profit);
          }

          if($data){
            return apiResponseSuccess($data,'income graph report data');
          } else{
            return responseError($data,'Error');
          }

      }

      private function getProfit($start, $end)
      {
          return Order::where('order_status_id', ORDER_STATUS_COMPLETE)
              ->whereBetween('created_at', [$start, $end])
              ->sum(DB::raw('total - IFNULL(staff_payment_amount, 0)'));
      }

      private function usersCount()
      {
          return User::whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->subDays(7)->toDateString(), Carbon::today()->toDateString()])
          ->doesntHave('roles')->get()->count();
      }

      private function ordersCount()
      {
          return Order::where('order_status_id', ORDER_STATUS_IN_PROGRESS)->get()->count();
      }

      private function paidBillsAmount()
      {
          $total = Bill::whereBetween('paid', [Carbon::now()->subDays(30)->toDateString(), Carbon::today()->toDateString()])->get()->sum('total');

          return format_money($total);
      }

      private function profitAmount()
      {
          $total = Order::where('order_status_id', ORDER_STATUS_COMPLETE)
          ->whereBetween(DB::raw('DATE(created_at)'), [Carbon::now()->subDays(30)->toDateString(), Carbon::today()->toDateString()])
          ->sum(DB::raw('total - IFNULL(staff_payment_amount, 0)'));

          return format_money($total);
      }



    }

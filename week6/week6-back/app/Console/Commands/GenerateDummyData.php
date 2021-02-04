<?php

namespace App\Console\Commands;

use App\Http\Controllers\UserActionController;
use App\Http\Requests\ExchangeUserActionRequest;
use App\Http\Requests\StoreUserActionRequest;
use App\Models\UserAction;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenerateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:data {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    function makeRandomDateInclusive($startDate, $endDate)
    {
        return date("Y-m-d", strtotime("$startDate + " . rand(0, round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24))) . " days"));
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    // Generate fake data for user
    public function handle()
    {
        $user = $this->argument('userId');
        Auth::loginUsingId($user);

        $userActionController = app()->make("App\Http\Controllers\UserActionController");

        // Getting date one and two year before
        $startDate = Carbon::now()->subWeeks(104)->toDateString();
        $firstYearDate = Carbon::now()->subWeeks(52)->toDateString();
        $endDate = Carbon::now()->toDateString();

        //Seeding last two year with random income

        //All moth for last two year
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1M'),
            new DateTime($endDate)
        );

        foreach ($period as $key => $value) {
            // First and last date of given month
            $first = date('Y-m-01', strtotime($value->format('Y-m-d')));
            $last = date('Y-m-t', strtotime($value->format('Y-m-d')));

            $rand = rand(0, 6);
            for ($i=0; $i < $rand ; $i++) {
                //Random date in month
                $date = $this->makeRandomDateInclusive($first, $last);
                $request = new StoreUserActionRequest([
                    "sum" => rand(10000, 50000),
                    "currency" => "UAH",
                    "date" => $date ,
                    "exchangeDate" => $date
                ]);
                app()->call([$userActionController, 'store'], ["request" => $request]);
            }

            $rand = rand(1, 2);
            for ($i = 0; $i < $rand; $i++) {
                //Random date in month
                $date = $this->makeRandomDateInclusive($first, $last);
                $request = new StoreUserActionRequest([
                    "sum" => rand(500, 2000),
                    "currency" => "USD",
                    "date" => $date,
                    "exchangeDate" => $date
                ]);
                app()->call([$userActionController, 'store'], ["request" => $request]);
            }
        }

        //All mont for last year
        $period = new DatePeriod(
            new DateTime($firstYearDate),
            new DateInterval('P1M'),
            new DateTime($endDate)
        );

        //Seeding last year with random exchange
        foreach ($period as $key => $value) {
            $first = date('Y-m-01', strtotime($value->format('Y-m-d')));
            $last = date('Y-m-t', strtotime($value->format('Y-m-d')));

            $date = $this->makeRandomDateInclusive($first, $last);
            $request = new ExchangeUserActionRequest([
                "sum" => rand(200, 500),
                "currency" => "USD",
                "date" => $date,
                "exchangeDate" => $date
            ]);
            app()->call([$userActionController, 'exchange'], ["request" => $request]);

        }
        return 0;
    }
}

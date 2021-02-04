<?php

namespace App\Console\Commands;

use App\Mail\TaxMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $key => $value) {

            if ($value->notification_period == "month") {
                Mail::to($value->email)->send(new TaxMail($value->firstname, $value->lastname));
            } else if ($value->notification_period == "quarter") {
                $month = [4, 7, 10, 1];
                if (in_array(date('m'), $month)) {
                    Mail::to($value->email)->send(new TaxMail($value->firstname, $value->lastname));
                }
            }
        }
        return 0;
    }
}

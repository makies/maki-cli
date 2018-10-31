<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Nexy\Slack\Client as SlackClient;

class GetShukiinToday extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'exec:shukiin-today';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'シュキーンから今日の出勤時刻を取得する';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->browse(function($browser) {
            
            $browser->visit(config('service.shukiin.url') . 'login')
                ->type('#email', config('service.shukiin.email'))
                ->type('#password', config('service.shukiin.password'))
                ->press('ログイン')
                ->assertSee('ホーム');

            $todanRecord = $browser->visit(config('service.shukiin.url') . 'timecard?employee_id=207')
                ->waitFor('.highlight-today')
                ->text('.highlight-today .js-record-column .time-entries ');

            $slack = new SlackClient(config('service.slack.webhook_url'));
            $slack->send('今日の出勤：' . $todanRecord);
        });
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->weekdays()->at('17:00');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeMyRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial Real Account Role';

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
        $user = User::where('email', config('app.main_email'))->first();
        if ($user) {
            $user->roles()->sync([config('constants.role_ids.admin')]);
            $this->info('Changed role');
        } else {
            $this->error('Not found');
        }
    }
}

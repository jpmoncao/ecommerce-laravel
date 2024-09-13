<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteTemporaryUsers extends Command
{
    protected $signature = 'users:delete-temporary';
    protected $description = 'Delete temporary users older than 7 days';

    public function handle()
    {
        $users = User::whereNull('email')
            ->whereNull('password')
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->get();

        foreach ($users as $user) {
            $user->delete();
        }

        $this->info('Temporary users cleaned up successfully.');
    }
}

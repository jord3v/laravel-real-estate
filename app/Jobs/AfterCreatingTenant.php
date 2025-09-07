<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AfterCreatingTenant implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = $this->tenant;

        $tenant->run(function ($tenant) {
            // o que precisar fazer no tenant
        });

        $tenant->update(['initial_migration_complete' => true]);
        $tenant->putDownForMaintenance([
            'message' => 'O sistema está em manutenção. Voltamos em breve!'
        ]);
    }
}

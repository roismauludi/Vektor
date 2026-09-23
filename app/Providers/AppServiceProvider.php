<?php

namespace App\Providers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('base.base', function ($view) {
            $approvalPendingCount = 0;
            $peminjamanDecisionCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->hasAnyRole(['Admin', 'PIC'])) {
                    $approvalPendingCount = Pengajuan::query()
                        ->whereRaw('LOWER(progress) = ?', ['menunggu verifikasi'])
                        ->count();
                }

                if ($user->hasAnyRole(['Admin', 'Security'])) {
                    $peminjamanDecisionCount = Pengajuan::query()
                        ->whereRaw('LOWER(progress) IN (?, ?)', ['approved', 'rejected'])
                        ->count();
                }
            }

            $view->with([
                'approvalPendingCount' => $approvalPendingCount,
                'peminjamanDecisionCount' => $peminjamanDecisionCount,
            ]);
        });
    }
}

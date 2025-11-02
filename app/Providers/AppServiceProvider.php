<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestLog; 
use App\Models\Quest;   
use Carbon\Carbon;

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
        Paginator::useBootstrapFive();
        View::composer('layouts.navigation', function ($view) {
            
            if (Auth::check()) {
                $user = Auth::user();
                $adminNotifications = collect();
                $userNewQuests = collect();
                $userReminders = collect();
                $notificationCount = 0;
                $newestTimestamp = null; 

                if ($user->isAdmin()) {
                    $pendingSubmissions = QuestLog::with('user', 'quest')
                                            ->where('status', 'pending_review')
                                            ->latest('updated_at') 
                                            ->get();
                    
                    $adminNotifications = $pendingSubmissions->take(10);
                    $notificationCount = $pendingSubmissions->count();
                    
                    if ($notificationCount > 0) {
                        $newestTimestamp = $pendingSubmissions->first()->updated_at->toIso8601String();
                    }
                    
                    $view->with('adminNotifications', $adminNotifications);

                } else {
                    $newQuests = Quest::where('is_admin_quest', true)
                                      ->where('is_active', true)
                                      ->where('created_at', '>=', Carbon::now()->subDays(7))
                                      ->latest('created_at') 
                                      ->get(); 

                    $reminders = QuestLog::with('quest')
                                        ->where('user_id', $user->id)
                                        ->where('status', 'active')
                                        ->whereHas('quest', function($q) {
                                            $q->whereIn('frequency', ['daily', 'weekly']);
                                        })
                                        ->latest('updated_at')
                                        ->get(); 

                    $notificationCount = $newQuests->count() + $reminders->count();

                    if ($notificationCount > 0) {
                         $newestQuestTime = $newQuests->isNotEmpty() ? $newQuests->first()->created_at : null;
                         $newestReminderTime = $reminders->isNotEmpty() ? $reminders->first()->updated_at : null;

                         if ($newestQuestTime && $newestReminderTime) {
                             $newestTimestamp = $newestQuestTime->gt($newestReminderTime) ? $newestQuestTime->toIso8601String() : $newestReminderTime->toIso8601String();
                         } elseif ($newestQuestTime) {
                             $newestTimestamp = $newestQuestTime->toIso8601String();
                         } elseif ($newestReminderTime) {
                             $newestTimestamp = $newestReminderTime->toIso8601String();
                         }
                    }
                    $view->with('userNewQuests', $newQuests->take(5));
                    $view->with('userReminders', $reminders->take(5));
                }
                $view->with('notificationCount', $notificationCount);
                $view->with('newestNotificationTimestamp', $newestTimestamp);
            }
        });
    }
}
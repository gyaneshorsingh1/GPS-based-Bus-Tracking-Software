<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Injects the current user's latest notifications and unread count into the
 * header partial so the bell dropdown and badge always render real data.
 */
class HeaderComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (! $user) {
            $view->with('headerNotifications', collect())
                ->with('headerUnreadCount', 0);

            return;
        }

        $view->with('headerNotifications', $user->notifications()->latest()->take(8)->get())
            ->with('headerUnreadCount', $user->unreadNotifications()->count());
    }
}

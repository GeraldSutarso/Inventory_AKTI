<?php
namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class NavbarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        $notifications = collect();

        if (!$user) return;

        if ($user->role === 'head') {
            $notifications = Order::where('kaunit_id', $user->id)
                ->where('is_approved', false)
                ->where('is_rejected', false)
                ->get();
        } elseif ($user->role === 'sarpras') {
            $notifications = Order::where('is_acknowledged', false)->get();
        }

        $view->with([
            'notifications' => $notifications,
            'unreadCount' => $notifications->count(),
        ]);
    }
}

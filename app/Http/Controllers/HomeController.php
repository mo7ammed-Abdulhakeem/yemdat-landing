<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('is_active', true)
            ->where('end_date', '>=', Carbon::now()) // Not ended yet
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $latestNews = Post::where('is_published', true)
            ->with('author')
            ->latest()
            ->take(2)
            ->get();

        return view('welcome', compact('upcomingEvents', 'latestNews'));
    }
}

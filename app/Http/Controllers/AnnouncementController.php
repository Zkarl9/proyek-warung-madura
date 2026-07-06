<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function dismiss(Announcement $announcement)
    {
        $announcement->dismissedBy()->syncWithoutDetaching([auth()->id()]);

        return response()->json(['success' => true]);
    }
}
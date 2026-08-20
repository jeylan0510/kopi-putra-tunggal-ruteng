<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Schedule;

class HomeController extends Controller
{
    public function index()
    {
        // Get up to 3 upcoming schedules with images
        $schedules = Schedule::with(['route', 'bus'])
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->take(3)
            ->get();
            
        return view('welcome', compact('schedules'));
    }

    public function schedules(Request $request)
    {
        $query = Schedule::with(['route', 'bus'])->whereDate('tanggal', '>=', now());

        if ($request->filled('asal')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('asal', 'like', '%' . $request->asal . '%');
            });
        }

        if ($request->filled('tujuan')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('tujuan', 'like', '%' . $request->tujuan . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $schedules = $query->orderBy('tanggal', 'asc')->orderBy('jam_berangkat', 'asc')->get();

        $availableTimes = $schedules->pluck('jam_berangkat')->unique()->sort()->values();
        $availableClasses = $schedules->map(function ($schedule) {
            return [
                'kelas' => $schedule->bus->kelas,
                'harga' => $schedule->harga
            ];
        })->unique(function ($item) {
            return $item['kelas'] . '-' . $item['harga'];
        })->sortBy('kelas')->values();

        return view('schedules.index', compact('schedules', 'availableTimes', 'availableClasses'));
    }
}

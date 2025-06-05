<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areaParkirs = AreaParkir::with('kampus')->get()->map(function ($area) {
            $area->available_slots = $area->kapasitas - Transaction::where('area_parkir_id', $area->id)->whereNull('end')->count();
            return $area;
        });
        $vehicleTypes = VehicleType::all();
        $selectedArea = null;
        return view('welcome', compact('areaParkirs', 'vehicleTypes', 'selectedArea'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'nopol' => 'required|string',
            'merk' => 'required|string',
            'thn_beli' => 'required|integer',
            'deskripsi' => 'required|string',
            'terms' => 'accepted',
        ]);

        $user = Auth::user() ?? User::create([
            'name' => $request->name,
            'email' => $request->email . '@nurulfikri.ac.id',
            'password' => bcrypt($request->password), 
            'role' => 2, 
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['nopol' => $request->nopol],
            [
                'pemilik' => $user->id,
                'jenis_kendaraan' => $request->vehicle_type_id,
                'merk' => $request->merk,
                'thn_beli' => $request->thn_beli,
                'deskripsi' => $request->deskripsi,
            ]
        );

        Transaction::create([
            'kendaraan_id' => $vehicle->id,
            'area_parkir_id' => $request->selected_area,
            'tanggal' => now()->toDateString(),
            'start' => now()->toTimeString(),
            'keterangan' => 'Slot ' . rand(1, 50), // Dummy slot
            'biaya' => 0, 
        ]);

        return redirect()->route('dashboard')->with('success', 'Registrasi parkir berhasil!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $activeParking = $user->vehicles()->first()?->transactions()->whereNull('end')->first();
        $areaParkirs = AreaParkir::with('campus')->get()->map(function ($area) {
            $area->available_slots = $area->kapasitas - Transaction::where('area_parkir_id', $area->id)->whereNull('end')->count();
            return $area;
        });
        $pastParkings = $user->vehicles()->first()?->transactions()->whereNotNull('end')->latest()->take(3)->get();

        return view('dashboard', compact('activeParking', 'areaParkirs', 'pastParkings'));
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

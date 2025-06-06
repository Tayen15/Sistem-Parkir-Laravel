<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areaParkirs = AreaParkir::with('campus')->get()->map(function ($area) {
            $area->available_slots = $area->kapasitas - Transaction::where('area_parkir_id', $area->id)->whereNull('end')->count();
            return $area;
        });
        $vehicleTypes = VehicleType::all();

        $view = Auth::check() ? 'dashboard.index' : 'index';
        $selectedArea = null;
        return view($view, compact('areaParkirs', 'vehicleTypes', 'selectedArea'))->with('layout', Auth::check() ? 'layouts.guest' : 'layouts.app');
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
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $activeParking = Transaction::whereNull('end')
                ->whereHas('vehicle', function ($query) use ($user) {
                    $query->where('pemilik', $user->id);
                })
                ->first();

            if ($activeParking) {
                return redirect()->back()
                    ->withErrors(['error' => 'Anda masih memiliki parkir aktif yang belum diselesaikan.'])
                    ->withInput();
            }
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'nopol' => 'required|string|max:20',
            'merk' => 'required|string|max:255',
            'thn_beli' => 'required|integer|digits:4|min:1990|max:' . date('Y'),
            'deskripsi' => 'nullable|string|max:1000',
            'selected_area' => 'required|exists:area_parkirs,id',
            'terms' => 'accepted',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $defaultPassword = null;

        $user = User::where('email', $request->email)->first();
        $userWasCreated = false;

        if (!$user) {
            $randomNumber = rand(1000, 9999);
            $defaultPassword = str_replace(' ', '', $request->name) . $randomNumber;

            $user = User::create([

                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($defaultPassword),
                'role' => 2,
                'has_changed_password' => false,
            ]);
            $userWasCreated = true;
        }

        $vehicle = Vehicle::firstOrCreate(
            ['nopol' => strtoupper($request->nopol)],
            [
                'pemilik' => $user->id,
                'jenis_kendaraan' => $request->vehicle_type_id,
                'merk' => $request->merk,
                'warna' => $request->warna,
                'thn_beli' => $request->thn_beli,
                'deskripsi' => $request->deskripsi ?? 'No description',
            ]
        );

        $transaction = Transaction::create([
            'kendaraan_id' => $vehicle->id,
            'area_parkir_id' => $request->selected_area,
            'tanggal' => now()->toDateString(),
            'start' => now()->toTimeString(),
            'keterangan' => 'Slot ' . AreaParkir::find($request->selected_area)->kapasitas,
            'biaya' => 0,
            'status_pembayaran' => 'pending',
        ]);

        if (!Auth::check()) {
            Auth::login($user);
            $request->session()->regenerate();
        }

        if ($userWasCreated) {
            $request->session()->flash('registration_success', [
                'email' => $request->email,
                'password' => $defaultPassword,
            ]);
            $successMessage = 'Registrasi parkir dan akun Anda berhasil dibuat!';
        } else {
            $successMessage = 'Parkir kendaraan Anda berhasil didaftarkan. Selamat datang kembali!';
        }

        return redirect()->route('dashboard')->with('success', $successMessage);
    }

    public function dashboard()
    {
        $user = Auth::user();

        $activeParking = Transaction::whereNull('end') // Hanya transaksi yang belum selesai
            ->whereHas('vehicle', function ($query) use ($user) {
                // Pastikan transaksi terkait dengan kendaraan yang dimiliki user ini
                $query->where('pemilik', $user->id);
            })
            ->with('vehicle.vehicleType', 'areaParkir') 
            ->first(); 

        $pastParkings = Transaction::whereNotNull('end') 
            ->whereHas('vehicle', function ($query) use ($user) {
                $query->where('pemilik', $user->id); 
            })
            ->with('vehicle.vehicleType', 'areaParkir') 
            ->latest()
            ->take(3) 
            ->get();

        $areaParkirs = AreaParkir::with('campus')->get()->map(function ($area) {
            $area->available_slots = $area->kapasitas - Transaction::where('area_parkir_id', $area->id)->whereNull('end')->count();
            return $area;
        });

        $needsPasswordChange = !$user->has_changed_password;

        return view('dashboard.index', compact('activeParking', 'areaParkirs', 'pastParkings', 'needsPasswordChange'))->with('layout', 'layouts.guest');
    }

    public function showPayment($id)
    {
        $transaction = Transaction::with('vehicle.vehicleType')->findOrFail($id);
        if ($transaction->vehicle->pemilik !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($transaction->end !== null && $transaction->status_pembayaran === 'completed') {
            return redirect()->route('dashboard')->with('error', 'Parkir ini sudah selesai dan dibayar.');
        }

        $fee = $transaction->calculateFee($transaction->start, now()->toTimeString(), $transaction->vehicle->vehicleType);

        if ($transaction->status_pembayaran === 'pending') {
            $transaction->update(['status_pembayaran' => 'awaiting_payment']);
        }

        return redirect()->route('dashboard')->with('payment_details', [
            'parking_id' => $transaction->id,
            'fee' => $fee,
            'start_time' => $transaction->start,
            'transaction_date' => $transaction->tanggal,
        ]);
    }

    public function showPaymentConfirmation($transaction_id)
    {
        $transaction = Transaction::with('vehicle.vehicleType')->find($transaction_id);

        $status = session('status', 'failed');
        $flashedTransaction = session('transaction', $transaction);

        if (!$flashedTransaction) {
            $flashedTransaction = $transaction;
        }

        if (!$flashedTransaction) {
            return redirect()->route('index')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('payment.confirmation', [
            'status' => $status,
            'transaction' => $flashedTransaction,
        ])->with('layout', 'layouts.app');
    }

    public function completeCashPayment(Request $request, $id)
    {
        $transaction = Transaction::with('vehicle.vehicleType')->findOrFail($id);

        if ($transaction->vehicle->pemilik !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
        if ($transaction->end !== null && $transaction->status_pembayaran === 'completed') {
            return response()->json(['success' => false, 'message' => 'Parkir ini sudah selesai dan dibayar.'], 400);
        }

        $finalFee = $transaction->calculateFee($transaction->start, now()->toTimeString(), $transaction->vehicle->vehicleType);

        $transaction->update([
            'end' => now()->toTimeString(),
            'biaya' => $finalFee,
            'status_pembayaran' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran tunai berhasil dan parkir selesai.',
            'fee_formatted' => number_format($finalFee, 0, ',', '.'),
            'parking_id' => $transaction->id
        ]);
    }


    public function generateQrPayment(Request $request, $id)
    {
        $transaction = Transaction::with('vehicle.vehicleType')->findOrFail($id);

        if ($transaction->vehicle->pemilik !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }
        if ($transaction->end !== null && $transaction->status_pembayaran === 'completed') {
            return response()->json(['success' => false, 'message' => 'Parkir ini sudah selesai dan dibayar.'], 400);
        }

        $fee = $request->input('fee');
        if (!$fee) {
            $fee = $transaction->calculateFee($transaction->start, now()->toTimeString(), $transaction->vehicle->vehicleType);
        }

        $qrCallbackUrl = route('payment.qr_success', ['transaction_id' => $transaction->id]);

        $qrData = $qrCallbackUrl;

        $transaction->update(['status_pembayaran' => 'pending_qr']);

        $qrCodeImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dibuat.',
            'qr_code_url' => $qrCodeImageUrl,
            'parking_id' => $transaction->id,
            'fee' => $fee, // Kirim fee ke frontend
            'redirect_url_on_success' => $qrCallbackUrl
        ]);
    }

    public function checkPaymentStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction->end !== null && $transaction->biaya > 0) { // Asumsi jika end terisi dan biaya > 0, berarti sudah dibayar
            return response()->json(['status' => 'paid', 'message' => 'Pembayaran berhasil!']);
        }
        return response()->json(['status' => 'pending', 'message' => 'Menunggu pembayaran...']);
    }

    public function handleQrSuccess($transaction_id)
    {
        $transaction = Transaction::with('vehicle.vehicleType')->findOrFail($transaction_id);

        if ($transaction->end === null && ($transaction->status_pembayaran === 'pending_qr' || $transaction->status_pembayaran === 'awaiting_payment')) {
            $finalFee = $transaction->calculateFee($transaction->start, now()->toTimeString(), $transaction->vehicle->vehicleType);

            $transaction->update([
                'end' => now()->toTimeString(),
                'biaya' => $finalFee,
                'status_pembayaran' => 'completed',
            ]);

            return redirect()->route('payment.confirmation', $transaction->id)->with([
                'status' => 'success',
                'transaction' => $transaction,
            ]);
        }

        return redirect()->route('payment.confirmation', $transaction->id)->with([
            'status' => 'info',
            'transaction' => $transaction,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi Anda salah.',
            ]);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
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

    public function showLoginForm()
    {
        return view('auth.login')->with('layout', 'layouts.app');
    }
}

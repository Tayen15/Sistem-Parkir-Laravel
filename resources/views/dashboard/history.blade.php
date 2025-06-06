@extends($layout ?? 'layouts.guest')

@section('title', 'Riwayat Parkir')

@section('content')
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <i class="fas fa-history text-blue-600"></i>
                <span>Riwayat Parkir</span>
            </h2>
        </div>
        <div class="p-6">
            @if ($pastParkings->isEmpty())
                <div class="text-center text-gray-600">
                    <p>Belum ada riwayat parkir.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($pastParkings as $parking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <i class="fas fa-car text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $parking->vehicle->nopol }}</p>
                                    <p class="text-sm text-gray-600">{{ $parking->areaParkir->name }} - Slot
                                        {{ $parking->keterangan }}</p>
                                    <p class="text-xs text-gray-500">{{ $parking->tanggal }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Durasi:
                                    {{ $this->calculateDuration($parking->start, $parking->end) }}</p>
                                <p class="font-bold text-green-600">Rp {{ number_format($parking->biaya, 0, ',', '.') }}</p>
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Selesai</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $pastParkings->links() }}
                </div>
            @endif
            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">Kembali
                    ke Dashboard</a>
            </div>
        </div>
    </div>
@endsection

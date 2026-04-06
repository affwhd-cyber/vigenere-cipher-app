@extends('layouts.app')

@section('title', 'Kasiski Examination')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark">🔍 Kasiski Examination</h1>
                <p class="lead text-secondary">Menentukan kemungkinan panjang kunci Vigenère dengan mencari pola berulang</p>
            </div>

            <div class="card card-custom shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-search"></i> Masukkan Ciphertext</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('kasiski.analyze') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="ciphertext" class="form-label fw-semibold">📝 Ciphertext</label>
                            <textarea class="form-control form-control-lg rounded-3" id="ciphertext" name="ciphertext" rows="5" placeholder="Masukkan ciphertext di sini..." required>{{ old('ciphertext', session('inputText', '')) }}</textarea>
                            <div class="form-text">Masukkan ciphertext yang ingin dianalisis (minimal 10 huruf). Spasi dan tanda baca akan diabaikan.</div>
                        </div>
                        <button type="submit" class="btn btn-custom btn-custom-primary btn-lg w-100 rounded-pill">
                            <i class="bi bi-graph-up"></i> Analisis
                        </button>
                    </form>
                </div>
            </div>

            @if(session('result'))
            <div class="mt-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="mb-0 fw-semibold">📊 Hasil Analisis Kasiski</h5>
                    </div>
                    <div class="card-body">
                        @if(isset(session('result')['details']['error']))
                            <div class="alert alert-warning">{{ session('result')['details']['error'] }}</div>
                        @elseif(isset(session('result')['details']['message']))
                            <div class="alert alert-info">{{ session('result')['details']['message'] }}</div>
                        @else
                            <div class="mb-3">
                                <strong>Panjang Ciphertext:</strong> {{ session('result')['details']['ciphertext_length'] }} huruf
                            </div>
                            
                            <div class="mb-3">
                                <strong>📌 Kemungkinan Panjang Kunci (faktor terbanyak):</strong>
                                @if(count(session('result')['key_lengths']) > 0)
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach(session('result')['key_lengths'] as $len)
                                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $len }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ditemukan kandidat panjang kunci</span>
                                @endif
                            </div>
                            
                            <hr>
                            
                            <h6><i class="bi bi-repeat"></i> Pola Berulang yang Ditemukan:</h6>
                            @if(count(session('result')['details']['repeated_patterns']) > 0)
                                <table class="table table-sm table-bordered mt-2">
                                    <thead class="table-light">
                                        <tr><th>Pola</th><th>Posisi</th><th>Jarak</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('result')['details']['raw_details'] as $detail)
                                        <tr>
                                            <td><code>{{ $detail['pattern'] }}</code></td>
                                            <td>{{ implode(', ', $detail['positions']) }}</td>
                                            <td>{{ $detail['distance'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Tidak ada pola berulang (trigram) yang ditemukan.</p>
                            @endif
                            
                            <hr>
                            
                            <h6><i class="bi bi-calculator"></i> Frekuensi Faktor Jarak:</h6>
                            <table class="table table-sm table-striped mt-2">
                                <thead class="table-light">
                                    <tr><th>Faktor (panjang kunci kandidat)</th><th>Frekuensi</th></tr>
                                </thead>
                                <tbody>
                                    @foreach(session('result')['details']['factor_counts'] as $factor => $count)
                                    <tr>
                                        <td><strong>{{ $factor }}</strong></td>
                                        <td>{{ $count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="alert alert-secondary mt-3">
                                <strong>💡 Interpretasi:</strong> Panjang kunci yang paling mungkin adalah faktor dengan frekuensi tertinggi. Dalam contoh di atas, coba gunakan panjang kunci tersebut untuk melakukan <strong>Frequency Analysis</strong> pada setiap kelompok.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
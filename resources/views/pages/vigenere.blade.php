@extends('layouts.app')

@section('title', 'Kalkulator Vigenère Cipher')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Halaman -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark">🔐 KALKULATOR Vigenère Cipher</h1>
                <p class="lead text-secondary">Enkripsi & Dekripsi pesan dengan kunci rahasia</p>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Polyalphabetic</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Tabula Recta</span>
                </div>
            </div>

            <!-- Card Kalkulator -->
            <div class="card card-custom shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-key-fill"></i> Masukkan Data</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('vigenere.process') }}" id="vigenereForm">
                        @csrf
                        
                        <!-- Teks -->
                        <div class="mb-4">
                            <label for="text" class="form-label fw-semibold">📝 Teks (Plaintext / Ciphertext)</label>
                            <textarea class="form-control form-control-lg rounded-3 border-2" id="text" name="text" rows="3" placeholder="Masukkan teks..." required style="resize: none;">{{ old('text', session('inputText', '')) }}</textarea>
                            <div class="form-text">Masukkan pesan yang ingin dienkripsi atau didekripsi (hanya huruf A-Z).</div>
                        </div>

                        <!-- Kunci -->
                        <div class="mb-4">
                            <label for="key" class="form-label fw-semibold">🔑 Kunci (Key)</label>
                            <input type="text" class="form-control form-control-lg rounded-3 border-2" id="key" name="key" value="{{ old('key', session('inputKey', '')) }}" required pattern="[A-Za-z]+" title="Hanya huruf A-Z" placeholder="Masukkan kunci...">
                            <div class="form-text">Kunci akan diulang secara otomatis hingga sepanjang teks.</div>
                        </div>

                        <!-- Mode radio -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">⚙️ Mode</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode" id="modeEncrypt" value="encrypt" {{ (old('mode', session('inputMode', 'encrypt')) == 'encrypt') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="modeEncrypt">
                                        🔒 Enkripsi (Plaintext → Ciphertext)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="mode" id="modeDecrypt" value="decrypt" {{ (old('mode', session('inputMode', 'encrypt')) == 'decrypt') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="modeDecrypt">
                                        🔓 Dekripsi (Ciphertext → Plaintext)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Proses -->
                        <button type="submit" class="btn btn-custom btn-custom-primary btn-lg w-100 rounded-pill shadow-sm">
                            <i class="bi bi-lock-fill"></i> Hasilkan Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Hasil -->
            @if(session('result'))
            <div class="mt-5 animate__animated animate__fadeInUp">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="mb-0 fw-semibold">📌 Hasil {{ session('inputMode') == 'encrypt' ? 'Ciphertext' : 'Plaintext' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="result-text">{{ session('result') }}</span>
                        </div>
                        <hr>
                        <h6 class="fw-semibold"><i class="bi bi-list-ul"></i> Proses Perhitungan:</h6>
                        <pre class="p-3 rounded-3 border bg-light" style="white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.9rem;">{{ session('steps') }}</pre>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info tambahan -->
            <div class="mt-5 text-center text-muted small">
                <p class="mb-0">💡 Vigenère Cipher menggunakan tabel Tabula Recta. Setiap huruf digeser berdasarkan huruf kunci.</p>
                <p class="mb-0">Rumus: <code>C = (P + K) mod 26</code> (enkripsi) & <code>P = (C - K) mod 26</code> (dekripsi).</p>
            </div>
        </div>
    </div>
</div>
@endsection
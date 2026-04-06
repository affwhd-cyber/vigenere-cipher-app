@extends('layouts.app')

@section('title', 'Frequency Analysis')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark">📊 Frequency Analysis</h1>
                <p class="lead text-secondary">Menganalisis ciphertext berdasarkan frekuensi huruf untuk menemukan kunci Vigenère</p>
            </div>

            <div class="card card-custom shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-bar-chart-steps"></i> Parameter Analisis</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('frequency.analyze') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="ciphertext" class="form-label fw-semibold">🔐 Ciphertext</label>
                            <textarea class="form-control form-control-lg rounded-3" id="ciphertext" name="ciphertext" rows="4" placeholder="Masukkan ciphertext..." required>{{ old('ciphertext', session('inputText', '')) }}</textarea>
                            <div class="form-text">Ciphertext yang akan dianalisis (hanya huruf A-Z, spasi dan tanda baca diabaikan).</div>
                        </div>
                        <div class="mb-4">
                            <label for="key_length" class="form-label fw-semibold">🔑 Panjang Kunci (dari Kasiski atau tebakan)</label>
                            <input type="number" class="form-control form-control-lg rounded-3" id="key_length" name="key_length" value="{{ old('key_length', session('inputKeyLength', 3)) }}" min="1" max="20" required>
                            <div class="form-text">Masukkan panjang kunci yang diduga (biasanya dari hasil Kasiski).</div>
                        </div>
                        <button type="submit" class="btn btn-custom btn-custom-primary btn-lg w-100 rounded-pill">
                            <i class="bi bi-graph-up"></i> Analisis Frekuensi
                        </button>
                    </form>
                </div>
            </div>

            @if(session('result'))
                @php $res = session('result'); @endphp
                @if(isset($res['error']))
                    <div class="alert alert-danger mt-4">{{ $res['error'] }}</div>
                @else
                    <div class="mt-5">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 pt-4">
                                <h5 class="mb-0 fw-semibold">📌 Hasil Analisis</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <strong>Kunci yang ditemukan:</strong>
                                        <div class="display-6 fw-bold text-primary">{{ $res['key'] }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Panjang kunci:</strong>
                                        <div class="fs-3">{{ $res['key_length'] }}</div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <strong>Plaintext hasil dekripsi:</strong>
                                    <div class="bg-light p-3 rounded-3 mt-2" style="font-family: monospace; font-size: 1.1rem;">{{ $res['plaintext'] }}</div>
                                </div>
                                <hr>
                                <h6><i class="bi bi-table"></i> Detail per Kelompok (berdasarkan panjang kunci)</h6>
                                <div class="accordion" id="groupAccordion">
                                    @foreach($res['details'] as $groupName => $groupData)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $groupName)) }}</strong> → Pergeseran terbaik: {{ $groupData['best_shift'] }} (huruf '{{ $groupData['best_shift_letter'] }}')
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#groupAccordion">
                                                <div class="accordion-body">
                                                    <div><strong>Teks kelompok:</strong> <code>{{ $groupData['text'] }}</code></div>
                                                    <div class="mt-2"><strong>Hasil dekripsi kelompok (dengan pergeseran):</strong> <code>{{ $groupData['decrypted'] }}</code></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>
                                <h6><i class="bi bi-list-ul"></i> Proses Dekripsi Lengkap (Vigenère)</h6>
                                <pre class="bg-light p-3 rounded-3" style="white-space: pre-wrap; font-size: 0.9rem;">{{ $res['steps'] }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
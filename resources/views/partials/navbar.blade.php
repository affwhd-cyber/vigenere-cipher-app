<nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('vigenere.index') }}">
            🔐 Vigenère Cipher
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('vigenere.index') }}">
                        <i class="bi bi-calculator-fill"></i> Kalkulator
                    </a>
                </li>
                {{-- Nanti bisa tambah menu: History, Analisis, dll --}}
            </ul>
        </div>
    </div>
</nav>
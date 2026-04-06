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
                    <a class="nav-link" href="{{ route('vigenere.index') }}">
                        <i class="bi bi-calculator-fill"></i> Kalkulator
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('kasiski.index') }}">
                        <i class="bi bi-search-heart"></i> Kasiski
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('frequency.index') }}">
                        <i class="bi bi-bar-chart-steps"></i> Frekuensi
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
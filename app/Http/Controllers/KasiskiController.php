<?php

namespace App\Http\Controllers;

use App\Services\Vigenere\KasiskiService;
use Illuminate\Http\Request;

class KasiskiController extends Controller
{
    protected $kasiskiService;

    public function __construct(KasiskiService $kasiskiService)
    {
        $this->kasiskiService = $kasiskiService;
    }

    public function index()
    {
        return view('pages.kasiski');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'ciphertext' => 'required|string|min:10'
        ]);

        $ciphertext = $request->input('ciphertext');
        $result = $this->kasiskiService->analyze($ciphertext);

        return redirect()->back()->with([
            'result' => $result,
            'inputText' => $ciphertext
        ]);
    }
}
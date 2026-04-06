<?php

namespace App\Http\Controllers;

use App\Services\Vigenere\FrequencyAnalysisService;
use Illuminate\Http\Request;

class FrequencyAnalysisController extends Controller
{
    protected $frequencyService;

    public function __construct(FrequencyAnalysisService $frequencyService)
    {
        $this->frequencyService = $frequencyService;
    }

    public function index()
    {
        return view('pages.frequency');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'ciphertext' => 'required|string',
            'key_length' => 'required|integer|min:1'
        ]);

        $ciphertext = $request->input('ciphertext');
        $keyLength = (int)$request->input('key_length');

        $result = $this->frequencyService->analyze($ciphertext, $keyLength);

        return redirect()->back()->with([
            'result' => $result,
            'inputText' => $ciphertext,
            'inputKeyLength' => $keyLength
        ]);
    }
}
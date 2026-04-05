<?php

namespace App\Http\Controllers;

use App\Services\Vigenere\CoreService;
use Illuminate\Http\Request;

class VigenereController extends Controller
{
    protected $coreService;

    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    public function index()
    {
        return view('pages.vigenere');
    }

    public function process(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'key' => 'required|string|regex:/^[A-Za-z]+$/',
            'mode' => 'required|in:encrypt,decrypt'
        ]);

        $text = $request->input('text');
        $key = $request->input('key');
        $mode = $request->input('mode');

        if ($mode === 'encrypt') {
            $result = $this->coreService->encrypt($text, $key);
        } else {
            $result = $this->coreService->decrypt($text, $key);
        }

        return redirect()->back()->with([
            'result' => $result['result'],
            'steps' => $result['steps'],
            'inputText' => $text,
            'inputKey' => $key,
            'inputMode' => $mode
        ]);
    }
}
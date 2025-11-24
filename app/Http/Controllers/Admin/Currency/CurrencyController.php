<?php

namespace App\Http\Controllers\Admin\Currency;

use App\Http\Controllers\Controller;
use App\Models\Currency\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::with('latestRate')->paginate(20);
        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin.currencies.create');
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'code' => 'required|unique:currencies,code|max:3',
            'name' => 'required',
            'symbol' => 'nullable',
        ]);

        Currency::create($request->all());
        return redirect()->route('currencies.index')->with('success', 'Currency added successfully.');
    }

    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => 'required|max:3|unique:currencies,code,' . $currency->id,
            'name' => 'required',
        ]);

        $currency->update($request->all());
        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        if($currency->exchangeRates()->exists()){
            $currency->exchangeRates()->delete();
        }
        $currency->delete();
        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }

    // Exchange Rate Management
    public function rates(Currency $currency)
    {
        $rates = $currency->exchangeRates()->orderBy('date', 'desc')->paginate(20);
        return view('admin.exchangeRates.index', compact('currency', 'rates'));
    }

    public function addRate(Request $request, Currency $currency)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $currency->exchangeRates()->create($request->all());
        return back()->with('success', 'Exchange rate added.');
    }
}

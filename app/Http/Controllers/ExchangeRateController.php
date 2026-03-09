<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Requests\ExchangeRateRequest;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q',''));
        $rows = ExchangeRate::query()
            ->with(['base','quote'])
            ->when($q !== '', function($qq) use ($q){
                $qq->where('base_currency_code','like',"%$q%")
                   ->orWhere('quote_currency_code','like',"%$q%");
            })
            ->orderBy('base_currency_code')->orderBy('quote_currency_code')
            ->orderByDesc('valid_from')
            ->paginate(15)->withQueryString();

        return view('back.exchange_rates.index', ['rates'=>$rows,'q'=>$q]);
    }

    public function create()
    {
        $currencies = Currency::orderBy('code')->get();
        return view('back.exchange_rates.create', ['rate'=>new ExchangeRate, 'currencies'=>$currencies]);
    }

    public function store(ExchangeRateRequest $req)
    {
        $data = $req->validated();
        $this->assertNoOverlap($data);

        ExchangeRate::create($data);
        return redirect()->route('admin.exchange-rates.index')->with('success','Rate created.');
    }

    public function edit(ExchangeRate $exchangeRate)
    {
        $currencies = Currency::orderBy('code')->get();
        return view('back.exchange_rates.edit', ['rate'=>$exchangeRate,'currencies'=>$currencies]);
    }

    public function update(ExchangeRateRequest $req, ExchangeRate $exchangeRate)
    {
        $data = $req->validated();
        $this->assertNoOverlap($data, $exchangeRate->id);

        $exchangeRate->update($data);
        return redirect()->route('admin.exchange-rates.index')->with('success','Rate updated.');
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();
        return back()->with('success','Rate deleted.');
    }

    /** Overlap guard: same pair cannot have overlapping date windows */
    protected function assertNoOverlap(array $data, ?int $ignoreId = null): void
    {
        $query = ExchangeRate::query()
            ->where('base_currency_code',  $data['base_currency_code'])
            ->where('quote_currency_code', $data['quote_currency_code']);

        if ($ignoreId) $query->where('id','!=',$ignoreId);

        $from = $data['valid_from'];
        $to   = $data['valid_to']; // null = open-ended

        $overlap = $query->where(function($q) use ($from,$to) {
            // existing:[valid_from .. valid_to] overlaps new:[from .. to]
            $q->where(function($qq) use ($from,$to){
                $qq->where(function($x) use ($from){
                        $x->where('valid_to','>=',$from)->orWhereNull('valid_to');
                    })
                   ->where('valid_from','<=', $to ?? $from); // for open end, compare to from
            });
        })->exists();

        if ($overlap) {
            abort(back()->withInput()->with('error','Date range overlaps with an existing rate for this pair.'));
        }
    }
}

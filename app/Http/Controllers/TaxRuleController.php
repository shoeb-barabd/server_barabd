<?php

namespace App\Http\Controllers;

use App\Models\TaxRule;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\TaxRuleRequest;

class TaxRuleController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q',''));
        $rows = TaxRule::query()->with('country')
            ->when($q !== '', function($qq) use ($q){
                $qq->where('tax_name','like',"%$q%")
                   ->orWhereHas('country', fn($c)=>$c->where('name','like',"%$q%")->orWhere('iso2','like',"%$q%"));
            })
            ->orderBy('country_id')->orderBy('tax_name')->orderByDesc('effective_from')
            ->paginate(15)->withQueryString();

        return view('back.tax_rules.index', ['rules'=>$rows,'q'=>$q]);
    }

    public function create()
    {
        return view('back.tax_rules.create', [
            'rule' => new TaxRule,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function store(TaxRuleRequest $req)
    {
        $data = $req->validated();
        $this->assertNoOverlap($data);
        TaxRule::create($data);
        return redirect()->route('admin.tax-rules.index')->with('success','Tax rule created.');
    }

    public function edit(TaxRule $taxRule)
    {
        return view('back.tax_rules.edit', [
            'rule' => $taxRule,
            'countries' => Country::orderBy('name')->get(),
        ]);
    }

    public function update(TaxRuleRequest $req, TaxRule $taxRule)
    {
        $data = $req->validated();
        $this->assertNoOverlap($data, $taxRule->id);
        $taxRule->update($data);
        return redirect()->route('admin.tax-rules.index')->with('success','Tax rule updated.');
    }

    public function destroy(TaxRule $taxRule)
    {
        $taxRule->delete();
        return back()->with('success','Tax rule deleted.');
    }

    /** country+tax_name windows must not overlap */
    protected function assertNoOverlap(array $d, ?int $ignoreId=null): void
    {
        $q = TaxRule::query()
            ->where('country_id',$d['country_id'])
            ->where('tax_name',$d['tax_name']);
        if ($ignoreId) $q->where('id','!=',$ignoreId);

        $from = $d['effective_from'];
        $to   = $d['effective_to'];

        $overlap = $q->where(function($x) use ($from,$to){
            $x->where(function($w) use ($from){
                $w->where('effective_to','>=',$from)->orWhereNull('effective_to');
            })->where('effective_from','<=', $to ?? $from);
        })->exists();

        if ($overlap) {
            abort(back()->withInput()->with('error','Effective date overlaps an existing rule for this country & tax name.'));
        }
    }
}

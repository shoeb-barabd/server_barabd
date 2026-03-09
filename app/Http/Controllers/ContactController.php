<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q',''));

        $rows = Contact::query()
            ->with('account')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereRaw(
                    "CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?",
                    ["%{$q}%"]
                )
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhereHas('account', fn($a) => $a->where('name','like',"%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('back.contacts.index', [
            'contacts' => $rows,
            'q'        => $q,
        ]);
    }

    public function create()
    {
        return view('back.contacts.create', [
            'contact'  => new Contact,
            'accounts' => Account::orderBy('name')->get(),
        ]);
    }

    public function store(ContactRequest $req)
    {
        $contact = Contact::create($req->validated());

        // ensure only one primary contact per account
        if ($contact->is_primary) {
            Contact::where('account_id', $contact->account_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_primary' => false]);
        }

        return redirect()->route('admin.contacts.index')->with('success', 'Contact created.');
    }

    public function edit(Contact $contact)
    {
        return view('back.contacts.edit', [
            'contact'  => $contact,
            'accounts' => \App\Models\Account::orderBy('name')->get(),
        ]);
    }

    public function update(ContactRequest $req, Contact $contact)
    {
        $contact->update($req->validated());

        if ($contact->is_primary) {
            Contact::where('account_id', $contact->account_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_primary' => false]);
        }

        return redirect()->route('admin.contacts.index')->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return back()->with('success', 'Contact deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete contact.');
        }
    }
}

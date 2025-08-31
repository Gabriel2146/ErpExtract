<?php

namespace App\Http\Controllers;

use App\Models\LdapDomain;
use Illuminate\Http\Request;

class LdapDomainController extends Controller
{
    public function index()
    {
        $domains = LdapDomain::orderBy('name')->get();
        return view('content.config.ldap_domains', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'domain'   => 'required|string|max:100',
            'host'     => 'required|string|max:100',
            'base_dn'  => 'required|string|max:2000',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:100',
            'country'  => 'required|string|max:4',
            'icon'     => 'nullable|string|max:255',
        ]);

        LdapDomain::create($request->only([
            'name',
            'domain',
            'host',
            'base_dn',
            'username',
            'password',
            'country',
            'icon'
        ]));

        return redirect()->back()->with('success', 'Dominio LDAP creado correctamente');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'domain'   => 'required|string|max:100',
            'host'     => 'required|string|max:100',
            'base_dn'  => 'required|string|max:255',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:100',
            'country'  => 'required|string|max:4',
            'icon'     => 'nullable|string|max:255',
        ]);

        $domain = LdapDomain::findOrFail($id);
        $domain->update($request->only([
            'name',
            'domain',
            'host',
            'base_dn',
            'username',
            'password',
            'country',
            'icon'
        ]));

        return redirect()->back()->with('success', 'Dominio LDAP actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $domain = LdapDomain::findOrFail($id);
        $domain->delete();

        return redirect()->route('ldap_domains.index')->with('success', 'Dominio LDAP eliminado correctamente');
    }
}

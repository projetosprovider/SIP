<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use App\Models\Client;
use App\Models\Client\{Employee, Occupation};
use Auth;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::where('id', '>', 0);

        if($request->filled('search')) {

          $search = $request->get('search');

          $employees->where('id', $search)
          ->orWhere('name', 'like', "%$search%")
          ->orWhere('cpf', 'like', "%$search%");

        }

        if($request->filled('status')) {
          $employees->where('active', $request->get('status'));
        }

        $quantity = $employees->count();

        $employees = $employees->paginate(15);

        foreach ($request->all() as $key => $value) {
            $employees->appends($key, $value);
        }

        return view('clients.employees.index', compact('employees', 'quantity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!Auth::user()->hasPermission('create.cliente.funcionarios')) {
            return abort(403, 'Unauthorized action.');
        }

        $company = null;

        if($request->has('client')) {
            $company = Client::uuid($request->get('client'));
        }

        $companies = Client::all();

        return view('clients.employees.create', compact('company', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->hasPermission('create.cliente.funcionarios')) {
            return abort(403, 'Unauthorized action.');
        }

        $data = $request->request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'phone' => 'string|max:20',
            'company_id' => 'required',
            'occupation_id' => 'required',
            //'cpf' => 'required|cpf|unique:employees',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = Client::uuid($data['company_id']);
        $data['company_id'] = $company->id;

        $occupation = Occupation::uuid($data['occupation_id']);
        $data['occupation_id'] = $occupation->id;

        $data['created_by'] = $request->user()->id;
        $data['active'] = $request->has('active');

        Employee::create($data);

        notify()->flash('Sucesso!', 'success', [
          'text' => 'Novo Funcionário adicionado ao Cliente com sucesso.'
        ]);

        return redirect()->route('clients.show', $company->uuid);
    }

    public function show($id)
    {
        $employee = Employee::uuid($id);
        return view('clients.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::user()->hasPermission('edit.cliente.funcionarios')) {
            return abort(403, 'Unauthorized action.');
        }

        $companies = Client::all();
        $employee = Employee::uuid($id);

        return view('clients.employees.edit', compact('companies', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!Auth::user()->hasPermission('edit.cliente.funcionarios')) {
            return abort(403, 'Unauthorized action.');
        }

        $data = $request->request->all();

        $employee = Employee::uuid($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,'.$employee->id,
            'phone' => 'string|max:20',
            'company_id' => 'required',
            'occupation_id' => 'required',
            //'cpf' => 'required|cpf|unique:employees,cpf,'.$employee->id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = Client::uuid($data['company_id']);
        $data['company_id'] = $company->id;

        $occupation = Occupation::uuid($data['occupation_id']);
        $data['occupation_id'] = $occupation->id;

        $data['active'] = $request->has('active');
        $employee->update($data);

        notify()->flash('Sucesso!', 'success', [
          'text' => 'Funcionário atualizado com sucesso.'
        ]);

        return redirect()->route('employees.show', $employee->uuid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

          if(!Auth::user()->hasPermission('delete.cliente.funcionarios')) {
              return response()->json([
                'success' => false,
                'message' => 'Operação não autorizada'
              ]);
          }

          $employee = Employee::uuid($id);

          $employee->delete();

          return response()->json([
            'success' => true,
            'message' => 'Funcionário removido com sucesso.'
          ]);

        } catch(\Exception $e) {
          return response()->json([
            'success' => false,
            'message' => $e->getMessage()
          ]);
        }
    }
}

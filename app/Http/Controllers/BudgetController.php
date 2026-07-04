<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// #[Middleware('auth', only:['index'])]
#[Middleware('auth')]
#[Middleware('verified')]
class BudgetController extends Controller
{
   //-------------------------------------------------------------------------------------------
    public function index(){
        $budgets= Auth::user()->budgets()->get();
        return view('dashboard', [
            'budgets' => $budgets
        ]);
    }

    //-------------------------------------------------------------------------------------------
    public function create(){
        return view('budgets.create');
    }

    
    //-------------------------------------------------------------------------------------------
    public function store(BudgetRequest $request){
        $data = $request->validated();
        
        $user_id = Auth::id();

        // $budget= Budget::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'type' => $data['type'],
        //     'user_id' => $user_id
        // ]);

        $budget= Auth::user()->budgets()->create($data);

        return redirect()->route('dashboard')->with('success', 'Presupuesto creado correctamente');

    }

   //-------------------------------------------------------------------------------------------
    #[Authorize('view', 'budget')]
    public function show(Budget $budget){
        return Inertia::render('Budgets/Show', [
            'budget' => $budget
        ]);
    }

    //-------------------------------------------------------------------------------------------
    
    #[Authorize('update', 'budget')]
    public function edit(Budget $budget){
        return view('budgets.edit', [
            'budget' => $budget
        ]);
    }

    //-------------------------------------------------------------------------------------------
    #[Authorize('update', 'budget')]
    public function update(BudgetRequest $request, Budget $budget){
        $budget->update($request->validated());
        return redirect()->route('dashboard')->with('success', 'Presupuesto actualizado correctamente');
    }

   //-------------------------------------------------------------------------------------------
    #[Authorize('delete', 'budget')]
    public function destroy(Budget $budget){
        $budget->delete();
        return redirect()->route('dashboard')->with('success', 'Presupuesto eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\Expense;
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

        return redirect()->route('budgets.show', $budget)->with('success', 'Presupuesto creado correctamente');

    }

   //-------------------------------------------------------------------------------------------
    #[Authorize('view', 'budget')]
    public function show(Budget $budget){

        // $expenses= Expense::where('budget_id', $budget->id)->latest()->get();
        // $expenses= $budget->expenses()->latest()->get();

        $budget->load([
            'expenses' => fn($query) => $query->latest()
        ]);

        $spent= $budget->expenses()->sum('amount');

        return Inertia::render('Budgets/Show', [
            'budget' => $budget,
            'spent' => $spent,
            'categories' => collect(ExpenseCategory::cases())->map(fn ($category) => [
                'value' => $category->value,
                'label' => $category->label()
            ])
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
        return redirect()->route('budgets.show', $budget)->with('success', 'Presupuesto actualizado correctamente');
    }

   //-------------------------------------------------------------------------------------------
    #[Authorize('delete', 'budget')]
    public function destroy(Budget $budget){
        // Budget::delete($budget->id);
        $budget->delete();
        return redirect()->route('dashboard')->with('success', 'Presupuesto eliminado correctamente.');
    }
}

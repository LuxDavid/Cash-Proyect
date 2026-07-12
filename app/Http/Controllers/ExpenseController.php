<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function store(ExpenseRequest $request, Budget $budget){
        // $data = $request->validated();

        // Expense::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'category' => $data['category'],
        //     'budget_id' => $data['budget_id']
        // ]);

        $budget->expenses()->create($request->validated());

        return redirect()->route('budgets.show', $budget)
                    ->with('success', 'Gasto Registrado correctamente');
    }

    public function update(ExpenseRequest $request, Budget $budget, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('budgets.show', $budget)
                    ->with('success', 'Gasto Actualizado correctamente');

    }

    public function destroy(Budget $budget, Expense $expense)
    {
        $expense->delete();
         return redirect()->route('budgets.show', $budget)
                    ->with('success', 'Gasto Eliminado correctamente');
    }
}

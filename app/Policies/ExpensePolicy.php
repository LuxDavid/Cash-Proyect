<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{

    public function create(User $user, Budget $budget): Response
    {
        return $user->id === $budget->user_id ? Response::allow() : Response::deny('No tienes permiso para agregar gastos a este presupuesto');
    }

    public function update(User $user, Expense $expense): Response
    {
        return $user->id === $expense->budget->user_id ? Response::allow() : Response::deny('No tienes permiso para ediatar los gastos de este presupuesto');
    }

    public function delete(User $user, Expense $expense): Response
    {
        return $user->id === $expense->budget->user_id ? Response::allow() : Response::deny('No tienes permiso para eliminar gastos de este presupuesto');
    }
}


import { useExpenseModalStore } from '@/stores/expense-modal-store'
import { useForm } from '@inertiajs/react';
import React from 'react';
import {route} from 'ziggy-js';
import InputError from './InputError';
import { DialogTitle } from '@headlessui/react'

export default function ExpenseForm() {

    const budget = useExpenseModalStore(state => state.budget);
    const expense= useExpenseModalStore(state => state.expense);
    const categories = useExpenseModalStore(state => state.categories);
    const closeModal = useExpenseModalStore(state => state.closeModal);

    const isEdting= !!expense;

    const {data, setData, post, put, errors, reset, processing} = useForm({
        name: expense?.name ?? '',
        amount: expense?.amount ?? '',
        category: expense?.category ?? ''
    });

    if(!budget) return null

    const submit= (e: React.SubmitEvent<HTMLFormElement>) => {
            e.preventDefault();

            if(isEdting && expense){
                put(route('expenses.update', [budget.id, expense.id]), {
                    onSuccess: () => {
                        reset()
                        closeModal()
                    },
                    preserveScroll:true
                });
                return;
            }

            post(route('expenses.store', budget.id), {
                onSuccess:() => {
                    reset();
                    closeModal();
                },
                    preserveScroll:true
            });
    }

    return (
        <>

             <DialogTitle as="h3" className="text-4xl font-black mt-10 text-center text-amber-50">
                {isEdting ? 'Editar' : 'Nuevo'} {' '} Gasto
            </DialogTitle>

        <div className='p-10 flex justify-center bg-amber-50'>
            <form className='flex flex-col space-y-3 w-full' onSubmit={submit}>
                <div className='space-y-3'>
                    <label htmlFor="name" className='block text-xl font-bold'>Nombre Gasto</label>
                    <input
                        id='name'
                        type="text"
                        placeholder="Nombre del gasto"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                    />
                </div>

                {errors.name && <InputError>{errors.name}</InputError>}

                <div className='space-y-3'>
                    <label htmlFor="amount" className='block text-xl font-bold'>Cantidad Gasto</label>
                    <input
                        id='amount'
                        type="number"
                        placeholder="Cantidad"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                        value={data.amount}
                        onChange={e => setData('amount', e.target.value)}
                    />
                </div>

                {errors.amount && <InputError>{errors.amount}</InputError>}

                {budget?.type === 'general' && (
                    <div className='space-y-3'>
                        <label htmlFor="category" className='block text-xl font-bold'>Categoría Gasto</label>
                        <select
                            name="category"
                            id="category"
                            className='w-full border border-gray-300 p-3 rounded-lg'
                            value={data.category}
                            onChange={e => setData('category', e.target.value)}
                        >
                            <option value="">Selecciona Categoría</option>
                            {categories.map(category => <option key={category.value} value={category.value}>{category.label}</option>)}
                        </select>

                         {errors.category && <InputError>{errors.category}</InputError>}
                    </div>
                )}

                <button disabled={processing} type="submit" className={`${processing ? 'opacity-60 cursor-not-allowed' : 'hover:bg-purple-800 cursor-pointer'}
                    mt-5 bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer`}>
                    {processing 
                    ? 'Guardando...' 
                    : isEdting 
                        ? 
                        'Actualizar gasto'
                    : 'Agregar Gasto'
                    } 
                </button>
            </form>
        </div>
        </>
    )
}

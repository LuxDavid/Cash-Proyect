import React from 'react'

export default function ExpenseForm() {
    return (

        <div className='p-10 flex justify-center bg-amber-50'>
            <form className='flex flex-col space-y-3 w-full'>
                <div className='space-y-3'>
                    <label htmlFor="name" className='block text-xl font-bold'>Nombre Gasto</label>
                    <input
                        id='name'
                        type="text"
                        placeholder="Nombre del gasto"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                    />
                </div>

                <div className='space-y-3'>
                    <label htmlFor="amount" className='block text-xl font-bold'>Cantidad Gasto</label>
                    <input
                        id='amount'
                        type="number"
                        placeholder="Cantidad"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                    />
                </div>

                <button type="submit" className="mt-5 bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer">
                    Agregar Gasto
                </button>
            </form>
        </div>
    )
}

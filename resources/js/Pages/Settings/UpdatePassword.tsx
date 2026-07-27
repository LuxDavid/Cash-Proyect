import { route } from "ziggy-js";
import AppLayout from "@/Layouts/AppLayout";
import { useForm, usePage } from "@inertiajs/react";
import React from "react";
import InputError from "../../../components/InputError";

export default function UpdatePassword() {

  const {data, setData, put, errors} = useForm({
      current_password:'',
      password:'',
      password_confirmation:''
  });

  const submit= (e: React.SubmitEvent) => {
    e.preventDefault();
    put(route('settings.password.update'));
  }

  return (
    <AppLayout title="Actualizar Password">      
      <div className="sm:flex sm:items-center mt-10">
        <div className="sm:flex-auto">
          <h1 className="font-bold text-4xl">Cambiar Contraseña</h1>
          <p className="mt-2 text-xl text-gray-500">Si deseas cambiar tu contraseña, utiliza este formulario </p>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <a href={route('dashboard')}
            className="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Volver a Presupuestos</a>
        </div>
      </div>

      <form
        className="mt-14 space-y-3 max-w-2xl mx-auto"
        onSubmit={submit}
      >

        <div className="flex flex-col gap-2">
          <label className="font-bold text-2xl" htmlFor="password">Contraseña Actual</label>

          <input
            id="name"
            type="password"
            placeholder="Tu Contraseña Actual"
            className="w-full border border-gray-300 p-3 rounded-lg"
            value={data.current_password}
            onChange={e => setData('current_password', e.target.value)}
          />
          {errors.current_password && <InputError>{errors.current_password}</InputError>}
        </div>

        <div className="flex flex-col gap-2">
          <label className="font-bold text-2xl" htmlFor="current_password">Nueva Contraseña</label>

          <input
            id="current_password"
            type="password"
            placeholder="Nueva Contraseña. Min. 8 caracteres"
            className="w-full border border-gray-300 p-3 rounded-lg"
            value={data.password}
            onChange={e => setData('password', e.target.value)}
          />
          {errors.password && <InputError>{errors.password}</InputError>}
        </div>


        <div className="flex flex-col gap-2 mt-4">
          <label className="font-bold text-2xl" htmlFor="password_confirmation">
            Confirmar Contraseña
          </label>

          <input
            id="password_confirmation"
            type="password"
            placeholder="Repite la nueva contraseña"
            className="w-full border border-gray-300 p-3 rounded-lg"
            value={data.password_confirmation}
            onChange={e => setData('password_confirmation', e.target.value)}
          />
            
        </div>

        <input type="submit" value='Cambiar Contraseña'
          className="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
      </form>
    </AppLayout>
  )
}
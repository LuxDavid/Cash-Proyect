import { Head, usePage } from "@inertiajs/react";
import React, { useEffect } from "react";
import { toast, ToastContainer } from "react-toastify";
import { PropsWithChildren } from 'react';

// type Props = {
//     title:string
//     children: React.ReactNode
// }

type Props= PropsWithChildren<{
    title:string
}>

export default function AppLayout({title, children} : Props){

    const {flash} = usePage().props;

     useEffect(() => {
        if(flash.success){
            toast.success(flash.success);
        }

        if(flash.error){
            toast.success(flash.error);
        }
        
    }, [flash]);

    return (
        <>
            <Head title={title} />
            {children}
            <ToastContainer/>
        </>
    )
}
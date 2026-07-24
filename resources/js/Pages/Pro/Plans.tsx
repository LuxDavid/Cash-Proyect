import { Head } from "@inertiajs/react";
import PricingTable from "../../../components/PricingTable";


export default function Plans(){
    return (
        <>
            <Head title="Planes"/>
            <main className="mt-5">
                <PricingTable/>
            </main>
        </>
    )
}
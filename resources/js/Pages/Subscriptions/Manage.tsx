import { Head, usePage } from "@inertiajs/react";
import SubscriptionStatus from '../../../components/subscriptions/SubscriptionStatus';
import { Subscription } from "../../../types/subscription";
import SubscriptionDowngrade from '../../../components/subscriptions/SubscriptionDowngrade';
import SubscriptionUpgrade from '../../../components/subscriptions/SubscriptionUpgrade';
import { toast,ToastContainer } from 'react-toastify';
import { useEffect } from "react";
import SubscriptionCancellation from '../../../components/subscriptions/SubscriptionCancellation';


type Props = {
    subscription: Subscription
}

const statusColors = {
  green: 'bg-green-50 text-green-600 border-green-200',
  yellow: 'bg-yellow-50 text-yellow-600 border-yellow-200',
  orange: 'bg-orange-50 text-orange-600 border-orange-200',
  red: 'bg-red-50 text-red-600 border-red-200',
  gray: 'bg-gray-50 text-gray-700 border-gray-200',
};

export default function Manage({subscription}: Props){

    const {flash} = usePage().props;

    const title= 'Administra tu suscripción';
    const isYearly = subscription.plan === 'yearly';

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
            <Head title={title}/>

            <main className="max-w-3xl mx-auto py-12 px-4">
                <h1 className="text-3xl font-black mb-2">{title}</h1>
                <p className="text-gray-500 mb-8 text-lg">
                    Cambia tu Plan, Cancela o Reactiva tu Suscripçión cuando quieras.
                </p>

                <SubscriptionStatus
                    isYearly={isYearly}
                    price={subscription.price}
                    status_label={subscription.status_label}
                    color={statusColors[subscription.status_label.color]}
                />

                {
                    subscription.on_grace_period ? (
                        <p>Suscripcion Cancelada...</p>
                    ) : (
                        <>
                            {!isYearly && <SubscriptionUpgrade/>}
                            {isYearly && <SubscriptionDowngrade 
                            next_billing_date={subscription.next_billing_date}
                            ends_at={subscription.ends_at}
                            />}

                            <SubscriptionCancellation
                                next_billing_date={subscription.next_billing_date} 
                            />
                        </>
                    )
                }
            </main>

            <ToastContainer/>
        </>
    )
}
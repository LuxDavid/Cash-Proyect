import '@inertiajs/core'

declare module '@inertiajs/core' {
    export interface InertiaConfig{
        sharedPageProps:{
            flash:{
            success?: string
            error?: string
        },
        user:{
            user:{
                name: string
                email:string
            },
            subscribed:boolean,
            plan: string | null
        }
        }
    }
}
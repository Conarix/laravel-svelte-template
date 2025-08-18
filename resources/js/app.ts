import './bootstrap';
import {createInertiaApp, type ResolvedComponent} from '@inertiajs/svelte';
import { mount } from 'svelte';
import GuestLayout from "@/Layouts/GuestLayout.svelte";
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import AppLayout from "@/Layouts/AppLayout.svelte";

const resolve = async (name: string) => {
    const page = await resolvePageComponent(
        `./Pages/${name}.svelte`,
        import.meta.glob<ResolvedComponent>('./Pages/**/*.svelte'),
    );

    return {
        default: page.default,
        layout: name.startsWith('Auth') ? GuestLayout : AppLayout,
    }
};

createInertiaApp({
    resolve,
    setup({ el, App, props }) {
        console.log({el, App, props});
        mount(App, { target: el, props });
    },
});

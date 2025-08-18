import { PageProps as InertiaPageProps } from "@inertiajs/core";
import type { PageProps as AppPageProps } from "@/types/index";
import type { route as routeFn } from "ziggy-js";
import type { AxiosInstance } from "axios";

declare global {
    const route: typeof routeFn;
    interface Window {
        axios: AxiosInstance;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

import { get } from 'svelte/store';
import { page } from "@inertiajs/svelte";
import type {PermissionEnum} from "@/types/enums";

export const canAll = (permissions: PermissionEnum[]) =>
    permissions.every((permission) => get(page).props.auth.permissions[permission]);

export const canAny = (permissions: PermissionEnum[]) =>
    permissions.some((permission) => get(page).props.auth.permissions[permission]);

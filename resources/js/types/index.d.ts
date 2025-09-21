import type {Component} from "svelte";
import type {IconProps} from "@lucide/svelte";
import type {Config} from "ziggy-js";
import {ChangeTypeEnum, PermissionEnum, RoleEnum, ToastTypeEnum} from "@/types/enums";
import type {ButtonVariant} from "@/Components/UI/Button.svelte";
import type {Writable} from "svelte/store";
import type {InertiaForm} from "@inertiajs/svelte";

export interface AuditTrack {
    id: number,
    user_id: number,
    type: ChangeTypeEnum,
    changes: { [key: string]: any },
    created_at: string,
    updated_at: string,

    user: User,
}

export interface Role {
    id: number,
    name: RoleEnum,
    created_at: string,
    updated_at: string,
}

export interface RoleWithPermissions extends Role {
    permissions: Permission[];
}

export interface Permission {
    id: number,
    name: PermissionEnum,
    display_name: string,
    created_at: string,
    updated_at: string,
}

export interface User {
    id: number,
    name: string,
    email: string,
    reset_password_on_login: boolean,
    email_verified_at: string | null,
    role: Role,
    permissions: Permission[],
    can_be_impersonated: boolean,
    created_at: string,
    updated_at: string,
    deleted_at: string | null,

    api_token?: string,
}

export interface PasswordValidationRules {
    min: number | null;
    max: number | null;
    mixedCase: boolean;
    letters: boolean;
    numbers: boolean;
    symbols: boolean;
}

export interface WithAuditTracks<T extends object> extends T {
    audit_tracks: AuditTrack[],
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string,
    environment: 'local' | 'development' | 'production',
    messages: { [key in keyof typeof ToastTypeEnum ]: string | null }
    auth: {
        user: User | null,
        impersonator: User | null,
        permissions: { [key in PermissionEnum]: boolean }
    }
    ziggy: Config & { location: string, base_url: string, current_route: string },
}

export interface NavItemBase {
    icon: Component<IconProps>,
    label: string,
    permission?: PermissionEnum
}

export interface NavItemLink extends NavItemBase {
    route: string,
}

export interface NavItemMenu extends NavItemBase {
    subItems: NavItemLink[]
}

export type NavItemsType = (NavItemLink | NavItemMenu)[];

export interface Paginator<T> {
    current_page: number,
    data: T[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: Link[],
    next_page_url: null,
    path: string,
    per_page: number,
    prev_page_url: number,
    to: number,
    total: number,
}

export interface Link {
    url: string | null,
    label: string
    active: boolean
}

export interface BaseHeaderButton {
    label: string,
    variant?: ButtonVariant,
    permission?: PermissionEnum
}

export interface AnchorHeaderButton extends BaseHeaderButton {
    href: string
}

export interface NonGetAnchorHeaderButton extends BaseHeaderButton {
    href: string
    method: 'post' | 'put' | 'patch' | 'delete',
}

export interface ClickEventHeaderButton extends BaseHeaderButton {
    onclick: () => void
}

export type HeaderButton = AnchorHeaderButton | NonGetAnchorHeaderButton | ClickEventHeaderButton;

export type Form<T> = Writable<InertiaForm<T>>;

export type SelectOptions = (Option|OptionGroup)[]

export interface OptionGroup {
    label: string,
    options: Option[]
}

export interface Option {
    label: string,
    value: string,
}

export type CastFunction<T> = (value: T) => T;

export type OpenDialogFunction = undefined | (() => void);
export type CloseDialogFunction = undefined | (() => void);

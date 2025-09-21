<script lang="ts">

    import {Home, ShieldQuestionMark, ShieldUser, User} from "@lucide/svelte";
    import {route} from "ziggy-js";
    import {type NavItemsType} from "@/types";
    import NavItems from '@/Components/Layout/Navigation/NavItems.svelte';
    import {PermissionEnum} from "@/types/enums";
    import type {MediaQuery} from "svelte/reactivity";

    const navItems: NavItemsType = [
        {
            icon: Home,
            label: "Home",
            route: route('dashboard'),
        },
        {
            icon: ShieldUser,
            label: "Admin",
            subItems: [
                {
                    icon: User,
                    label: 'Users',
                    route: route('admin.users.index'),
                    permission: PermissionEnum.USERS_VIEW,
                },
                {
                    icon: ShieldQuestionMark,
                    label: 'Role Permissions',
                    route: route('admin.permissions.edit'),
                    permission: PermissionEnum.PERMISSIONS_EDIT,
                }
            ]
        }
    ];

    let {
        open = $bindable(),
        smallView,
        minimal = false,
        topNav = false,
    } : {
        open: boolean,
        smallView: MediaQuery,
        minimal?: boolean,
        topNav?: boolean,
    } = $props();

</script>

<div class={["flex", !topNav && "flex-col", "justify-start", topNav && "items-start", !topNav && "items-center", !topNav && "w-full", "gap-2"]}>
    <NavItems {navItems} bind:open {smallView} {minimal} {topNav} />
</div>

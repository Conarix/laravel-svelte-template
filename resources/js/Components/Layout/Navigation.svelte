<script lang="ts">

    import {Home, User} from "@lucide/svelte";
    import {route} from "ziggy-js";
    import {type NavItemsType} from "@/types";
    import NavItems from '@/Components/Layout/NavItems.svelte';
    import {PermissionEnum} from "@/types/enums";

    const navItems: NavItemsType = [
        {
            icon: Home,
            label: "Home",
            route: route('dashboard'),
        },
        {
            icon: User,
            label: "Users",
            route: route('admin.users.index'),
            permission: PermissionEnum.USERS_VIEW,
        }
    ];

</script>

<div class="flex flex-col justify-start items-start w-full gap-2">
    <NavItems {navItems} />
</div>

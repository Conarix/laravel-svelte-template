<script lang="ts">

    import { page } from "@inertiajs/svelte";
    import type {NavItemsType} from "@/types";
    import NavItem from "@/Components/Layout/NavItem.svelte";
    import NavItemSubMenu from "@/Components/Layout/NavItemSubMenu.svelte";
    import HasPermission from "@/Components/Conditionals/HasPermission.svelte";

    let {
        navItems
    } : {
        navItems: NavItemsType
    } = $props();
</script>

{#each navItems as navItem, i (i)}
    <HasPermission all={[navItem.permission]}>
        {#if 'route' in navItem}
            <NavItem icon={navItem.icon} label={navItem.label} route={navItem.route} />
        {:else}
            <NavItemSubMenu icon={navItem.icon} label={navItem.label} subItems={navItem.subItems} />
        {/if}
    </HasPermission>
{/each}



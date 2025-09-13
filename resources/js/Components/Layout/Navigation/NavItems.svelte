<script lang="ts">
    import type {NavItemsType} from "@/types";
    import NavItem from "@/Components/Layout/Navigation/NavItem.svelte";
    import NavItemSubMenu from "@/Components/Layout/Navigation/NavItemSubMenu.svelte";
    import HasPermission from "@/Components/Conditionals/HasPermission.svelte";
    import type {MediaQuery} from "svelte/reactivity";

    let {
        navItems,
        open = $bindable(),
        smallView,
        minimal,
        topNav,
    } : {
        navItems: NavItemsType,
        open: boolean,
        smallView: MediaQuery,
        minimal: boolean,
        topNav: boolean,
    } = $props();
</script>

{#each navItems as navItem, i (i)}
    <HasPermission all={[navItem.permission]}>
        {#if 'route' in navItem}
            <NavItem icon={navItem.icon} label={navItem.label} route={navItem.route} bind:open {smallView} {minimal} />
        {:else}
            <NavItemSubMenu icon={navItem.icon} label={navItem.label} subItems={navItem.subItems} bind:open {smallView} {minimal} {topNav} />
        {/if}
    </HasPermission>
{/each}



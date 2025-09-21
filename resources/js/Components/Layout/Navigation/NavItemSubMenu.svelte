<script lang="ts">

    import type {NavItemBase, NavItemLink} from "@/types";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import NavItem from "@/Components/Layout/Navigation/NavItem.svelte";
    import NavItems from "./NavItems.svelte";
    import type {MediaQuery} from "svelte/reactivity";
    import {clickOutside} from "@/Actions/ClickOutside";
    import {canAny} from "@/utils/auth.svelte";

    let {
        icon,
        label,
        subItems,
        open = $bindable(),
        smallView,
        minimal,
        topNav,
    }: {
        icon: NavItemBase["icon"],
        label: NavItemBase['label'],
        subItems: NavItemLink[],
        open: boolean,
        smallView: MediaQuery,
        minimal: boolean,
        topNav: boolean,
    } = $props();

    let topNavOpenState: boolean = $state(false);
    let accessible: boolean = $derived.by(() => {
        const permissions = subItems.map((subItem) => subItem.permission)
            .filter((permission) => permission !== undefined);

        return canAny(permissions);
    })
</script>

{#if accessible}
    {#if topNav}
        <div class="relative">
            <NavItem {icon} {label} openState={topNavOpenState} bind:open {smallView} {minimal}
                     onclick={() => topNavOpenState = true}/>

            {#if topNavOpenState}
                <div
                    class="absolute top-[110%] left-0 w-fit min-w-max max-w-[200px] bg-sidebar rounded-md border shadow-lg whitespace-normal"
                    use:clickOutside={() => topNavOpenState = false}
                >
                    <NavItems navItems={subItems} bind:open {smallView} {minimal} {topNav}/>
                </div>
            {/if}
        </div>
    {:else}
        <Collapsible>
            {#snippet trigger(openState)}
                <NavItem {icon} {label} {openState} bind:open {smallView} {minimal}/>
            {/snippet}

            <NavItems navItems={subItems} bind:open {smallView} {minimal} {topNav}/>
        </Collapsible>
    {/if}
{/if}

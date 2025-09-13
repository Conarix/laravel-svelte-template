<script lang="ts">

    import type {NavItemBase, NavItemsType} from "@/types";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import NavItem from "@/Components/Layout/Navigation/NavItem.svelte";
    import NavItems from "./NavItems.svelte";
    import type {MediaQuery} from "svelte/reactivity";
    import { clickOutside } from "@/Actions/ClickOutside";

    let {
        icon,
        label,
        subItems,
        open = $bindable(),
        smallView,
        minimal,
        topNav,
    } : {
        icon: NavItemBase["icon"],
        label: NavItemBase['label'],
        subItems: NavItemsType,
        open: boolean,
        smallView: MediaQuery,
        minimal: boolean,
        topNav: boolean,
    } = $props();

    const IconComponent = $derived(icon);

    let topNavOpenState: boolean = $state(false);
</script>

{#if topNav}
    <div class="relative">
        <NavItem {icon} {label} openState={topNavOpenState} bind:open {smallView} {minimal} onclick={() => topNavOpenState = !topNavOpenState} />

        {#if topNavOpenState}
            <div
                class="absolute top-[110%] left-0 w-full bg-sidebar rounded-md border shadow-lg"
                use:clickOutside={() => topNavOpenState = false}
            >
                <NavItems navItems={subItems} bind:open {smallView} {minimal} {topNav} />
            </div>
        {/if}
    </div>
{:else}
    <Collapsible>
        {#snippet trigger(openState)}
            <NavItem {icon} {label} {openState} bind:open {smallView} {minimal} />
        {/snippet}

        <NavItems navItems={subItems} bind:open {smallView} {minimal} {topNav} />
    </Collapsible>
{/if}

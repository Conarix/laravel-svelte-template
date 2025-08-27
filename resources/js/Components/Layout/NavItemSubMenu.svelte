<script lang="ts">

    import type {NavItemBase, NavItemsType} from "@/types";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import NavItem from "@/Components/Layout/NavItem.svelte";
    import NavItems from "./NavItems.svelte";
    import type {MediaQuery} from "svelte/reactivity";

    let {
        icon,
        label,
        subItems,
        open = $bindable(),
        smallView,
    } : {
        icon: NavItemBase["icon"],
        label: NavItemBase['label'],
        subItems: NavItemsType,
        open: boolean,
        smallView: MediaQuery,
    } = $props();

    const IconComponent = $derived(icon);
</script>

<Collapsible>
    {#snippet trigger(openState)}
        <NavItem {icon} {label} {openState} bind:open {smallView} />
    {/snippet}

    <NavItems navItems={subItems} bind:open {smallView} />
</Collapsible>

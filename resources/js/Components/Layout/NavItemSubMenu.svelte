<script lang="ts">

    import type {NavItemBase, NavItemsType} from "@/types";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import NavItem from "@/Components/Layout/NavItem.svelte";
    import NavItems from "./NavItems.svelte";

    const {
        icon,
        label,
        subItems
    } : {
        icon: NavItemBase["icon"],
        label: NavItemBase['label'],
        subItems: NavItemsType
    } = $props();

    const IconComponent = $derived(icon);
</script>

<Collapsible>
    {#snippet trigger(openState)}
        <NavItem {icon} {label} {openState} />
    {/snippet}

    <NavItems navItems={subItems} />
</Collapsible>

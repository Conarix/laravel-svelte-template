<script lang="ts">

    import { ChevronDown } from "@lucide/svelte";
    import type {Component} from "svelte";
    import type {IconProps} from "@lucide/svelte";
    import {Link} from "@inertiajs/svelte";
    import {page} from "@inertiajs/svelte";

    const {
        icon,
        label,
        route,
        openState,
    } : {
        icon: Component<IconProps>,
        label: string,
        route?: string
        openState?: boolean
    } = $props();

    const IconComponent = $derived(icon);
    const selected = $derived(route === $page.props.ziggy.location);

</script>

{#snippet itemRow()}
    <div class="flex justify-between items-center w-full hover:bg-accent p-2 rounded-md {selected ? 'bg-accent' : ''}">
        <div class="flex justify-start items-center gap-1">
            <IconComponent />

            {label}
        </div>

        {#if openState !== undefined}
            <ChevronDown class={['transition-[rotate]', 'duration-100', openState && 'rotate-180']} />
        {/if}
    </div>
{/snippet}

{#if route !== undefined}
    <Link href={route} class="w-full">
        {@render itemRow()}
    </Link>
{:else}
    {@render itemRow()}
{/if}

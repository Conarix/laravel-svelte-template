<script lang="ts">

    import { ChevronDown } from "@lucide/svelte";
    import type {Component} from "svelte";
    import type {IconProps} from "@lucide/svelte";
    import {Link} from "@inertiajs/svelte";
    import {page} from "@inertiajs/svelte";
    import type {MediaQuery} from "svelte/reactivity";

    let {
        icon,
        label,
        route,
        openState,
        open = $bindable(),
        smallView,
        minimal,
        onclick,
    } : {
        icon: Component<IconProps>,
        label: string,
        route?: string,
        openState?: boolean,
        open: boolean,
        smallView: MediaQuery,
        minimal: boolean,
        onclick?: () => void,
    } = $props();

    const IconComponent = $derived(icon);
    const selected = $derived(route === $page.props.ziggy.location);

    const onSuccess = () => {
        if (smallView.current) {
            open = false;
        }
    }

</script>

{#snippet itemRow()}
    <div class="flex justify-between items-center w-full hover:bg-accent p-2 rounded-md {selected ? 'bg-accent' : ''}">
        <div class="flex justify-start items-center gap-1 text-nowrap">
            {#if !minimal}
                <IconComponent />
            {/if}

            {label}
        </div>

        {#if openState !== undefined}
            <ChevronDown class={['transition-[rotate]', 'duration-100', openState && 'rotate-180']} />
        {/if}
    </div>
{/snippet}

{#if route !== undefined}
    <Link href={route} class="w-full" on:success={onSuccess}>
        {@render itemRow()}
    </Link>
{:else}
    {#if onclick !== undefined}
        <div {onclick} role="button" tabindex="-1" onkeydown={(e) => e.key === 'Enter' && onclick()} class="cursor-pointer">
            {@render itemRow()}
        </div>
    {:else}
        {@render itemRow()}
    {/if}
{/if}

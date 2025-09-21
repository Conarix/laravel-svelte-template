<script lang="ts">

    import {Link, router} from "@inertiajs/svelte";
    import Button from "@/Components/UI/Button.svelte";
    import { page } from "@inertiajs/svelte";
    import type {Snippet} from "svelte";
    import type {HeaderButton} from "@/types";

    type Props = {
        buttons?: HeaderButton[],
        duplicate?: boolean;
        children: Snippet
    }

    const {
        buttons = [],
        duplicate = false,
        children
    }: Props = $props();
</script>

{#snippet buttonRow()}
    <div class="flex justify-end items-center w-full gap-2">
        {#each buttons as button, i (i)}
            {#if button.permission === undefined || $page.props.auth.permissions[button.permission] }
                {#if 'method' in button}
                    <Button variant={button.variant} onclick={() => router[button.method ?? 'get'](button.href)}>
                        {button.label}
                    </Button>
                {:else if 'onclick' in button}
                    <Button variant={button.variant} onclick={button.onclick}>
                        {button.label}
                    </Button>
                {:else}
                    <Link href={button.href}>
                        <Button variant={button.variant}>
                            {button.label}
                        </Button>
                    </Link>
                {/if}
            {/if}
        {/each}
    </div>
{/snippet}

<div class="flex flex-col w-full gap-4">
    {@render buttonRow()}

    {@render children()}

    {#if duplicate}
        {@render buttonRow()}
    {/if}
</div>

<script lang="ts">

    import type {Snippet} from "svelte";
    import autoAnimate from "@formkit/auto-animate";

    let {
        trigger,
        children,
        open = false,
    }: {
        trigger: Snippet<[boolean]>,
        children: Snippet
        open?: boolean
    } = $props();

    let openState = $state(open);
</script>

<div class="flex flex-col w-full">
    <button type="button" class="w-full" onclick={() => openState = !openState}>
        {@render trigger(openState)}
    </button>

    <div class="flex flex-col w-full pl-2" use:autoAnimate={{duration: 100}}>
        {#if openState}
            {@render children()}
        {/if}
    </div>
</div>


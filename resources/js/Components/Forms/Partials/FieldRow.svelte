<script lang="ts">

    import type {Snippet} from "svelte";

    const {
        justify,
        align,
        columns,
        children,
    } : {
        justify?: CSSStyleDeclaration['justifyContent'],
        align?: CSSStyleDeclaration['alignItems'],
        columns?: number,
        children: Snippet
    } = $props();
</script>

{#if columns !== undefined}
    <div class="grid gap-2 w-full" style="grid-template-columns: repeat({columns}, minmax(0, 1fr));">
        {@render children()}
    </div>
{:else}
    <div class="flex w-full gap-2" style:justify-content={justify ?? 'center'} style:align-items={align ?? 'center'}>
        {@render children()}
    </div>
{/if}

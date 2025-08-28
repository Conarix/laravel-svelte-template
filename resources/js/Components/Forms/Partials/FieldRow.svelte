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
        columns?: number | {default: number, sm?: number, md?: number, lg?: number, xl?: number, "2xl"?: number},
        children: Snippet
    } = $props();

    const gridColsClasses: string[] = $derived.by(() => {
        const classes: string[] = [];

        if (columns === undefined) {
            return [];
        }

        if (typeof columns === "number") {
            return [`grid-cols-${columns}`];
        }

        classes.push(`grid-cols-${columns.default}`);

        for (const [modifier, value] of Object.entries(columns)) {
            if (modifier === "default") {
                continue;
            }

            classes.push(`${modifier}:grid-cols-${value}`);
        }

        return classes;
    })
</script>

{#if columns !== undefined}
    <div class={["grid", "gap-2", "w-full"].concat(gridColsClasses)}>
        {@render children()}
    </div>
{:else}
    <div class="flex w-full gap-2" style:justify-content={justify ?? 'center'} style:align-items={align ?? 'center'}>
        {@render children()}
    </div>
{/if}

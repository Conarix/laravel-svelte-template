<script lang="ts" generics="T extends object">
    import {v4 as uuidV4} from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import { inputClasses } from "@/Components/Inputs/Partials/classes";

    let {
        label,
        values,
        key,
        nameCast
    }: {
        label?: string,
        values: T[],
        key: keyof T,
        nameCast?: (s: any) => string,
    } = $props();

    const classes = inputClasses + " list-item";

    const id = uuidV4();
</script>

<div class="flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <ul class="w-full list-disc list-inside">
        {#each values as value, i (i)}
            <span class={classes}>{nameCast ? nameCast(value[key]) : value[key]}</span>
        {:else}
            <span>No Values</span>
        {/each}
    </ul>
</div>

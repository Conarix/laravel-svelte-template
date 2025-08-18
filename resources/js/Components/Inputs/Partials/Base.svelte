<script lang="ts">
    import type {HTMLInputAttributes, HTMLInputTypeAttribute} from "svelte/elements";
    import { inputClasses } from "@/Components/Inputs/Partials/classes";

    let {
        id,
        value = $bindable(''),
        type,
        ...restProps
    } : HTMLInputAttributes & {
        id: string
        type: HTMLInputTypeAttribute
        value: string
    } = $props();



    let classes: string = $derived.by(() => {
        let classesString = inputClasses;

        if (Object.keys(restProps).includes('class')) {
            classesString += ` ${restProps.class}`
        }

        delete restProps.class;

        return classesString;
    })
</script>

<input
    {id}
    class={classes}
    {type}
    bind:value
    {...restProps}
/>

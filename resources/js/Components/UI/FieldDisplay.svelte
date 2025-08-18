<script lang="ts">
    import {v4 as uuidV4} from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import { inputClasses } from "@/Components/Inputs/Partials/classes";
    import InputHint from "@/Components/Inputs/Partials/InputHint.svelte";

    let {
        label,
        value,
        valueCast,
        hint,
    }: {
        label?: string,
        value: string | null,
        valueCast?: (s: string | null) => string,
        hint?: string,
    } = $props();

    const id = uuidV4();
</script>

<div class="flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <span class={inputClasses}>{valueCast ? valueCast(value) : value}</span>
    <InputHint {hint} />
</div>

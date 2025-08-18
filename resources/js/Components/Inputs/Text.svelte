<script lang="ts">
    import {v4 as uuidV4} from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import Base from "@/Components/Inputs/Partials/Base.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";

    let {
        label,
        value = $bindable(''),
        error,
        placeholder,
    }: {
        label?: string,
        value: string,
        error?: string,
        placeholder?: string
    } = $props();

    const id = uuidV4();
</script>

<div class="flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <Base {id} type="text" bind:value {placeholder} />
    <InputError {error}/>
</div>

<script lang="ts">
    import { v4 as uuidV4 } from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import Base from "@/Components/Inputs/Partials/Base.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";

    let {
        label,
        value = $bindable(''),
        error
    } : {
        label: string,
        value: string,
        error?: string,
    } = $props();

    const id = uuidV4();
</script>

<FieldLabel {id}>{label}</FieldLabel>
<Base {id} type="email" bind:value />
<InputError {error} />

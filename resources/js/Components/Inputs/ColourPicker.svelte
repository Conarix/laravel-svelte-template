<script lang="ts">
    import ColorPicker from 'svelte-awesome-color-picker';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";
    import {v4 as uuidV4} from "uuid";
    import InputHint from "@/Components/Inputs/Partials/InputHint.svelte";

    interface Props {
        label: string;
        value?: string;
        hint?: string;
        error?: string;
    }

    let {
        label,
        value = $bindable(),
        hint ,
        error
    }: Props = $props();

    const id = uuidV4();
</script>

<div class="flex flex-col gap-2">
    <FieldLabel {id}>{label}</FieldLabel>
    <ColorPicker bind:hex={value} />
    <InputHint {hint} />
    <InputError {error} />
</div>

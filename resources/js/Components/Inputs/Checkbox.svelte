<script lang="ts">
    import { v4 as uuidV4 } from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import Base from "@/Components/Inputs/Partials/Base.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";

    let {
        label,
        checked = $bindable(false),
        error,
    } : {
        label: string,
        checked: boolean,
        error?: string
    } = $props();

    const id = uuidV4();
</script>

<div class="flex justify-center items-center gap-4">
    <input
        {id}
        type="checkbox"
        class="border-primary ring-offset-background focus-visible:ring-ring data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground peer box-content size-4 shrink-0 rounded-sm border focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[disabled=true]:cursor-not-allowed data-[disabled=true]:opacity-50"
        bind:checked
    />

    <FieldLabel {id}>{label}</FieldLabel>
</div>
<InputError {error} />

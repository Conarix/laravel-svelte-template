<script lang="ts">
    import {v4 as uuidV4} from 'uuid';
    import FieldLabel from "@/Components/Inputs/Partials/FieldLabel.svelte";
    import Base from "@/Components/Inputs/Partials/Base.svelte";
    import InputError from "@/Components/Inputs/Partials/InputError.svelte";
    import InputHint from "@/Components/Inputs/Partials/InputHint.svelte";
    import { Eye, EyeClosed } from "@lucide/svelte";
    import Button from "@/Components/UI/Button.svelte";

    let {
        label,
        value = $bindable(''),
        error,
        hint,
    }: {
        label: string,
        value: string,
        error?: string,
        hint?: string,
    } = $props();

    let showPassword: boolean = $state(false);

    const id = uuidV4();
</script>

<div class="flex flex-col">
    <FieldLabel {id}>{label}</FieldLabel>
    <div class="flex justify-between items-center w-full gap-2">
        <Base {id} type={showPassword ? 'text' : 'password'} bind:value class="flex-grow" />

        <Button
            variant="outline"
            type="button"
            onclick={() => showPassword = !showPassword}
            title={showPassword ? 'Hide Password' : 'Show Password'}
        >
            {#if showPassword}
                <Eye />
            {:else}
                <EyeClosed />
            {/if}
        </Button>
    </div>
    <InputHint {hint} />
    <InputError {error}/>
</div>

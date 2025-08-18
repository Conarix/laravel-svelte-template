<script lang="ts">
    import Base from "@/Components/Forms/Partials/Base.svelte";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import {route} from "ziggy-js";
    import Email from "@/Components/Inputs/Email.svelte";
    import Password from "@/Components/Inputs/Password.svelte";
    import {useForm} from "@inertiajs/svelte";
    import Debug from "@/Components/Debug.svelte";
    import Checkbox from "@/Components/Inputs/Checkbox.svelte";
    import {toast} from "svelte-sonner";

    let form = $state(useForm({
        email: '',
        password: '',
        remember_me: false,
    }));

    const onsubmit = () => {
        $form.post(
            route('auth.authenticate'),
            {
                onError: () => toast.error("Failed to Login"),
            }
        )
    }
</script>

<Base {onsubmit} submitDisabled={!$form.isDirty}>
    <FieldRow columns={1}>
        <Email label="Email Address" bind:value={$form.email} error={$form.errors.email} />
    </FieldRow>

    <FieldRow columns={1}>
        <Password label="Password" bind:value={$form.password} error={$form.errors.password} />
    </FieldRow>

    {#snippet beforeSubmit()}
        <Checkbox label="Remember Me" bind:checked={$form.remember_me} error={$form.errors.remember_me} />
    {/snippet}
</Base>

<!--<Debug {$form} />-->

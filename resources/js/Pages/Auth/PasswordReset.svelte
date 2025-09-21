<script lang="ts">
    import Base from "@/Components/Forms/Partials/Base.svelte";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import Password from "@/Components/Inputs/Password.svelte";
    import {useForm} from "@inertiajs/svelte";
    import type {PasswordValidationRules} from "@/types";
    import PasswordValidation from "@/Components/UI/PasswordValidation.svelte";

    const onsubmit = () => {
        $form.patch(route('password-reset.update'));
    }

    let form = useForm({
        new_password: '',
        new_password_confirmation: '',
    });

    const {
        rules
    } : {
        rules: PasswordValidationRules
    } = $props();
</script>

<Base {onsubmit}>
    <p class="text-muted-foreground">You are required to reset your password. Please reset your password below to access this system.</p>

    <PasswordValidation {rules} password={$form.new_password} />

    <FieldRow columns={1}>
        <Password label="New Password" bind:value={$form.new_password} error={$form.errors['new_password']} />
    </FieldRow>

    <FieldRow columns={1}>
        <Password label="Confirm New Password" bind:value={$form.new_password_confirmation} error={$form.errors['new_password_confirmation']} />
    </FieldRow>
</Base>

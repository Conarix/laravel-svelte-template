<script lang="ts">

    import Base from "@/Components/Forms/Partials/Base.svelte";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import Text from "@/Components/Inputs/Text.svelte";
    import type {Form, SelectOptions} from "@/types";
    import type {UserFormInner} from "@/utils/Admin/users";
    import Select from "@/Components/Inputs/Select.svelte";
    import Password from "@/Components/Inputs/Password.svelte";
    import Checkbox from "@/Components/Inputs/Checkbox.svelte";

    const {
        form = $bindable(),
        onsubmit,
        roles,
        permissions,
        update = false,
    }: {
        form: Form<UserFormInner>,
        onsubmit: () => void,
        roles: SelectOptions,
        permissions: SelectOptions,
        update?: boolean,
    } = $props();

</script>

<Base {onsubmit}>
    <FieldRow columns={2}>
        <Text label="Username" bind:value={$form.name} error={$form.errors.name} />

        <Text label="Email Address" bind:value={$form.email} error={$form.errors.email} />
    </FieldRow>

    <FieldRow columns={2}>
        <Select label="Role" bind:value={
            () => $form.role ?? '',
            (v) => v === '' ? $form.role = null : $form.role = v
        } options={roles} error={$form.errors.role} />

        <Select label="Permissions" bind:value={$form.permissions} options={permissions} multiple error={$form.errors.permissions} />
    </FieldRow>

    <FieldRow columns={2}>
        <Password label="Password" bind:value={$form.password} hint="Leave passwords blank to keep password unchanged" error={$form.errors.password} />
        <Password label="Confirm Password" bind:value={$form.password_confirmation} />
    </FieldRow>

    {#if update}
        <FieldRow columns={1}>
            <Checkbox
                label="Reset Password on Login"
                bind:checked={$form.reset_password_on_login}
                error={$form.errors.reset_password_on_login}
            />
        </FieldRow>
    {/if}
</Base>

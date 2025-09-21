<script lang="ts">
    import HeaderButtons from '@/Layouts/HeaderButtons.svelte'
    import type {CloseDialogFunction, HeaderButton, OpenDialogFunction, PasswordValidationRules} from "@/types";
    import PageHeading from "@/Components/UI/PageHeading.svelte";
    import type {User} from "@/types";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import FieldDisplay from "@/Components/UI/FieldDisplay.svelte";
    import FieldDisplayList from "@/Components/UI/FieldDisplayList.svelte";
    import {dateToString, titleCase} from "@/utils/helpers";
    import Base from "@/Components/Forms/Partials/Base.svelte";
    import {router, useForm} from "@inertiajs/svelte";
    import Password from "@/Components/Inputs/Password.svelte";
    import {toast} from "svelte-sonner";
    import Dialog from "@/Components/UI/Dialog.svelte";
    import Button from "@/Components/UI/Button.svelte";
    import Debug from "@/Components/Debug.svelte";
    import PasswordValidation from "@/Components/UI/PasswordValidation.svelte";


    let openDeleteDialog: OpenDialogFunction = $state();
    let closeDeleteDialog: CloseDialogFunction = $state();

    const buttons: HeaderButton[] = [
        {
            onclick: () => openDeleteDialog?.(),
            label: "Delete",
            variant: 'destructive',
        }
    ];

    let {
        user,
        rules,
    } : {
        user: User;
        rules: PasswordValidationRules;
    } = $props();

    let form = useForm({
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
    });

    const onsubmit = () => {
        $form.patch(
            route('account.update-password'),
            {
                onError: () => toast.error("Failed to update password"),
                onSuccess: () => {
                    $form.current_password = '';
                    $form.new_password = '';
                    $form.new_password_confirmation = '';
                }
            }
        )
    }

    const deleteAccount = () => router.delete(
        route('account.destroy'),
        {
            onError: () => toast.error("Failed to delete account"),
        }
    )
</script>

<svelte:head>
    <title>Account</title>
</svelte:head>

<HeaderButtons {buttons}>
    <PageHeading text="Account Management" />

    <div class="flex flex-col justify-start items-center w-full gap-4">
        <FieldRow columns={2}>
            <FieldDisplay label="Name" value={user.name} />
            <FieldDisplay label="Email Address" value={user.email} />
        </FieldRow>

        <FieldRow columns={2}>
            <FieldDisplay label="Role" value={user.role.name} />
            <FieldDisplayList label="Additional Permissions" values={user.permissions} key="name" nameCast={titleCase} />
        </FieldRow>

        <FieldRow columns={1}>
            <FieldDisplay label="Created At" value={user.created_at} valueCast={dateToString()} />
        </FieldRow>

        <hr class="w-full" />

        <Base {onsubmit}>
            <p class="w-full italic">Fill out the form below to change your password.</p>

            <PasswordValidation {rules} password={$form.new_password} />

            <FieldRow columns={1}>
                <Password label="Current Password" bind:value={$form.current_password} error={$form.errors['current_password']} />
                <Password label="New Password" bind:value={$form.new_password} error={$form.errors['new_password']} />
                <Password label="New Password Confirmation" bind:value={$form.new_password_confirmation} />
            </FieldRow>

            <Debug {$form} />
        </Base>
    </div>

    <Dialog bind:openDialog={openDeleteDialog} bind:closeDialog={closeDeleteDialog}>
        <h1 class="text-xl font-bold">Delete Account</h1>

        <p>Are you sure you want to delete this account?</p>

        <div class="flex justify-end items-center w-full gap-4">
            <Button type="button" variant="destructive" onclick={deleteAccount}>
                Delete
            </Button>
        </div>
    </Dialog>
</HeaderButtons>

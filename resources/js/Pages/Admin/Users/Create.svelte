<script lang="ts">

    import type {HeaderButton, SelectOptions} from "@/types";
    import HeaderButtons from "@/Layouts/HeaderButtons.svelte";
    import PageHeading from "@/Components/UI/PageHeading.svelte";
    import {useForm} from "@/utils/precognition.svelte";
    import {newUserForm} from "@/utils/Admin/users";
    import UserForm from "@/Components/Forms/Admin/UserForm.svelte";
    import Debug from "@/Components/Debug.svelte";

    const {
        roles,
        permissions,
    } : {
        roles: SelectOptions,
        permissions: SelectOptions,
    } = $props();

    const buttons: HeaderButton[] = [
        {
            href: route('admin.users.index'),
            label: 'Cancel',
            variant: 'destructive'
        }
    ];

    let form = $state(useForm(
        newUserForm(),
        'post',
        route('admin.users.store'),
    ))

    const onsubmit = () => {
        $form.post(route('admin.users.store'));
    }
</script>

<svelte:head>
    <title>Create User</title>
</svelte:head>

<HeaderButtons {buttons}>
    <PageHeading text="Create New User" />

    <UserForm bind:form {onsubmit} {roles} {permissions} />

    <Debug {$form} />
</HeaderButtons>

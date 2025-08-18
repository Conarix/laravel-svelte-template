<script lang="ts">
    import type {User, Option, HeaderButton} from "@/types";
    import HeaderButtons from "@/Layouts/HeaderButtons.svelte";
    import Debug from "@/Components/Debug.svelte";
    import PageHeading from "@/Components/UI/PageHeading.svelte";
    import UserForm from "@/Components/Forms/Admin/UserForm.svelte";
    import {useForm} from "@inertiajs/svelte";
    import {newUserForm} from "@/utils/Admin/users";

    type Props = {
        user: User,
        roles: Option[],
        permissions: Option[],
    }

    const {
        user,
        roles,
        permissions
    } : Props = $props();

    const onsubmit = () => {
        $form.put(route("admin.users.update", [user.id]));
    }

    const buttons: HeaderButton[] = [
        {
            href: route('admin.users.show', [user.id]),
            label: 'Cancel',
            variant: 'destructive'
        },
        {
            onclick: onsubmit,
            label: 'Save',
            variant: 'default',
        }
    ];

    let form = $state(useForm(newUserForm(user)))
</script>

<svelte:head>
    <title>Edit User</title>
</svelte:head>

<HeaderButtons {buttons}>
    <PageHeading text="Edit User - {user.name}" />

    <UserForm bind:form {onsubmit} {roles} {permissions} />

    <Debug {$form} />
</HeaderButtons>

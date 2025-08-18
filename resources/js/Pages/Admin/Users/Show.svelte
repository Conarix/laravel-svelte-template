<script lang="ts">

    import type {HeaderButton, User, WithAuditTracks} from "@/types";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import FieldDisplay from "@/Components/UI/FieldDisplay.svelte";
    import FieldDisplayList from "@/Components/UI/FieldDisplayList.svelte";
    import {dateToString, dialogHide, dialogShow, titleCase} from "@/utils/helpers";
    import HeaderButtons from "@/Layouts/HeaderButtons.svelte";
    import PageHeading from "@/Components/UI/PageHeading.svelte";
    import {PermissionEnum} from "@/types/enums";
    import Dialog from "@/Components/UI/Dialog.svelte";
    import Button from "@/Components/UI/Button.svelte";
    import {router} from "@inertiajs/svelte";
    import Debug from "@/Components/Debug.svelte";
    import {toast} from "svelte-sonner";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import ShowAuditHistory from "@/Components/UI/ShowAuditHistory.svelte";

    const {
        user
    } : {
        user: WithAuditTracks<User>
    } = $props();

    const buttons: HeaderButton[] = $derived.by(() => {
        const btns: HeaderButton[] = [
            {
                href: route("admin.users.index"),
                label: 'Back To List',
                variant: 'default',
            },
            {
                href: route('admin.users.edit', [user.id]),
                label: 'Edit',
                variant: 'default',
                permission: PermissionEnum.USERS_EDIT
            },
            {
                onclick: () => dialogShow("token-regenerate-confirmation"),
                label: 'Regenerate Token',
                variant: 'destructive',
                permission: PermissionEnum.USERS_EDIT
            }
        ];

        if (user.deleted_at) {
            btns.push({
                href: route('admin.users.restore', [user.id]),
                label: 'Restore',
                variant: 'default',
                method: 'post',
            })
        } else {
            btns.push({
                onclick: () => dialogShow("user-delete-confirmation"),
                label: 'Delete',
                variant: 'destructive',
                permission: PermissionEnum.USERS_DELETE
            });
        }

        return btns;
    });

    const deleteUser = () => {
        router.delete(
            route('admin.users.delete', [user.id]),
            {
                onFinish: () => dialogHide("user-delete-confirmation"),
                onError: () => toast.error("Failed to delete user"),
            },
        );
    }

    const regenerateAPIToken = () => {
        router.post(
            route('admin.users.token.regenerate', [user.id]),
            undefined,
            {
                onFinish: () => dialogHide("token-regenerate-confirmation"),
                onError: () => toast.error("Failed to regenerate API Token"),
            }
        )
    }

</script>

<svelte:head>
    <title>Viewing {user.name}</title>
</svelte:head>

<HeaderButtons {buttons}>
    <PageHeading text="Viewing User - {user.name}" />

    <FieldRow columns={1}>
        <FieldDisplay label="Email Address" value={user.email} />
    </FieldRow>

    <FieldRow columns={2}>
        <FieldDisplay label="User Role" value={user.role.name} />
        <FieldDisplayList label="Specially Assigned Permissions" values={user.permissions} key="name" nameCast={titleCase} />
    </FieldRow>

    <FieldRow columns={3}>
        <FieldDisplay label="Created At" value={user.created_at} valueCast={dateToString()} />
        <FieldDisplay label="Updated At" value={user.updated_at} valueCast={dateToString()} />
        <FieldDisplay label="Deleted At" value={user.deleted_at} valueCast={dateToString()} />
    </FieldRow>

    {#if user.api_token}
        <FieldRow columns={1}>
            <FieldDisplay label="API Token" value={user.api_token} hint="Take note of this value as you will not be able to see this again." />
        </FieldRow>
    {/if}

    <ShowAuditHistory auditTracked={user} />

    <Dialog id="user-delete-confirmation">
        <h1 class="text-xl">Delete User - {user.name}?</h1>

        <hr class="my-1 border-border w-full" />

        <p>Are you sure you want to delete this user?</p>

        <div class="flex justify-end items-center gap-2 w-full">
            <Button variant="destructive" onclick={deleteUser}>
                Delete
            </Button>
        </div>
    </Dialog>

    <Dialog id="token-regenerate-confirmation">
        <h1 class="text-xl">Regenerate API Token for {user.name}?</h1>

        <hr class="my-1 border-border w-full" />

        <p>Are you sure you would like to regenerate this user's API token?</p>
        <p>Any system using this token will stop working.</p>

        <div class="flex justify-end items-center gap-2 w-full">
            <Button variant="destructive" onclick={regenerateAPIToken}>
                Regenerate API Token
            </Button>
        </div>
    </Dialog>

    <Debug {user} />
</HeaderButtons>

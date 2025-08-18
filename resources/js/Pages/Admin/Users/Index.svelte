<script lang="ts">

    import type {CastFunction, HeaderButton, Paginator, User} from "@/types";
    import UserTable from "@/Components/Tables/UserTable.svelte";
    import HeaderButtons from "@/Layouts/HeaderButtons.svelte";
    import {PermissionEnum} from "@/types/enums";
    import {dateToString} from "@/utils/helpers";

    const buttons: HeaderButton[] = [
        {
            href: route('admin.users.create'),
            label: "Add New",
            permission: PermissionEnum.USERS_CREATE,
        }
    ]

    const {
        users
    } : {
        users: Paginator<User>
    } = $props();

    const columns: (keyof User)[] = [
        'name',
        'email',
        'deleted_at',
    ];

    const casts: Partial<{ [key in keyof User]: CastFunction<any> }> = {
        "deleted_at": dateToString('d/m/Y H:i:s')
    }

    const filterable: boolean = true;
</script>

<svelte:head>
    <title>Users</title>
</svelte:head>

<HeaderButtons {buttons} >
    <UserTable
        paginator={users}
        {columns}
        {casts}
        {filterable}
    />
</HeaderButtons>

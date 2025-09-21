<script lang="ts">
    import type {HeaderButton, Permission, RoleWithPermissions} from "@/types";
    import HeaderButtons from "@/Layouts/HeaderButtons.svelte";
    import Debug from "@/Components/Debug.svelte";
    import PageHeading from "@/Components/UI/PageHeading.svelte";
    import {type InertiaForm, useForm} from "@inertiajs/svelte";
    import type {Writable} from "svelte/store";
    import {onMount} from "svelte";
    import {cn, titleCase} from "@/utils/helpers";
    import {inputClasses} from "@/Components/Inputs/Partials/classes";

    type Props = {
        roles: RoleWithPermissions[],
        permissions: Permission[],
    }

    type FormData = {
        role_permissions: {
            [key: RoleWithPermissions['id']]: Permission['id'][],
        }
    }

    const {
        roles,
        permissions
    } : Props = $props();

    const onsubmit = () => {
        $form.put(route("admin.permissions.update"));
    }

    const buttons: HeaderButton[] = [
        {
            onclick: onsubmit,
            label: 'Save',
            variant: 'default',
        }
    ];

    let form: Writable<InertiaForm<FormData>> = $state(useForm({
        role_permissions: {}
    }));
    let columns: number = $state(0);

    onMount(() => {
        for (const role of roles) {
            $form.role_permissions[role.id] = role.permissions.map((permission) => permission.id);
        }
        columns = permissions.length + 2; // Two columns for role name
    });
</script>

<svelte:head>
    <title>Edit User</title>
</svelte:head>

<HeaderButtons {buttons}>
    <PageHeading text="Edit Role Permissions" />

    <div class="grid divide-y *:p-2">
        <div class="sticky top-0 grid gap-4 *:text-lg *:font-semibold" style:grid-template-columns={`repeat(${columns}, minmax(0, 1fr)`}>
            <span class="col-span-2">Role</span>
            {#each permissions as permission (permission.id)}
                <span>{permission.display_name}</span>
            {/each}
        </div>
        {#each roles as role (role.id)}
            <div class="grid gap-4 items-center even:bg-accent" style:grid-template-columns={`repeat(${columns}, minmax(0, 1fr)`}>
                <span class="col-span-2 text-lg font-semibold">{titleCase(role.name)}</span>
                {#each permissions as permission (permission.id)}
                    <span>
                        <input
                            type="checkbox"
                            class={cn(inputClasses, ["w-full aspect-square accent-primary"])}
                            name={role.name}
                            value={permission.id}
                            bind:group={$form.role_permissions[role.id]}
                        />
                    </span>
                {/each}
            </div>
        {/each}
    </div>

    <Debug {$form} />
</HeaderButtons>

<script lang="ts">
    import {dateToString, titleCase} from "@/utils/helpers";
    import type {AuditTrack} from "@/types";
    import {Link} from "@inertiajs/svelte";
    import HasPermission from "@/Components/Conditionals/HasPermission.svelte";
    import {ChangeTypeEnum, PermissionEnum} from "@/types/enums";
    import {Link as LinkIcon} from '@lucide/svelte';

    let {
        auditTrack
    }: {
        auditTrack: AuditTrack
    } = $props();
</script>

<div class="flex justify-between items-center w-full gap-12 px-1 py-2">
    <div class="flex flex-col justify-center items-start flex-shrink gap-2">
        <HasPermission all={[PermissionEnum.USERS_VIEW]}>
            <Link class="flex gap-4 text-lg" href={route('admin.users.show', [auditTrack.user.id])}>
                { auditTrack.user.name }

                <LinkIcon/>
            </Link>

            {#snippet otherwise()}
                <span class="text-lg">{auditTrack.user.name}</span>
            {/snippet}
        </HasPermission>

        <span>{ dateToString()(auditTrack.created_at) }</span>
    </div>

    <div class="flex flex-col justify-start items-start flex-grow gap-2">
        {#if auditTrack.type === ChangeTypeEnum.Update}
            <h2 class="text-lg font-semibold">Changes:</h2>
            {#each Object.entries(auditTrack.changes) as [field, newValue] (field)}
                <span>{ titleCase(field) }: { newValue } </span>
            {/each}
        {:else}
            <h2 class="text-lg font-semibold">
                {#if auditTrack.type === ChangeTypeEnum.Creation}
                    Record Created
                {:else if auditTrack.type === ChangeTypeEnum.Deletion}
                    Record Deleted
                {:else if auditTrack.type === ChangeTypeEnum.Restoration}
                    Record Restored
                {/if}
            </h2>
        {/if}
    </div>
</div>

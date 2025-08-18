<script lang="ts">

    import type {WithAuditTracks} from "@/types";
    import Collapsible from "@/Components/UI/Collapsible.svelte";
    import {ChevronDown} from "@lucide/svelte";
    import DisplayAuditTrack from "@/Components/UI/DisplayAuditTrack.svelte";

    let {
        auditTracked
    } : {
        auditTracked: WithAuditTracks<any>
    } = $props();
</script>

<Collapsible>
    {#snippet trigger(openState)}
        <div class="flex justify-between items-center w-full gap-4">
            <h1 class="text-2xl font-semibold">View Update History</h1>

            <ChevronDown class={['transition-[rotate]', 'duration-100', openState && 'rotate-180']} />
        </div>
    {/snippet}

    <div class="flex flex-col justify-start items-center w-full gap-4 my-4 divide-y-2 divide-border">
        {#each auditTracked.audit_tracks as auditTrack (auditTrack.id)}
            <DisplayAuditTrack {auditTrack} />
        {:else}
            <h3 class="text-lg font-semibold">No updates on this record.</h3>
        {/each}
    </div>
</Collapsible>

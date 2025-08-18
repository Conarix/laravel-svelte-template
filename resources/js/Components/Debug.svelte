<script lang="ts">

    import {page} from "@inertiajs/svelte";
    import Card from "@/Components/UI/Card.svelte";

    let props = $props();
</script>

{#if $page.props.environment === 'local'}
    <div class="shadow p-4 rounded-lg">
        <pre>{JSON.stringify(props, null, 4)}</pre>
    </div>
{/if}

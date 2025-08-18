<script lang="ts">

    const {
        error
    } : {
        error?: string
    } = $props();
</script>

{#if error}
    <span class="text-red-500">
        {error}
    </span>
{/if}

<script lang="ts">
    const {
        hint,
    } : {
        hint?: string,
    } = $props();
</script>

{#if hint}
    <span class="text-muted-foreground text-sm italic">{hint}</span>
{/if}

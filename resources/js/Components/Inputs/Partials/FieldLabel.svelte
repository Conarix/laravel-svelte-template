<script lang="ts">

    import type {Snippet} from "svelte";

    const {
        id,
        children,
    } : {
        id: string
        children: Snippet
    } = $props();
</script>

<label class="font-semibold" for={id}>
    {@render children?.()}
</label>

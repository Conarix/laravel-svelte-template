<script lang="ts">

    import type {Snippet} from "svelte";

    let {
        children,
    } : {
        children: Snippet
    } = $props();
</script>

<div class="flex flex-col justify-center items-center bg-card rounded-lg border p-6 w-full max-w-md shadow-lg">
    {@render children()}
</div>

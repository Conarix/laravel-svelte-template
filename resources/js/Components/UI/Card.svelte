<script lang="ts">

    import type {Snippet} from "svelte";

    let {
        className = '',
        children,
    } : {
        className?: string;
        children: Snippet
    } = $props();
</script>

<div class="flex flex-col justify-center items-center bg-card rounded-lg border p-6 w-full shadow-lg {className}">
    {@render children()}
</div>

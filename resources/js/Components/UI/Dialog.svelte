<script lang="ts">


    import type {Snippet} from "svelte";

    const {
        id,
        children,
    } : {
        id: string,
        children: Snippet
    } = $props();

    let self: HTMLDivElement;
</script>

<div
    bind:this={self}
    class="absolute top-0 left-0 justify-center items-center bg-gray-400/30 backdrop-blur-sm w-full min-h-screen"
    {id}
    style:display="none"
    onclick={() => self.style.display = 'none'}
    onkeydown={(e: KeyboardEvent) => {e.key === "Escape" && (self.style.display = 'none')}}
    role="button"
    tabindex="-1"
>
    <div
        class="flex flex-col justify-start items-start gap-4 max-w-1/2 bg-sidebar border rounded-lg p-8"
        onclick={(e: Event) => e.stopPropagation()}
        onkeydown={(e: KeyboardEvent) => {e.key === "Escape" && (self.style.display = 'none')}}
        role="button"
        tabindex="-1"
    >
        {@render children()}
    </div>
</div>

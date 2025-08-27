<script lang="ts">
    import {onMount, type Snippet} from "svelte";
    import type {CloseDialogFunction, OpenDialogFunction} from "@/types";

    let {
        children,
        openDialog = $bindable(),
        closeDialog = $bindable(),
    } : {
        children: Snippet
        openDialog: OpenDialogFunction,
        closeDialog: CloseDialogFunction,
    } = $props();

    let self: HTMLDivElement;

    const openFunction: OpenDialogFunction = () => {
        if (! self) {
            console.error("Attempt to open dialog before it is properly set");
            return;
        }

        self.style.display = "flex";
        self.focus();
    }

    const closeFunction: CloseDialogFunction = () => {
        if (! self) {
            console.error("Attempt to close dialog before it is properly set");
            return;
        }

        self.style.display = "none";
    }

    onMount(() => {
        openDialog = openFunction;
        closeDialog = closeFunction;
    })
</script>

<div
    bind:this={self}
    class="absolute top-0 left-0 justify-center items-center bg-gray-400/30 backdrop-blur-sm w-full min-h-screen"
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

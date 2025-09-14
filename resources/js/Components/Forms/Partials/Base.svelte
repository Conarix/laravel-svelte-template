<script lang="ts">

    import type {Snippet} from "svelte";
    import FieldRow from "@/Components/Forms/Partials/FieldRow.svelte";
    import Button from "@/Components/UI/Button.svelte";

    const {
        onsubmit,
        submitDisabled = false,
        children,
        beforeSubmit,
    } : {
        onsubmit: () => void,
        submitDisabled?: boolean
        children: Snippet
        beforeSubmit?: Snippet
    } = $props();
</script>

<form class="flex flex-col justify-start items-center w-full gap-4" onsubmit={(e) => {e.preventDefault(); onsubmit();}}>
    {@render children()}

    <FieldRow justify="between" align="center">
        <div class="flex justify-start items-center gap-2 flex-grow">
            {@render beforeSubmit?.()}
        </div>

        <div class="flex justify-end items-center flex-shrink">
            <Button type="submit" disabled={submitDisabled}>
                Submit
            </Button>
        </div>
    </FieldRow>
</form>

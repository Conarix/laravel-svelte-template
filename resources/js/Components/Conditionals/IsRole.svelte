<script lang="ts">

    import { page } from "@inertiajs/svelte";
    import type {RoleEnum} from "@/types/enums";
    import type {Snippet} from "svelte";

    const {
        role,
        children
    } : {
        role: RoleEnum
        children: Snippet
    } = $props();
</script>

{#if $page.props.auth.user?.role?.name === role}
    {@render children()}
{/if}

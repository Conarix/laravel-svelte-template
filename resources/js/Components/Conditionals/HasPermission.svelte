<script lang="ts">

    import { page } from "@inertiajs/svelte";
    import type {PermissionEnum} from "@/types/enums";
    import type {Snippet} from "svelte";

    const {
        any = [],
        all = [],
        otherwise,
        children,
    } : {
        any?: (PermissionEnum|undefined)[],
        all?: (PermissionEnum|undefined)[],
        otherwise?: Snippet,
        children: Snippet,
    } = $props();

    let allAllows = $derived(
        all.length > 0
            ? all.filter((value) => value !== undefined).every((value) => $page.props.auth.permissions[value])
            : true
    );

    let anyAllows = $derived(
        any.length > 0
            ? any.filter((value) => value !== undefined).some((value) => $page.props.auth.permissions[value])
            : true
    );

    let allowed = $derived(allAllows && anyAllows);

    // console.log({ any, all, anyAllows, allAllows, allowed})
</script>

{#if allowed}
    {@render children()}
{/if}

{#if !allowed && otherwise !== undefined}
    {@render otherwise()}
{/if}

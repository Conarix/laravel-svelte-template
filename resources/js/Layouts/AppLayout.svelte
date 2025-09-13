<script lang="ts">
    import type {Snippet} from "svelte";
    import Breadcrumbs from "@/Components/Layout/Breadcrumbs.svelte";
    // import { default as NavMenu } from "@/Components/Layout/Navigation/SideNav.svelte";
    import { default as NavMenu } from "@/Components/Layout/Navigation/TopNav.svelte";
    import ToastFromSession from "@/Components/Layout/ToastFromSession.svelte";

    let {children}: { children: Snippet } = $props();
</script>

<main class="w-full">
    <NavMenu />

    <div class="flex flex-col justify-start items-center w-full p-8">
        <Breadcrumbs/>

        <div class="flex flex-col justify-start items-center w-full m-8">
            {@render children()}
        </div>
    </div>
</main>

<ToastFromSession />

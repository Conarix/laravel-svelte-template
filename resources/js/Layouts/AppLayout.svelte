<script lang="ts">
    import type {Snippet} from "svelte";
    import Card from "@/Components/UI/Card.svelte";
    import AppLogo from "@/Components/UI/AppLogo.svelte";
    import Navigation from "@/Components/Layout/Navigation.svelte";
    import NavUser from "@/Components/Layout/NavUser.svelte";
    import Breadcrumbs from "@/Components/Layout/Breadcrumbs.svelte";
    import NavMenu from "@/Components/Layout/NavMenu.svelte";
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

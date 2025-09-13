<script lang="ts">
    import AppLogo from "@/Components/UI/AppLogo.svelte";
    import NavUser from "@/Components/Layout/Navigation/NavUser.svelte";
    import Navigation from "@/Components/Layout/Navigation/Navigation.svelte";
    import {Menu, X} from "@lucide/svelte";
    import {MediaQuery} from "svelte/reactivity";


    const smallView = new MediaQuery('width <= 48rem');
    let open = $state(false);

</script>

<header class="relative flex justify-between items-center w-full gap-4 px-4 py-2 bg-sidebar border-b shadow-lg">
    <div class="flex justify-start items-center gap-8">
        <div class="flex justify-center items-center w-full">
            <AppLogo/>
        </div>

        {#if !smallView.current}
            <Navigation bind:open {smallView} topNav minimal />
        {/if}
    </div>

    {#if !smallView.current}
        <NavUser topNav />
    {:else}
        {#if open}
            <X onclick={() => open = false} />
        {:else}
            <Menu onclick={() => open = true} />
        {/if}
    {/if}

    {#if open && smallView.current}
        <div class="absolute left-0 top-full w-full bg-sidebar border-b shadow-lg px-2 py-2 z-[1000]">
            <Navigation bind:open {smallView} />

            <hr class="my-1" />

            <NavUser topNav {smallView} />
        </div>
    {/if}
</header>

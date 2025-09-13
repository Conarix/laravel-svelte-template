<script lang="ts">

    import AppLogo from "@/Components/UI/AppLogo.svelte";
    import NavUser from "@/Components/Layout/Navigation/NavUser.svelte";
    import Navigation from "@/Components/Layout/Navigation/Navigation.svelte";
    import {ChevronsRight, ChevronsLeft} from "@lucide/svelte";
    import {MediaQuery} from "svelte/reactivity";

    let open = $state(false);
    let navMenuWidth = $state(-1000); // Start far off-screen

    const checkMousePosition = (event: MouseEvent) => {
        if (event.clientX <= 20) {
            console.log("Open nav menu");
            open = true;
        }
    }

    const closeNavMenu = () => {
        console.log("Close nav menu");
        open = false;
    }

    const smallView = new MediaQuery('width <= 48rem');

</script>

<svelte:window onmousemove={checkMousePosition}/>

<aside
    class="absolute top-0 flex h-[100dvh] transition-left duration-300 z-100"
    style:left={open ? 0 : `-${navMenuWidth}px`}
    onmouseleave={closeNavMenu}
>
    <div
        bind:clientWidth={navMenuWidth}
        class="flex flex-col justify-between w-2xs h-full bg-sidebar px-4 py-2 border-r shadow"
    >
        <div class="flex flex-col justify-start items-start gap-4">
            <div class="flex justify-center items-center w-full">
                <AppLogo/>
            </div>

            <Navigation bind:open {smallView} />
        </div>

        <NavUser/>
    </div>

    <div
        onclick={() => open = !open}
        onkeydown={() => {}}
        role="button"
        tabindex="-1"
        class="flex justify-center items-center size-12 mt-20 bg-sidebar border-t border-r border-b rounded-r-2xl cursor-pointer"
    >
        {#if open}
            <ChevronsLeft/>
        {:else}
            <ChevronsRight/>
        {/if}
    </div>
</aside>

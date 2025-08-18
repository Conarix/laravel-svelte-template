<script lang="ts">

import AppLogo from "@/Components/UI/AppLogo.svelte";
import NavUser from "@/Components/Layout/NavUser.svelte";
import Navigation from "@/Components/Layout/Navigation.svelte";

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

</script>

<svelte:window onmousemove={checkMousePosition} />

<aside
    bind:clientWidth={navMenuWidth}
    class="absolute top-0 flex flex-col justify-between max-w-2xs w-full h-screen bg-sidebar px-4 py-2 border-r shadow transition-left duration-300 z-100"
    style:left={open ? 0 : `-${navMenuWidth}px`}
    onmouseleave={closeNavMenu}
>
    <div class="flex flex-col justify-start items-start gap-4">
        <div class="flex justify-center items-center w-full">
            <AppLogo/>
        </div>

        <Navigation/>
    </div>

    <NavUser/>
</aside>

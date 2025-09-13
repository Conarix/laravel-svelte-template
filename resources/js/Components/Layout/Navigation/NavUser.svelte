<script lang="ts">
    import { Settings, LogOut, User } from "@lucide/svelte";
    import {page, router} from "@inertiajs/svelte";
    import ColourSchemeSelect from "@/Components/Layout/ColourSchemeSelect.svelte";
    import type {MediaQuery} from "svelte/reactivity";

    let user = $page.props.auth.user;

    let open = $state(false);

    const togglePopup = (e: MouseEvent | KeyboardEvent) => {
        console.log("Click / Keyboard Event");

        if (e.type === 'keydown' && (e as KeyboardEvent).key !== 'Enter') return;

        open = !open
    };

    const hidePopup = () => {
        console.log("Mouse leave event");

        open = false;
    }

    const logout = () => {
        router.post(route('auth.logout'));
    }

    const {
        topNav = false,
        smallView,
    } : {
        topNav?: boolean;
        smallView?: MediaQuery;
    } = $props();

</script>

{#if user}
    <div
        class={["relative", "flex", "justify-between", "items-center", !topNav && "w-full", "gap-2", "p-2", "hover:bg-accent", "rounded-lg"]}
        onclick={togglePopup}
        onkeydown={togglePopup}
        role="button"
        tabindex="0"
    >
        <div class="flex flex-col w-full">
            <span>{user.name}</span>
            <span class="text-muted-foreground">{user.email}</span>
        </div>

        <Settings />

        {#if open}
            <div
                class={[
                    "absolute",
                    topNav ? "top-[110%]" : "bottom-full",
                    topNav ? "right-0" : "left-0",
                    "flex",
                    "flex-col",
                    topNav && (smallView === undefined || !smallView.current) ? "w-2xs" : "w-full",
                    "gap-2",
                    "p-2",
                    "border",
                    "rounded-lg",
                    topNav && "bg-sidebar"
                ]}
                onclick={(e: Event) => e.stopPropagation()}
                onkeydown={(e: Event) => e.stopPropagation()}
                onmouseleave={hidePopup}
                role="dialog"
                tabindex="0"
            >
                <h1 class="font-semibold underline">Settings</h1>

                <div class="flex flex-col w-full">
                    <h2>Colour Scheme</h2>
                    <ColourSchemeSelect />
                </div>

                <a class="flex justify-between items-center w-full p-2 hover:bg-accent rounded-lg" href={route('account.show')}>
                    <User />

                    <span>Manage Account</span>
                </a>

                <button class="flex justify-between items-center w-full p-2 hover:bg-accent rounded-lg" onclick={logout}>
                    <LogOut class="text-red-500" />

                    <span>Logout</span>
                </button>
            </div>
        {/if}
    </div>
{/if}

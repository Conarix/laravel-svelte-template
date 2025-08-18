<script lang="ts">
    import type { Snippet } from "svelte";
    import Card from "@/Components/UI/Card.svelte";
    import AppLogo from "@/Components/UI/AppLogo.svelte";
    import { Toaster } from "svelte-sonner";

    let { children } : { children?: Snippet } = $props();
</script>

<div class="circles">
    {#each new Array(15) as _}
        <span class="bg-accent"></span>
    {/each}
</div>

<div class="flex flex-col justify-center items-center w-full gap-4 min-h-screen">
    <AppLogo />

    <Card>
        {@render children?.()}
    </Card>
</div>

<Toaster richColors position="top-right" />

<style lang="scss">
    @use 'sass:math';

    .circles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        margin: auto;
        padding: 0;
        overflow: hidden;
        z-index: -100;

        span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            animation: animate 25s linear infinite;
            bottom: -200px;
        }

        @for $i from 1 through 15 {
            $size: math.random(130) + 60 + px;
            span:nth-child(#{$i}) {
                left: math.percentage(calc(math.random(100) / 100));
                width: $size;
                height: $size;
                animation-delay: math.random(35) - 20 + s;
                animation-duration: math.random(20) + 5 + s;
                border-radius: math.random(30) + px;
            }
        }

    }

    @keyframes animate {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }

        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
            border-radius: 60%;
        }
    }
</style>

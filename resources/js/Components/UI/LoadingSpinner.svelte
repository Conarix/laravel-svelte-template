<script lang="ts">

    import {onMount} from "svelte";

    const {
        size = 16,
        speed = 100,
    } : {
        size?: number;
        speed?: number;
    } = $props();

    let spinner: HTMLDivElement;

    onMount(() => {
        const interval = setInterval(() => {
            spinner.querySelectorAll('.highlight').forEach((el) => {
                el.classList.remove('highlight');
                const next = el.nextElementSibling;

                if (next) {
                    next.classList.add('highlight');
                } else {
                    spinner.firstElementChild!.classList.add('highlight');
                }
            });
        }, speed);

        return () => clearInterval(interval);
    });
</script>

<div bind:this={spinner} class="relative aspect-square spinner" style:width={`${size / 4}rem`}>
    <div class="spinner-el highlight" style="--x: 0; --y: -2;"></div>
    <div class="spinner-el" style="--x: 1; --y: -1;"></div>
    <div class="spinner-el" style="--x: 2; --y: 0;"></div>
    <div class="spinner-el" style="--x: 1; --y: 1;"></div>
    <div class="spinner-el highlight" style="--x: 0; --y: 2;"></div>
    <div class="spinner-el" style="--x: -1; --y: 1;"></div>
    <div class="spinner-el" style="--x: -2; --y: 0;"></div>
    <div class="spinner-el" style="--x: -1; --y: -1;"></div>
</div>

<style>
    .spinner-el {
        --x: 0;
        --y: 0;
        position: absolute;
        top: calc(50% * sin(calc(45deg * var(--y))) + 50%);
        left: calc(50% * sin(calc(45deg * var(--x))) + 50%);
        transform: translateX(-50%) translateY(-50%);

        width: calc(100% / 6);
        aspect-ratio: 1 / 1;
        border-radius: 100%;
        background-color: var(--spinner-light-off, #000);

        transition: background-color 0.1s ease-out;
    }

    .spinner-el.highlight {
        background-color: var(--spinner-light-on, #888);
    }
</style>

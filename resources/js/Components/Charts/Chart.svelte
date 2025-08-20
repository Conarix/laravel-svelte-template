<script lang="ts">
    import {onMount} from "svelte";
    import Chart from "chart.js/auto";

    let {
        chart = $bindable(),
    } : {
        chart: Chart<'line'> | null
    } = $props();

    let ctx: HTMLCanvasElement | null = $state(null);
    chart = null;


    onMount(() => {
        if (ctx === null) {
            throw new Error("ctx was null");
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: []
            }
        });
    })
</script>

<div class="flex flex-col justify-center items-center w-full">
    <canvas bind:this={ctx}></canvas>
</div>

<script lang="ts">
    import {ChevronRight} from "@lucide/svelte";
    import {Link, page} from "@inertiajs/svelte";
    import {route} from "ziggy-js";
    import {is_numeric, titleCase} from "@/utils/helpers";

    const breadcrumbSteps = $derived.by<{ route?: string; label: string }[]>(() => {
        const steps: { route?: string; label: string }[] = [
            { route: route("dashboard"), label: "Home" }
        ];

        const loc = $page.props.ziggy.location as string;
        const parts = loc.split("/").slice(3);

        let running = "";
        for (const part of parts) {
            if (is_numeric(part)) continue;

            running += part;

            if (route().has(`${running}.index`)) {
                steps.push({ route: route(`${running}.index`), label: titleCase(part) });
            } else {
                steps.push({ label: titleCase(part) });
            }

            running += ".";
        }

        // de-dup by label
        return [...new Map(steps.map(s => [s.label, s])).values()];
    });
</script>

<div class="flex items-center gap-1 w-full">
    {#each breadcrumbSteps as step, i (i)}
        {#if step.route}
        <Link href={step.route}>
            <span>{step.label}</span>
        </Link>
        {:else}
            <span>{step.label}</span>
        {/if}

        {#if i !== breadcrumbSteps.length - 1}
            <ChevronRight />
        {/if}
    {/each}
</div>

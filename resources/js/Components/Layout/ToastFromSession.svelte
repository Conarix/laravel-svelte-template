<script lang="ts">
    import { page } from "@inertiajs/svelte";
    import { Toaster, toast } from "svelte-sonner";

    let messages = $derived($page.props.messages);

    $effect(() => {
        const toToast = Object.entries(messages)
            .filter(([_, message]) => message != null);

        for (const [method, message] of toToast) {
            //@ts-ignore
            toast[method.toLowerCase()](message);
        }
    })
</script>

<Toaster richColors position="top-right" />

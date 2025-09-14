<script lang="ts">
    import {page} from "@inertiajs/svelte";
    import Debug from "@/Components/Debug.svelte";
    import type {DateTime} from "luxon";

    let testDate: DateTime | null = $state(null);
</script>

<Debug {testDate} {$page} />

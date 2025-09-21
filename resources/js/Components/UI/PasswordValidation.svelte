<script lang="ts">

    import type {PasswordValidationRules} from "@/types";
    import { X, Check } from '@lucide/svelte';

    type Rule = {
        rule: keyof PasswordValidationRules;
        rule_display: string;
        condition: number | boolean;
        satisfied: boolean;
    }

    type CheckSatisfied = (rule: keyof PasswordValidationRules, condition: number | boolean) => boolean;

    const RULE_DISPLAY_MAP : { [key in keyof PasswordValidationRules]: string } = {
        min: "Minimum Length",
        max: "Maximum Length",
        mixedCase: "Lowercase and Uppercase",
        letters: "Contains Letters",
        numbers: "Contains Numbers",
        symbols: "Contains Symbols",
    } as const;

    const {
        rules,
        password,
    } : {
        rules: PasswordValidationRules,
        password: string
    } = $props();

    const checkRuleSatisfied: CheckSatisfied = (rule: keyof PasswordValidationRules, condition: number | boolean) => {
        switch (rule) {
            case 'min':
                return password.length >= (condition as number);
            case 'max':
                return password.length <= (condition as number);
            case 'letters':
                return /\p{L}/u.test(password);
            case 'numbers':
                return /\p{N}/u.test(password);
            case 'symbols':
                return /\p{Z}|\p{S}|\p{P}/u.test(password);
            case 'mixedCase':
                return /(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u.test(password);
        }
    }

    let applicableRules: Rule[] = $derived(
        Object.entries(rules)
            .map(([rule, condition]) => ({
                rule: rule as keyof PasswordValidationRules,
                rule_display: RULE_DISPLAY_MAP[rule as keyof PasswordValidationRules],
                condition: condition as unknown as number | boolean,
                satisfied: checkRuleSatisfied(rule as keyof PasswordValidationRules, condition)
            })).filter((rule) => rule.condition && !Array.isArray(rule.condition))
    );
</script>

<div class="flex flex-col items-center w-full gap-2">
    {#each applicableRules as rule (rule.rule)}
        <div class="flex items-center gap-2 w-full">
            {#if rule.satisfied}
                <Check color="green" />
            {:else}
                <X color="red" />
            {/if}

            <span>{rule.rule_display}</span>

            {#if typeof rule.condition === 'number'}
                <span>({rule.condition})</span>
            {/if}
        </div>
    {/each}
</div>

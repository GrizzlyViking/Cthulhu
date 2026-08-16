/*
 * Turning an occupation into form fields and back.
 *
 * The stored `skills` column is one array mixing plain slugs with the book's
 * two kinds of slot — "one interpersonal skill" (a choice from a named set) and
 * "any one other skill" (a free slot). That is awkward to edit as a single
 * list, so the form keeps the three apart and the server reassembles them; see
 * App\Http\Requests\OccupationRequest.
 */

/** The fields a brand new occupation starts from. */
export function blankOccupationForm(eraValues = []) {
    return {
        name: '',
        description: '',
        eras: [...eraValues],
        skill_points_formula: [{ multiplier: 2, options: ['education'] }],
        credit_rating_min: 9,
        credit_rating_max: 40,
        skills: [],
        choices: [],
        any_count: 0,
        any_label: '',
    };
}

/** The same fields, filled in from an occupation that already exists. */
export function occupationToForm(occupation, eraValues = []) {
    const entries = occupation.skills ?? [];

    const any = entries.find((entry) => typeof entry === 'object' && entry?.type === 'any');

    return {
        name: occupation.name ?? '',
        description: occupation.description ?? '',
        eras: [...(occupation.eras ?? eraValues)],
        skill_points_formula: (occupation.skill_points_formula ?? []).map((component) => ({
            multiplier: component.multiplier,
            options: [...component.options],
        })),
        credit_rating_min: occupation.credit_rating_min ?? 0,
        credit_rating_max: occupation.credit_rating_max ?? 99,
        skills: entries.filter((entry) => typeof entry === 'string'),
        choices: entries
            .filter((entry) => typeof entry === 'object' && entry?.type === 'choice')
            .map((entry) => ({
                count: entry.count ?? 1,
                options: [...(entry.options ?? [])],
                label: entry.label ?? '',
            })),
        any_count: any?.count ?? 0,
        any_label: any?.label ?? '',
    };
}

/**
 * The skill point pool the formula would give, for a set of characteristics —
 * the same sum the server makes, so the player sees it before saving.
 */
export function formulaPool(formula, stats) {
    return (formula ?? []).reduce((total, component) => {
        const options = component.options ?? [];
        if (options.length === 0) return total;

        const best = Math.max(...options.map((key) => Number(stats?.[key]) || 0));

        return total + (Number(component.multiplier) || 0) * best;
    }, 0);
}

/** "EDU × 2 + STR or DEX × 2", as the list and the sheet show it. */
export function formulaLabel(formula, characteristics) {
    return (formula ?? [])
        .filter((component) => (component.options ?? []).length > 0)
        .map((component) => {
            const options = component.options
                .map((key) => characteristics[key] ?? key.toUpperCase())
                .join(' or ');

            return `${options} × ${component.multiplier}`;
        })
        .join(' + ');
}

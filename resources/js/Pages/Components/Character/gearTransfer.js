/** Semicolons and newlines separate possessions; commas can belong to a name. */
export function parseGear(text, catalogue) {
    return text.split(/[;\r\n]+/u)
        .map((name, source_index) => ({ source_index, name: name.trim().replace(/^(?:[-*•]\s+|\d+[.)]\s+)/u, '').trim() }))
        .filter(({ name }) => name)
        .map(({ name, source_index }) => {
            const matches = ['weapon', 'equipment'].flatMap((type) =>
                catalogue[type].filter((item) => item.name.toLowerCase() === name.toLowerCase())
                    .map((item) => ({ type, item })));
            const match = matches.length === 1 ? matches[0] : null;
            const looksLikeWeapon = /\b(revolver|pistol|handgun|rifle|shotgun|musket|sword|dagger|knife|pocketknife|bayonet|whip|crossbow)\b/iu.test(name)
                && !/\b(ammo|ammunition|rounds|bullets|shells|cartridges|holster|scabbard|sheath|cleaning|case|magazine)\b/iu.test(name);
            return { name, source: name, source_index, remaining: '', type: match?.type ?? (looksLikeWeapon ? 'weapon' : 'equipment'), catalogue_id: match?.item.id ?? null,
                quantity: 1, damage: '', skill: '', include: true };
        });
}

/** Keep the part of a source entry that the player did not choose to transfer. */
export function remainingGear(source, name) {
    const original = source.trim();
    const words = original.match(/\S+/gu) ?? [];
    const chosen = name.trim().match(/\S+/gu) ?? [];
    if (!chosen.length) return original;
    const normalise = (word) => word.toLowerCase().replace(/^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/gu, '');
    const sameWord = (left, right) => {
        const a = normalise(left), b = normalise(right);
        if (a === b) return true;
        // A small spelling correction must not leave the original item behind.
        if (Math.min(a.length, b.length) < 5) return false;
        const distances = Array.from({ length: b.length + 1 }, (_, i) => i);
        for (let i = 1; i <= a.length; i++) {
            let previous = distances[0];
            distances[0] = i;
            for (let j = 1; j <= b.length; j++) {
                const old = distances[j];
                distances[j] = Math.min(distances[j] + 1, distances[j - 1] + 1, previous + (a[i - 1] === b[j - 1] ? 0 : 1));
                previous = old;
            }
        }
        return distances[b.length] <= 1;
    };
    for (let start = 0; start <= words.length - chosen.length; start++) {
        if (!chosen.every((word, offset) => sameWord(words[start + offset], word))) continue;
        return [...words.slice(0, start), ...words.slice(start + chosen.length)].join(' ')
            .replace(/^(?:and\b|&)\s*|\s*(?:\band|&)$/giu, '')
            .replace(/^[,;\s]+|[,;\s]+$/gu, '').trim();
    }
    // A rewritten name may refer to only part of this entry; let the player say what stays.
    return original;
}

/*
 * The era a sheet is played in, and whether a thing belongs to it.
 *
 * `eras` on a skill, weapon or catalogue item is a list of era values, and
 * something available throughout carries all of them. A missing or empty list
 * means the server has nothing to say about it, which is treated as "belongs
 * everywhere" rather than "belongs nowhere" — the sheet must never swallow a
 * thing over a blank column.
 */
export const belongsToEra = (thing, era) =>
    !era || !thing?.eras?.length || thing.eras.includes(era);

/**
 * The era's short name, for a chip. Falls back to the raw value so an era the
 * frontend has not been told about still reads as something.
 */
export const eraLabel = (value, options = []) =>
    options.find((option) => option.value === value)?.short ?? value;

/**
 * The eras a thing belongs to, spelled out — "1920s", or "1920s and Modern".
 */
export const eraSummary = (thing, options = []) =>
    (thing?.eras ?? []).map((value) => eraLabel(value, options)).join(' and ');
